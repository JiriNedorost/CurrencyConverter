# Currency Conversion Application
========================

Requirements
------------

  * PHP 7.4+
  * SQLite PHP extension enabled;
  * Apache2 or similar webserver
  * usual Laravel application requirements

Installation
------------

Execute this command to install the project:

```bash
$ git clone https://github.com/Spocklw/CurrencyConverter
$ cd CurrencyConverter
$ composer install
```
Database create and migration
-----------------------------
```bash
$ touch database/database.sqlite
$ php artisan migrate
```

Create your .env file
-----------------------------
```bash
$ cp .env.example .env
```
and then fill values according to your setup

Usage & Troubleshooting
-----

/public directory with index.php must be in path directly accessible from page served by your webserver.
 
