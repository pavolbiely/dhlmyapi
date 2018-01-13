# DHL myAPI
[![Build Status](https://travis-ci.org/pavolbiely/dhlmyapi.svg?branch=master)](https://travis-ci.org/pavolbiely/dhlmyapi)
[![Coverage Status](https://coveralls.io/repos/github/pavolbiely/dhlmyapi/badge.svg?branch=master)](https://coveralls.io/github/pavolbiely/dhlmyapi?branch=master)

Please ask DHL for username, password and Customer ID in order to access their API.

## Usage

Use composer to install this package.

### Example of usage
```php
$dhl = new Dhl('user', 'pass', 1234, __DIR__ . '/temp');
$payment = new Payment('SK4123000000000002045678', 'POBNSKBA', '123456', 100.0);
$sender = NULL;
$recipient = new Address('TEST','TEST','TEST','TEST','SK');
$package = new Package(25183385203, Package::TYPE_SK_COD, Package::DEPO_HQ_BRATISLAVA, $recipient, $sender, $payment, [Package::FLAG_SAT], 'TEST');
$dhl->createPackages([$package]);
$dhl->createPickupOrders([new PickupOrder('TEST','TEST', 1, $recipient, 'info@example.org', NULL, NULL, NULL, 'TEST')])
```

## How to run tests?
Tests are build with [Nette Tester](https://tester.nette.org/). You can run it like this:
```bash
php -f tester ./ -c php.ini-mac --coverage coverage.html --coverage-src ../src
```

## Minimum requirements
- PHP 7.1+

## License
MIT License (c) Pavol Biely

Read the provided LICENSE file for details.
