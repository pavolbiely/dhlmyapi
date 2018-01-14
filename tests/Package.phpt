<?php declare(strict_types=1);

use Tester\Assert,
	DhlMyApi\Package,
	DhlMyApi\Address,
	DhlMyApi\Payment;

require __DIR__ . '/bootstrap.php';

$sender = new Address('Test Tester', 'Testovacia 1', '831 04', 'Bratislava', Address::COUNTRY_SK);
$recipient = new Address('Test Tester 2', 'Testovacia 2', '831 05', 'Bratislava', Address::COUNTRY_SK);
$payment = new Payment('DE89370400440532013000', 'TATRSKBX', '1201800001', 1.99, Payment::CURRENCY_EUR,  1000.0,  Payment::CURRENCY_EUR,  '0308');

$package = new Package(25183385203, Package::TYPE_SK_COD, Package::DEPO_HQ_BRATISLAVA, $recipient, $sender, $payment, [Package::FLAG_SAT], 'TEST');
$package->setPosition(2);
$package->setCount(3);

Assert::same(25183385203, $package->getNumber());
Assert::same(102, $package->getType());
Assert::same(50, $package->getDepo());
Assert::same('Test Tester', $package->getSender()->getName());
Assert::same('Test Tester 2', $package->getRecipient()->getName());
Assert::same('DE89370400440532013000', $package->getPayment()->getIban());
Assert::same(['SAT'], $package->getFlags());
Assert::same('TEST', $package->getNote());
Assert::same(2, $package->getPosition());
Assert::same(3, $package->getCount());

Assert::exception(function () use ($package) {
	$package->setType(999);
}, '\Exception', "Type '999' not found");

Assert::exception(function () use ($package) {
	$package->setDepo(999);
}, '\Exception', "Depo '999' not found");

Assert::exception(function () use ($package) {
	$package->setFlags(['XXX']);
}, '\Exception', "Unknown flag 'XXX'");

foreach ([Package::TYPE_SK, Package::TYPE_INTERNATIONAL, Package::TYPE_FORYOU_SK, Package::TYPE_IMPORT] as $type) {
	Assert::exception(function () use ($type, $recipient, $sender, $payment) {
		new Package(25183385203, $type, Package::DEPO_HQ_BRATISLAVA, $recipient, $sender, $payment, [Package::FLAG_SAT], 'TEST');
	}, '\Exception', 'Payment is available only for cash on delivery packages');
}
