<?php

return [
    /*
     * Path to the client secret json file. Take a look at the README of this package
     * to learn how to get this file. You can also pass the credentials as an array
     * instead of a file path.
     */
    'service_account_credentials_json' => storage_path(env('GOOGLE_APPLICATION_CREDENTIALS', null)),

    /*
     * Property id of the Google Analytics 4
     */
    'property_id' => env('ANALYTICS_V4_PROPERTY_ID', null),

    /*
     * The amount of minutes the Google API responses will be cached.
     * If you set this to zero, the responses won't be cached at all.
     */
    'cache_lifetime_in_minutes' => 60 * 24,
];
