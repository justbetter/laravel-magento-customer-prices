# Upgrade Guide

## Upgrading from 1.x to 2.x

You do not need to modify code in your price/sku retriever.

If you were using the Mageplaza Better Tier Price module you now have to implement your update class yourself or upgrade
to the [JustBetter Magento 2 Customer Pricing](https://github.com/justbetter/magento2-customer-pricing) module.
See the readme on how you can implement your own updater class, you can use the default class from version 1.x as a
template.

## Upgrading from 2.x to 3.x

3.x introduces a complete refactor of the package structure.

A few highlights:

- Simplified implementation
- Support updating via Magento 2 bulk async requests
- Removed error logger, replaced with activity log
- Dropped support for Laravel 10

### Update your project

The price retriever and SKU retriever classes all have been merged into a single repository class.
Refer to the readme on how to implement this.

The configuration file has been stripped, most of the configuration is now done in the repository class.

A lot of classes have been renamed, be sure to update your scheduler and check all classes that you use.
The price model has been renamed from `MagentoCustomerPrice` to `CustomerPrice`.
