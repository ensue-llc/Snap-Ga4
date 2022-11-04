# Google analytics 4: Data API v1

This package intends to provide analytics methods for Data API v1 [Beta Release].

## Getting Started

### Install
Run the following command:
```
composer require ensue/ga4
```

### Publish config and lang (optional)
```
php artisan vendor:publish --tag=ga4
```

### Dependencies
Follow the step 1 and 2 provided in
[Google Analytics Data API (GA4) QuickStart](https://developers.google.com/analytics/devguides/reporting/data/v1/quickstart-client-libraries).
Once you download the service account credentials, store it in **storage** directory.

### ENV changes
Add this parameters in your env file
```
GA4_PROPERTY_ID=<YOUR-PROPERTY-ID>
GA4_CREDENTIALS_JSON_PATH=<FILE-STORED-IN-STORAGE-DIR>
```
