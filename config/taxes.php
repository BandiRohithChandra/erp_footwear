<?php

return [
    'default_region' => 'in',
    'regions' => [
        'in' => [
            'tax_rate' => 0.18, // 18% GST for India
            'currency' => 'INR',
        ],
        'us' => [
            'tax_rate' => 0.08, // Example 8% sales tax for US
            'currency' => 'USD',
        ],
    ],
];