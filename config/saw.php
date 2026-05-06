<?php

return [
    'weights' => [
        'rating' => 0.35,
        'sentimen' => 0.15,
        'harga' => 0.50,
        'popularitas' => 0.00,
        'kebaruan' => 0.00,
    ],
    'criteria_types' => [
        'rating' => 'benefit',
        'sentimen' => 'benefit',
        'harga' => 'cost',
        'popularitas' => 'benefit',
        'kebaruan' => 'benefit',
    ],
    'recalculate_schedule' => 'daily',
    'min_reviews' => 1,
];
