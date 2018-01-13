<?php declare(strict_types=1);

use Tester\Assert,
	DhlMyApi\Address;

require __DIR__ . '/bootstrap.php';

$address = new Address('Test Tester', 'Testovacia 1', '831 04', 'Bratislava', Address::COUNTRY_SK);
$address->setName2('Test2');
$address->setContact('Test Contact');
$address->setPhone('+421949949949');
$address->setEmail('info@example.org');

Assert::same('Test Tester', $address->getName());
Assert::same('Test2', $address->getName2());
Assert::same('Testovacia 1', $address->getStreet());
Assert::same('Bratislava', $address->getCity());
Assert::same('831 04', $address->getZipCode());
Assert::same('SK', $address->getCountry());
Assert::same('Test Contact', $address->getContact());
Assert::same('+421949949949', $address->getPhone());
Assert::same('info@example.org', $address->getEmail());

Assert::exception(function () use ($address) {
	$address->setEmail('SOME_INVALID_EMAIL_STRING');
}, '\Exception');

Assert::exception(function () use ($address) {
	$address->setCountry('XX');
}, '\Exception');
