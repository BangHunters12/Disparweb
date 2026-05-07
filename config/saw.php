<?php

return [
    /*
    |--------------------------------------------------------------------------
    | SAW Default Weights (stored in DB via saw_config table)
    |--------------------------------------------------------------------------
    | These are fallback defaults when no DB config exists yet.
    | Total must equal 100.
    */
    'default_weights' => [
        'rating'      => 40.00,
        'sentimen'    => 25.00,
        'harga'       => 15.00,
        'popularitas' => 10.00,
        'kebaruan'    => 10.00,
    ],

    /*
    |--------------------------------------------------------------------------
    | Criteria Types
    |--------------------------------------------------------------------------
    | benefit = higher is better (normalize: Xij / max)
    | cost    = lower is better (normalize: min / Xij)
    */
    'criteria_types' => [
        'rating'      => 'benefit',
        'sentimen'    => 'benefit',
        'harga'       => 'cost',
        'popularitas' => 'benefit',
        'kebaruan'    => 'benefit',
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Values for New Restaurants
    |--------------------------------------------------------------------------
    */
    'default_rating' => 3.0,
    'default_price'  => 50000,

    /*
    |--------------------------------------------------------------------------
    | Kebaruan (Recency) Settings
    |--------------------------------------------------------------------------
    */
    'recency_days' => 365, // max recency window in days
];
