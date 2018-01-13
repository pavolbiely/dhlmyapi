<?php declare(strict_types=1);

use Tester\Assert,
	DhlMyApi\PickupOrder,
	DhlMyApi\Address;

require __DIR__ . '/bootstrap.php';

$sender = new Address('Test Tester', 'Testovacia 1', '831 04', 'Bratislava', Address::COUNTRY_SK);

$order = new PickupOrder('1201800001',  '999', 5, $sender, 'info@example.org', new \DateTime('2018-01-02'), new \DateTime('2018-01-02 15:00'), new \DateTime('2018-01-02 18:00'), 'Testing');

Assert::same('1201800001', $order->getOrderReferenceId());
Assert::same('999', $order->getCustomerRefs());
Assert::same(5, $order->getCountPackages());
Assert::same('Test Tester', $order->getSender()->getName());
Assert::same('info@example.org', $order->getEmail());
Assert::same('2018-01-02', $order->getSendDate()->format('Y-m-d'));
Assert::same('2018-01-02 15:00', $order->getSendTimeFrom()->format('Y-m-d H:i'));
Assert::same('2018-01-02 18:00', $order->getSendTimeTo()->format('Y-m-d H:i'));
Assert::same('Testing', $order->getNote());

Assert::exception(function () use ($order) {
	$order->setEmail('SOME_INVALID_EMAIL_STRING');
}, '\Exception');
