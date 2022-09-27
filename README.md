# Laravel Magento Customer Prices

This package provides a way to add customer specific prices to Magento from a Laravel app.
By default, it uses the [Magaplaza Better Tier Price](https://www.mageplaza.com/magento-2-better-tier-price/) module for customer specific prices.
You can implement another customer specific price module, see [Replacing Mageplaza](#replacing-mageplaza).

## Features
This package can:

- Retrieve prices from any source
- Push customer specific prices to Magento
- Only update prices in Magento when are modified. i.e. when you retrieve the same price ten times it only updates once to Magento
- Automatically stop syncing when updating fails
- Logs activities using [Spatie activitylog](https://github.com/spatie/laravel-activitylog)
- Logs errors using [JustBetter Error Logger](https://github.com/justbetter/laravel-error-logger)
- Checks if Magento products exist using [JustBetter Magento Products](https://bitbucket.org/just-better/laravel-magento-products)

## Installation

Require this package: `composer require justbetter/laravel-magento-customer-prices`

Publish the config
```
php artisan vendor:publish --provider="JustBetter\MagentoCustomerPrices\ServiceProvider" --tag="config"
```

Publish the activity log's migrations:
```
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-migrations"
```

Run the migrations
```
php artisan migrate
```

## Usage

Add the following commands to your scheduler:
```php
<?php

    protected function schedule(Schedule $schedule): void
    {
        $schedule->command(\JustBetter\MagentoCustomerPrices\Commands\SyncCustomerPricesCommand::class)->everyMinute();

        // Retrieve all customer prices weekly
        $schedule->command(\JustBetter\MagentoCustomerPrices\Commands\RetrieveAllCustomerPricesCommand::class)->weekly();

        // Retrieve updated customer prices daily
        $schedule->command(\JustBetter\MagentoCustomerPrices\Commands\RetrieveUpdatedCustomerPricesCommand::class)->daily();
    }

```

### Retrieving Customer Prices

To retrieve prices you have to write a retriever.
A retriever is a class that extends the `\JustBetter\MagentoCustomerPrices\Retriever\CustomerPriceRetriever` class.

You'll be required to write three methods:
#### retrieve(string $sku)

Must return an enumerable of `\JustBetter\MagentoCustomerPrices\Data\CustomerPriceData` objects

#### retrieveAllSkus()

Must return an enumerable of strings

#### retrieveUpdatedSkus()

Must return an enumerable of strings

#### Example
See the `\JustBetter\MagentoCustomerPrices\Retriever\DummyCustomerPriceRetriever` class for an example.

## Replacing Mageplaza

By default this package uses the [Magaplaza Better Tier Price](https://www.mageplaza.com/magento-2-better-tier-price/) module for updating prices to Magento.
You can use another package by creating a class that implements `JustBetter\MagentoCustomerPrices\Contracts\UpdatesMagentoCustomerPrices`.
See `\JustBetter\MagentoCustomerPrices\Actions\UpdateMageplazaCustomerPrices` for an example.

Don't forget to bind your own class!
```
app()->singleton(UpdatesMagentoCustomerPrices::class, YourCustomUpdater::class);
```

## Quality

To ensure the quality of this package, run the following command:

```shell
composer quality
```

This will execute three tasks:

1. Makes sure all tests are passed
2. Checks for any issues using static code analysis
3. Checks if the code is correctly formatted

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Vincent Boon](https://github.com/VincentBean)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
