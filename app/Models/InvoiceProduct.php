
<?php
class InvoiceProduct extends Pivot
{
    protected $casts = [
        'variations' => 'array',
    ];
}
