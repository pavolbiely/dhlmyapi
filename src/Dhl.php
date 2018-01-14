<?php declare(strict_types=1);

namespace DhlMyApi;

/**
 * DHL myApi v 1.0.0.5
 *
 */
class Dhl
{
	/* @var \SoapClient */
	protected $soap;

	/* @var string */
	protected $username;

	/* @var string */
	protected $password;

	/* @var int */
	protected $customerId;

	/* @var string */
	protected $tempDir;



	/**
	 * @param string
	 * @param string
	 * @param int
	 * @param string
	 * @throws \DhlMyApi\DhlMyApiException
	 */
	public function __construct(string $username, string $password, int $customerId, string $tempDir = NULL)
	{
		$this->username = $username;
		$this->password = $password;
		$this->customerId = $customerId;

		if ($tempDir === NULL) {
			$this->tempDir = sys_get_temp_dir();
		} else {
			$this->tempDir = rtrim($tempDir, '/');
		}

		try {
			$this->soap = new \SoapClient('https://myapi.dhlparcel.sk/MyAPI.svc?wsdl', [
				'trace' => 1,
				'exception' => 1,
			]);

		} catch (\Exception $e) {
			throw new DhlMyApiException('Unable to connect to DHL myAPI');
		}

		if (!$this->isHealthy()) {
			throw new DhlMyApiException('DHL myAPI is offline');
		}
	}



	/**
	 * @return bool
	 */
	public function isHealthy(): bool
	{
		try {
			$result = $this->soap->isHealtly();
			return isset($result->IsHealtlyResult) && $result->IsHealtlyResult = 'Healthy';

		} catch (\Exception $e) {
			return false;
		}
	}



	/**
	 * @return string
	 * @throws \DhlMyApi\DhlMyApiException
	 */
	protected function login(): string
	{
		try {
			$result = $this->soap->Login([
				'Auth' => [
					'CustId' => $this->customerId,
					'UserName' => $this->username,
					'Password' => $this->password,
				],
			]);
			return $result->LoginResult->AuthToken;

		} catch (\Exception $e) {
			throw new DhlMyApiException('Login failed', 0, $e);
		}
	}



	/**
	 * @return string
	 */
	protected function getAuthToken(): string
	{
		$path = $this->tempDir . '/' . md5(implode('|', [$this->customerId, $this->username, $this->password]));
		if (is_file($path) && strtotime('-30 minutes') <= filemtime($path)) {
			return file_get_contents($path);
		}

		$token = $this->login();
		file_put_contents($path, $token);
		return $token;
	}



	/**
	 * @return string
	 */
	public function getVersion(): string
	{
		try {
			$result = $this->soap->Version();
			return $result->VersionResult;

		} catch (\Exception $e) {
			throw new DhlMyApiException(NULL, 0, $e);
		}
	}



	/**
	 * @param \DhlMyApi\PickupOrder[]
	 * @return \stdClass|NULL
	 * @throws \DhlMyApi\DhlMyApiException
	 */
	public function createPickupOrders(array $orders): ?\stdClass
	{
		try {
			if (count($orders) == 0) {
				throw new \Exception('No pickup orders provided');
			}

			$myApiPickUpOrderIn = [];
			foreach ($orders as $order) {
				if (!$order instanceof PickupOrder) {
					throw new \Exception('Pickup orders must be instance of \DhlMyApi\PickupOrder class');
				}

				$sendDate = $order->getSendDate();
				$sendTimeFrom = $order->getSendTimeFrom();
				$sendTimeTo = $order->getSendTimeTo();
				$sender = $order->getSender();

				$myApiPickUpOrderIn[] = [
					'OrdRefId' => $order->getOrderReferenceId(),
					'CustRef' => $order->getCustomerRefs(),
					'CountPack' => $order->getCountPackages(),
					'Note' => $order->getNote(),
					'Email' => $order->getEmail(),
					'SendDate' => $sendDate ? $sendDate->format('Y-m-d') : NULL,
					'SendTimeFrom' => $sendTimeFrom ? $sendTimeFrom->format(\DateTime::ATOM) : NULL,
					'SendTimeTo' => $sendTimeTo ? $sendTimeTo->format(\DateTime::ATOM) : NULL,
					'Sender' => [
						'City' => $sender->getCity(),
						'Contact' => $sender->getContact(),
						'Country' => $sender->getCountry(),
						'Email' => $sender->getEmail(),
						'Name' => $sender->getName(),
						'Name2' => $sender->getName2(),
						'Phone' => $sender->getPhone(),
						'Street' => $sender->getStreet(),
						'ZipCode' => $sender->getZipCode()
					],
				];
			}

			$data = [
				'Auth' => [
					'AuthToken' => $this->getAuthToken(),
				],
				'Orders' => [
					'myApiPickUpOrderIn' => $myApiPickUpOrderIn,
				],
			];
			return $this->soap->CreatePickupOrders($data);

		} catch (\Exception $e) {
			throw new DhlMyApiException('Could not create pickup order', 0, $e);
		}
	}



