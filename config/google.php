<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Google Maps / Places API Keys
    |--------------------------------------------------------------------------
    |
    | GOOGLE_PLACES_KEY  → Backend PHP only (IP restricted in GCP Console)
    | GOOGLE_MAPS_KEY    → Blade views embed (HTTP Referrer restricted)
    | GOOGLE_MAPS_FLUTTER_KEY → Flutter mobile app (App restricted)
    |
    */

    'places_key'        => env('GOOGLE_PLACES_KEY', ''),
    'maps_key'          => env('GOOGLE_MAPS_KEY', ''),
    'flutter_maps_key'  => env('GOOGLE_MAPS_FLUTTER_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Default Search Parameters
    |--------------------------------------------------------------------------
    */
    'default_location'  => [
        'lat'    => -7.9117,
        'lng'    => 113.8231,
        'radius' => 15000, // meters
    ],

    'search_types'      => 'restaurant|food|cafe',
    'search_language'   => 'id',

    /*
    |--------------------------------------------------------------------------
    | Photo Settings
    |--------------------------------------------------------------------------
    */
    'max_photos'        => 5,
    'photo_max_width'   => 800,
    'max_reviews'       => 5,

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    */
    'places_rate_limit' => 30, // requests per minute
];
