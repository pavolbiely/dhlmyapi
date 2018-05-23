<?php declare(strict_types=1);

use Tester\Assert,
	DhlMyApi\Payment;

require __DIR__ . '/bootstrap.php';

$payment = new Payment('DE89 3704 0044 0532 0130 00', 'TATRSKBX', '1201800001', 1.99, Payment::CURRENCY_EUR,  1000.0,  Payment::CURRENCY_EUR,  '0308');

Assert::same('DE89370400440532013000', $payment->getIban());
Assert::same('TATRSKBX', $payment->getSwift());
Assert::same('1201800001', $payment->getCodVarSymbol());
Assert::same('1.99', $payment->getCodPrice());
Assert::same('EUR', $payment->getCodCurrency());
Assert::same('1000.00', $payment->getInsurPrice());
Assert::same('EUR', $payment->getInsurCurrency());
Assert::same('0308', $payment->getSpecSymbol());

Assert::exception(function () use ($payment) {
	$payment->setCodVarSymbol('SOME_INVALID_VARIABLE_SYMBOL');
}, '\Exception', 'Variable symbol must contain only numbers and be max. 10 characters long');

Assert::exception(function () use ($payment) {
	$payment->setCodPrice(7001.0);
}, '\Exception', 'Cash on delivery price cannot exceed 7000 EUR');

Assert::exception(function () use ($payment) {
	$payment->setSpecSymbol('SOME_INVALID_VARIABLE_SYMBOL');
}, '\Exception', 'Specific symbol must contain only numbers and be max. 6 characters long');

Assert::exception(function () use ($payment) {
	$payment->setCodCurrency('XXX');
}, '\Exception');

Assert::exception(function () use ($payment) {
	$payment->setInsurCurrency('XXX');
}, '\Exception');

$payment->setCodCurrency('CZK');
$payment->setCodPrice(7001.0);
Assert::exception(function () use ($payment) {
	$payment->setCodCurrency('EUR');
}, '\Exception');
