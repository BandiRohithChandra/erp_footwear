<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
    'user_id',
    'client_id',
    'customer_name',
    'cart_items',
    'subtotal',
    'gst',
    'cgst',
    'sgst',
    'igst',
    'total',
    'paid_amount',
    'balance',
    'payment_method',
    'payment_status', // <-- add this
    'address',
    'city',
    'state',
    'pincode',
    'mobile',
    'pan_no',
    'email',
    'status',
    'transport_name',
    'transport_address',
    'transport_phone',
    'transport_id',
    'company_name',
    'company_address',
    'company_gst',
    'po_no',
    'article_no',
    
];


    protected $casts = [
        'cart_items' => 'array', // decode JSON automatically
    ];

    // 🔗 Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
        // or Client::class if you have separate clients table
    }

    public function invoice()
    {
        return $this->hasOneThrough(
            Invoice::class,         // Final model
            ProductionOrder::class, // Intermediate model
            'client_order_id',      // Foreign key on ProductionOrder pointing to Order
            'order_id',             // Foreign key on Invoice pointing to ProductionOrder
            'id',                   // Local key on Order
            'id'                    // Local key on ProductionOrder
        );
    }

    public function productionOrder()
    {
        return $this->hasOne(ProductionOrder::class, 'client_order_id');
    }

    // Keep only if you have order_product pivot table
    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_product')
                    ->withPivot('quantity', 'price')
                    ->withTimestamps();
    }


    public function salesRep()
{
    return $this->client?->salesRep();
}
}
