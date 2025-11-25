<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

   protected $fillable = [
    'quotation_id', 
    'order_id', 
    'client_id', 
    'amount', 
    'amount_paid',
    'items', 
    'payment_type', 
    'grace_period',   // ✅ ADD THIS
    'due_date', 
    'status', 
    'po_no', 
    'is_synced', 
    'client_invoice_id'
];




    protected $casts = [
        'due_date' => 'datetime',
        'items' => 'array',
    ];

    public function order()
{
    return $this->belongsTo(Order::class);
}


public function quotation()
{
    return $this->belongsTo(\App\Models\Quotation::class);
}

public function products()
{
    return $this->belongsToMany(\App\Models\Product::class)
                ->withPivot('quantity','unit_price','variations')
                ->withTimestamps();
}



    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function updateStatus()
    {
        if ($this->amount_paid >= $this->amount) {
            $this->status = 'paid';
        } elseif ($this->amount_paid > 0) {
            $this->status = 'partially_paid';
        } else {
            $this->status = 'pending';
        }
        $this->save();
    }

    public function getRemainingBalanceAttribute()
    {
        return $this->amount - $this->amount_paid;
    }

protected static function booted()
{
    static::updated(function ($invoice) {
        if ($invoice->wasChanged(['status', 'amount_paid'])) {
            try {
                $response = \Http::withHeaders([
                    'Authorization' => 'Bearer ' . env('SYNC_API_TOKEN'),
                    'Content-Type'  => 'application/json',
                ])->post(env('SYNC_UPDATE_API_URL'), [
                    'id'          => $invoice->client_invoice_id ?? $invoice->id,
                    'status'      => $invoice->status,
                    'amount_paid' => $invoice->amount_paid,
                    'order_id'    => $invoice->order_id,
                    'client_id'   => $invoice->client_id,
                    'updated_at'  => now(),
                ]);

                if ($response->successful()) {
                    \Log::info('⚡ Auto-updated invoice pushed to online', [
                        'invoice_id'   => $invoice->id,
                        'status'       => $invoice->status,
                        'amount_paid'  => $invoice->amount_paid,
                        'response'     => $response->json(),
                    ]);
                } else {
                    \Log::warning('⚠️ Invoice push failed with response', [
                        'invoice_id' => $invoice->id,
                        'response'   => $response->body(),
                    ]);
                }
            } catch (\Throwable $e) {
                \Log::error('❌ Auto-sync failed', [
                    'invoice_id' => $invoice->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    });
}




}