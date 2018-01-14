<?php declare(strict_types=1);

use Tester\Assert,
	DhlMyApi\Package,
	DhlMyApi\Address,
	DhlMyApi\Payment,
	DhlMyApi\PdfLabel;

require __DIR__ . '/bootstrap.php';

$sender = new Address('Test Tester', 'Testovacia 1', '831 04', 'Bratislava', Address::COUNTRY_SK);
$recipient = new Address('Test Tester 2', 'Testovacia 2', '831 05', 'Bratislava', Address::COUNTRY_SK);
$payment = new Payment('DE89370400440532013000', 'TATRSKBX', '1201800001', 1.99, Payment::CURRENCY_EUR,  1000.0,  Payment::CURRENCY_EUR,  '0308');

$packages = [];
for ($i = 0; $i <= 10; $i++) {
	$package = new Package(25183385203 + $i, Package::TYPE_SK_COD, Package::DEPO_HQ_BRATISLAVA, $recipient, $sender, $payment, [Package::FLAG_SAT], 'TEST');
	$package->setPosition(2);
	$package->setCount(3);
	$packages[] = $package;
}

Assert::exception(function () use ($packages) {
	PdfLabel::generateLabels($packages, 'NONE');
}, '\Exception');

$filename = tempnam(sys_get_temp_dir(), 'Label');

$label = PdfLabel::generateLabels($packages, PdfLabel::TYPE_FULL);
file_put_contents($filename, $label);
Assert::same('application/pdf', (new finfo(FILEINFO_MIME))->file($filename, FILEINFO_MIME_TYPE));

$label = PdfLabel::generateLabels($packages, PdfLabel::TYPE_QUARTER);
file_put_contents($filename, $label);
Assert::same('application/pdf', (new finfo(FILEINFO_MIME))->file($filename, FILEINFO_MIME_TYPE));
