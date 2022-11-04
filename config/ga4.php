<?php

return [
    /*
     * Path to the client secret json file. Take a look at the README of this package
     * to learn how to get this file. You can also pass the credentials as an array
     * instead of a file path.
     */
    'service_account_credentials_json' => storage_path(env('GA4_CREDENTIALS_JSON_PATH', null)),

    /*
     * Property id of the Google Analytics 4
     */
    'property_id' => env('GA4_PROPERTY_ID', null),
];
