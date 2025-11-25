<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionOrder extends Model
{
    protected $fillable = [
        'stage',
        'quotation_id',
        'status',
        'due_date',
        'client_order_id', // add this
    ];


    protected $casts = [
        'due_date' => 'date',
    ];


    public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'quotation_id');
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'order_id');
    }

    // --- ADDED: product() relationship expected by with(['product', 'client'])
    public function product()
    {
        // assumes production_orders.product_id references products.id
        return $this->belongsTo(Product::class, 'product_id');
    }

    // --- ADDED: client() relationship expected by with(['product', 'client'])
    public function client()
    {
        // assumes production_orders.client_id references users.id (or clients table)
        return $this->belongsTo(User::class, 'client_id');
        // If you have a Client model instead of User, change to:
        // return $this->belongsTo(Client::class, 'client_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'production_order_product')
            ->using(ProductQuotation::class)
            ->withPivot('quantity', 'unit_price', 'variations')
            ->withTimestamps();
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }

    // app/Models/ProductionOrder.php
    public function clientOrder()
    {
        return $this->belongsTo(Order::class, 'client_order_id');
    }

    // App/Models/ProductionOrder.php
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function stages()
    {
        return $this->hasMany(ProductionStage::class);
    }

    public function getProducts()
    {
        // 1️⃣ If a client order exists, use its products
        if ($this->clientOrder) {
            // If cart_items stored as JSON in orders table
            if (!empty($this->clientOrder->cart_items)) {
                $cartItems = json_decode($this->clientOrder->cart_items, true);

                return collect($cartItems)->map(function ($item) {
                    return (object) [
                        'name' => $item['name'] ?? 'N/A',
                        'sku' => $item['sku'] ?? 'N/A',
                        'pivot' => (object) [
                            'quantity' => $item['quantity'] ?? 0,
                            'unit_price' => $item['unit_price'] ?? 0,
                        ],
                    ];
                });
            }

            // Or if you have a relationship table like order_products
            if (method_exists($this->clientOrder, 'products')) {
                return $this->clientOrder->products->map(function ($p) {
                    return (object) [
                        'name' => $p->name,
                        'sku' => $p->sku ?? 'N/A',
                        'pivot' => $p->pivot,
                    ];
                });
            }
        }

        // 2️⃣ Otherwise, fallback to quotation products
        if ($this->quotation?->products) {
            return $this->quotation->products->map(function ($p) {
                return (object) [
                    'name' => $p->name,
                    'sku' => $p->sku ?? 'N/A',
                    'pivot' => $p->pivot,
                ];
            });
        }

        return collect();
    }
}
