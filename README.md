# NexaMerchant/Apis

[![Build Status](https://github.com/NexaMerchant/apis/workflows/Laravel/badge.svg)](https://github.com/NexaMerchant/apis)
[![Release](https://img.shields.io/github/release/NexaMerchant/apis.svg?style=flat-square)](https://github.com/NexaMerchant/apis/releases)

# Requirements
 - PHP >= 8.1
 - Laravel >= 10
 - Sodium

# How to Install

```
NexaMerchant\Apis\Providers\ApisServiceProvider::class,
```
Add it to config/app.php $providers

# How to Publish

```
composer require nexa-merchant/apis
```

# How to Install
```
php artisan apis:install
```

# How to Generate API Docs
```
php artisan Apis:gendocs
```

# View Api Document
```
http://localhost/api/admin/documentation (Admin)
```
```
http://localhost/api/shop/documentation (Shop)
```

Bug Report
------------
[https://github.com/NexaMerchant/apis/issues](https://github.com/NexaMerchant/apis/issues)

# License
------------
`NexaMerchant Apis` is licensed under [The MIT License (MIT)](LICENSE).