	/**
	 * @param \DhlMyPackage\Package
	 * @param string
	 * @return \stdClass|NULL
	 */
	public function createPackages(array $packages, string $customerUniqueImportId = NULL)
	{
		try {
			if (count($packages) == 0) {
				throw new \Exception('No packages provided');
			}

			$myApiPackageIn = [];
			foreach ($packages as $package) {
				if (!$package instanceof Package) {
					throw new \Exception('Packages must be instance of \DhlMyApi\Package class');
				}

				$sender = $package->getSender();
				$recipient = $package->getRecipient();
				$payment = $package->getPayment();

				$flags = [];
				foreach ($package->getFlags() as $flag) {
					$flags[] = [
						'Code' => $flag,
						'Value' => true,
					];
				}
				$flags = ['MyApiFlag' => $flags];

				$myApiPackageIn[] = [
					'PackNumber' => $package->getNumber(),
					'PackProductType' => $package->getType(),
					'Note' => $package->getNote(),
					'DepoCode' => $package->getDepo(),
					'Sender' => $sender ? [
						'City' => $sender->getCity(),
						'Contact' => $sender->getContact(),
						'Country' => $sender->getCountry(),
						'Email' => $sender->getEmail(),
						'Name' => $sender->getName(),
						'Name2' => $sender->getName2(),
						'Phone' => $sender->getPhone(),
						'Street' => $sender->getStreet(),
						'ZipCode' => $sender->getZipCode(),
					] : NULL,
					'Recipient' => [
						'City' => $recipient->getCity(),
						'Contact' => $recipient->getContact(),
						'Country' => $recipient->getCountry(),
						'Email' => $recipient->getEmail(),
						'Name' => $recipient->getName(),
						'Name2' => $recipient->getName2(),
						'Phone' => $recipient->getPhone(),
						'Street' => $recipient->getStreet(),
						'ZipCode' => $recipient->getZipCode(),
					],
					'PaymentInfo' => $payment ? [
						'CodCurrency' => $payment->getCodCurrency(),
						'CodPrice' => $payment->getCodPrice(),
						'CodVarSym' => $payment->getCodVarSymbol(),
						'IBAN' => $payment->getIban(),
						'InsurCurrency' => $payment->getInsurCurrency(),
						'InsurPrice' => $payment->getInsurPrice(),
						'SpecSymbol' => $payment->getSpecSymbol(),
						'Swift' => $payment->getSwift(),
					] : NULL,
					'Flags' => $flags,
				];
			}

			$data = [
				'Auth' => [
					'AuthToken' => $this->getAuthToken(),
				],
				'CustomerUniqueImportId' => $customerUniqueImportId,
				'Packages' => [
					'MyApiPackageIn' => $myApiPackageIn,
				],
			];
			$result = $this->soap->CreatePackages($data);

			return isset($result->CreatePackagesResult->ResultData->ItemResult) ? $result->CreatePackagesResult->ResultData->ItemResult : NULL;

		} catch (\Exception $e) {
			throw new DhlMyApiException('Could not create packages', 0, $e);
		}
	}



	/**
	 * @param string
	 * @param \DateTime
	 * @param \DateTime
	 * @param array
	 * @throws \DhlMyApi\DhlMyApiException
	 * @return mixed
	 */
	public function getPackages(string $customerRefs = NULL, \DateTime $dateFrom = NULL, \DateTime $dateTo = NULL, array $packageNumbers = [])
	{
		if ($customerRefs === NULL && $dateFrom === NULL && $dateTo === NULL && count($packageNumbers) == 0) {
			throw new DhlMyApiException('At least one parameter must be specified!');
		}

		$data = [
			'Auth' => [
				'AuthToken' => $this->getAuthToken(),
			],
			'Filter' => [
				'CustRefs' => $customerRefs,
				'DateFrom' => ($dateFrom ? $dateFrom->format('Y-m-d') : NULL),
				'DateTo' => ($dateTo ? $dateTo->format('Y-m-d') : NULL),
				'PackNumbers' => $packageNumbers
			]
		];
		$result = $this->soap->GetPackages($data);

		return isset($result->GetPackagesResult->ResultData->MyApiPackageOut) ? $result->GetPackagesResult->ResultData->MyApiPackageOut : [];
	}



	/**
	 * @param string
	 * @param string
	 * @return object|NULL
	 * @throws \Exception
	 */
	public function getParcelShops(string $code = NULL, string $countryCode = Address::COUNTRY_SK)
	{
		if ($countryCode && !in_array($countryCode, Address::COUNTRIES)) {
			throw new \Exception("Country '" . $countryCode . "' not found");
		}

		$result = $this->soap->GetParcelShops([
			'Filter' => [
				'Code' => $code,
				'CountryCode' => $countryCode,
			]
		]);

		return $result->GetParcelShopsResult->ResultData->MyApiParcelShop ?? NULL;
	}
}



class DhlMyApiException extends \Exception
{
}
