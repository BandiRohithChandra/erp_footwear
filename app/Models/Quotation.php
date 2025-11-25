<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProductQuotation;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Quotation extends Model
{
    // 🔹 Define status constants to avoid typos
    const STATUS_PENDING = 'pending';
    const STATUS_SENT = 'sent';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_REJECTED = 'rejected';
    const STATUS_EXPIRED = 'expired';

    protected $fillable = [
    'client_id',
    'brand_name',   // FIXED
    'warehouse_id',
    'salesperson_id',
    'status',
    'subtotal',
    'tax',
    'grand_total',
    'tax_type',
    'notes',
    'quotation_no',
];



    protected $casts = [
        'status' => 'string',
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'variations' => 'array',
        'grand_total' => 'decimal:2',
    ];

    /**
     * Boot method to auto-generate quotation number
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($quotation) {
            if (empty($quotation->quotation_no)) {
                $quotation->quotation_no = static::generateQuotationNumber();
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function salesperson(): BelongsTo
    {
        return $this->belongsTo(User::class, 'salesperson_id');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_quotation')
            ->using(ProductQuotation::class)
            ->withPivot(['quantity', 'unit_price', 'variations'])
            ->withTimestamps();
    }



    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'quotation_id');
    }



    public function orders(): HasMany
    {
        return $this->hasMany(ProductionOrder::class, 'quotation_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Quotation Number Generation
    |--------------------------------------------------------------------------
    */
    public static function generateQuotationNumber(): string
    {
        $datePrefix = now()->format('Ymd');
        $latest = static::where('quotation_no', 'like', "QTN-{$datePrefix}-%")
            ->orderBy('id', 'desc')
            ->first();

        $sequence = $latest ? (int) substr($latest->quotation_no, -6) + 1 : 1;

        return "QTN-{$datePrefix}-" . str_pad($sequence, 6, '0', STR_PAD_LEFT);
    }

    public static function getNextQuotationNumber(): string
    {
        return static::generateQuotationNumber();
    }

    public function scopeByQuotationNo($query, $quotationNo): static
    {
        return $query->where('quotation_no', 'like', "%{$quotationNo}%");
    }

    /*
    |--------------------------------------------------------------------------
    | Status Helpers
    |--------------------------------------------------------------------------
    */
    public function markAsSent(): self
    {
        $this->update(['status' => self::STATUS_SENT]);
        return $this;
    }

    public function markAsAccepted(): self
    {
        $this->update(['status' => self::STATUS_ACCEPTED]);
        return $this;
    }

    public function markAsRejected(): self
    {
        $this->update(['status' => self::STATUS_REJECTED]);
        return $this;
    }

    public function markAsExpired(): self
    {
        $this->update(['status' => self::STATUS_EXPIRED]);
        return $this;
    }

    public function getStatusBadgeClasses(): array
    {
        return match ($this->status) {
            self::STATUS_PENDING => ['bg-yellow-100', 'text-yellow-800', 'border-yellow-200'],
            self::STATUS_SENT => ['bg-blue-100', 'text-blue-800', 'border-blue-200'],
            self::STATUS_ACCEPTED => ['bg-green-100', 'text-green-800', 'border-green-200'],
            self::STATUS_REJECTED => ['bg-red-100', 'text-red-800', 'border-red-200'],
            self::STATUS_EXPIRED => ['bg-gray-100', 'text-gray-800', 'border-gray-200'],
            default => ['bg-gray-100', 'text-gray-800', 'border-gray-200'],
        };
    }

    public function getStatusDisplay(): array
    {
        return match ($this->status) {
            self::STATUS_PENDING => ['text' => 'Pending', 'icon' => '⏳'],
            self::STATUS_SENT => ['text' => 'Sent', 'icon' => '📧'],
            self::STATUS_ACCEPTED => ['text' => 'Accepted', 'icon' => '✅'],
            self::STATUS_REJECTED => ['text' => 'Rejected', 'icon' => '❌'],
            self::STATUS_EXPIRED => ['text' => 'Expired', 'icon' => '🕐'],
            default => ['text' => ucfirst($this->status), 'icon' => '⚪'],
        };
    }

    /*
    |--------------------------------------------------------------------------
    | Boolean Checkers
    |--------------------------------------------------------------------------
    */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isSent(): bool
    {
        return $this->status === self::STATUS_SENT;
    }

    public function isAccepted(): bool
    {
        return $this->status === self::STATUS_ACCEPTED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function isExpired(): bool
    {
        return $this->status === self::STATUS_EXPIRED;
    }

    public function isActionable(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_SENT], true);
    }

    public function canConvertToOrder(): bool
    {
        return $this->isAccepted();
    }

    /*
    |--------------------------------------------------------------------------
    | Generic Status Updater
    |--------------------------------------------------------------------------
    */
    public function updateStatus(string $status): self
    {
        $validStatuses = [
            self::STATUS_PENDING,
            self::STATUS_SENT,
            self::STATUS_ACCEPTED,
            self::STATUS_REJECTED,
            self::STATUS_EXPIRED,
        ];

        if (!in_array($status, $validStatuses, true)) {
            throw new \InvalidArgumentException("Invalid status: {$status}");
        }

        $this->update(['status' => $status]);
        return $this;
    }

    public function getAvailableActions(): array
    {
        $actions = [];

        if ($this->isPending()) {
            $actions = [
                'send' => ['label' => 'Send Quotation', 'icon' => '📧'],
                'accept' => ['label' => 'Mark as Accepted', 'icon' => '✅'],
                'reject' => ['label' => 'Mark as Rejected', 'icon' => '❌'],
                'expire' => ['label' => 'Mark as Expired', 'icon' => '🕐'],
            ];
        } elseif ($this->isSent()) {
            $actions = [
                'accept' => ['label' => 'Mark as Accepted', 'icon' => '✅'],
                'reject' => ['label' => 'Mark as Rejected', 'icon' => '❌'],
                'expire' => ['label' => 'Mark as Expired', 'icon' => '🕐'],
            ];
        } elseif ($this->isAccepted()) {
            $actions = [
                'convert_to_order' => ['label' => 'Convert to Order', 'icon' => '📋'],
            ];
        }

        return $actions;
    }

    /**
     * Get total quantity across all products
     */
    public function getTotalQuantityAttribute(): int
    {
        return $this->products->sum('pivot.quantity');
    }

    /**
     * Get formatted quotation number for display
     */
    public function getFormattedQuotationNoAttribute(): string
    {
        return $this->quotation_no ?? 'QTN-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Get formatted creation date
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->created_at->format('d M Y');
    }

    /**
     * Get formatted amounts with currency symbol
     */
    public function getFormattedSubtotalAttribute(): string
    {
        return '₹' . number_format($this->subtotal, 2);
    }

    public function getFormattedTaxAttribute(): string
    {
        return '₹' . number_format($this->tax, 2);
    }

    public function getFormattedGrandTotalAttribute(): string
    {
        return '₹' . number_format($this->grand_total, 2);
    }

    /**
     * Scope for quotations that can be converted to orders
     */
    public function scopeConvertibleToOrders($query): static
    {
        return $query->where('status', self::STATUS_ACCEPTED)
            ->whereDoesntHave('orders');
    }

    /**
     * Check if quotation has been converted to order
     */
    public function hasBeenConverted(): bool
    {
        return $this->orders()->exists();
    }

    /**
     * Get the first production order if exists
     */
    public function getProductionOrder(): ?ProductionOrder
    {
        return $this->orders()->first();
    }

    /**
     * Get tax rate based on tax type
     */
    public function getTaxRate(): float
    {
        return match ($this->tax_type ?? 'cgst') {
            'cgst' => 0.18, // 9% CGST + 9% SGST
            'igst' => 0.18, // 18% IGST
            default => 0.18,
        };
    }

    /**
     * Calculate tax amount
     */
    public function calculateTax(): float
    {
        return $this->subtotal * $this->getTaxRate();
    }

    /**
     * Validate if totals are consistent
     */
    public function isTotalsValid(): bool
    {
        $calculatedTax = $this->calculateTax();
        $calculatedTotal = $this->subtotal + $calculatedTax;

        return abs($this->tax - $calculatedTax) < 0.01 &&
            abs($this->grand_total - $calculatedTotal) < 0.01;
    }

    /**
     * Get article number for a product (uses SKU from products table)
     */
    public function getArticleNoForProduct($productId): ?string
    {
        $product = $this->products->where('id', $productId)->first();
        return $product?->sku;
    }
}