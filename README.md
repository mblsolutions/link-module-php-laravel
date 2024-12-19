# link-module-php-laravel
Official Link Module interface for Laravel PHP applications.

## Contents

- [Installation](#installation)
- [Configuration](#installation)

### Installation

The recommended way to install Simfoni PHP Laravel is through [Composer](https://getcomposer.org/).

```bash
composer require mblsolutions/link-module-php-laravel
```

#### Laravel without auto-discovery

If you don't use auto-discovery, add the ServiceProvider to the providers array in config/app.php
```php
\MBLSolutions\LinkModuleLaravel\LinkModuleServiceProvider::class,
```

### Configuration

To import the default Simfoni configuration file into laravel please run the following command

```bash
php artisan vendor:publish --provider="MBLSolutions\LinkModuleLaravel\LinkModuleServiceProvider"
```

A new config file will be available in config/simfoni.php - Please ensure you update these configuration items with details provided by Redu Retail.