<?php declare(strict_types=1);

use Tester\Assert,
	DhlMyApi\Dhl;

require __DIR__ . '/bootstrap.php';

$dhl = new Dhl('user', 'pass', 12345);

Assert::true($dhl->isHealthy());
Assert::true(strlen($dhl->getVersion()) > 0);
