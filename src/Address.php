<?php declare(strict_types=1);

namespace DhlMyApi;

class Address
{
	/* @var string */
	protected $street;

	/* @var string */
	protected $zipCode;

	/* @var string */
	protected $city;

	/* @var string */
	protected $country;

	/* @var string */
	protected $name;

	/* @var string */
	protected $name2;

	/* @var string */
	protected $email;

	/* @var string */
	protected $phone;

	/* @var string */
	protected $contact;

	const COUNTRIES = [
		'CZ', // Česká republika
		'DE', // Nemecko
		'GB', // Anglicko
		'SK', // Slovensko
		'AT', // Rakúsko
		'PL', // Poľsko
		'CH', // Švajčiarsko
		'FI', // Fínsko
		'HU', // Maďarsko
		'SI', // Slovinsko
		'LV', // Lotyšsko
		'EE', // Estonsko
		'LT', // Litva
		'BE', // Belgicko
		'DK', // Dánsko
		'ES', // Španielsko
		'FR', // Francúzsko
		'IE', // Írsko
		'IT', // Taliansko
		'NL', // Holandsko
		'NO', // Nórsko
		'PT', // Portugalsko
		'SE', // Švédsko
		'RO', // Rumunsko
		'BG', // Bulharsko
		'GR', // Grácko
		'RU', // Rusko
		'TR', // Turecko
		'LU', // Luxembursko
		'HR', // Chorvátsko
	];

	const COUNTRY_CZ = 'CZ'; // Česká republika
	const COUNTRY_DE = 'DE'; // Nemecko
	const COUNTRY_GB = 'GB'; // Anglicko
	const COUNTRY_SK = 'SK'; // Slovensko
	const COUNTRY_AT = 'AT'; // Rakúsko
	const COUNTRY_PL = 'PL'; // Poľsko
	const COUNTRY_CH = 'CH'; // Švajčiarsko
	const COUNTRY_FI = 'FI'; // Fínsko
	const COUNTRY_HU = 'HU'; // Maďarsko
	const COUNTRY_SI = 'SI'; // Slovinsko
	const COUNTRY_LV = 'LV'; // Lotyšsko
	const COUNTRY_EE = 'EE'; // Estonsko
	const COUNTRY_LT = 'LT'; // Litva
	const COUNTRY_BE = 'BE'; // Belgicko
	const COUNTRY_DK = 'DK'; // Dánsko
	const COUNTRY_ES = 'ES'; // Španielsko
	const COUNTRY_FR = 'FR'; // Francúzsko
	const COUNTRY_IE = 'IE'; // Írsko
	const COUNTRY_IT = 'IT'; // Taliansko
	const COUNTRY_NL = 'NL'; // Holandsko
	const COUNTRY_NO = 'NO'; // Nórsko
	const COUNTRY_PT = 'PT'; // Portugalsko
	const COUNTRY_SE = 'SE'; // Švédsko
	const COUNTRY_RO = 'RO'; // Rumunsko
	const COUNTRY_BG = 'BG'; // Bulharsko
	const COUNTRY_GR = 'GR'; // Grácko
	const COUNTRY_RU = 'RU'; // Rusko
	const COUNTRY_TR = 'TR'; // Turecko
	const COUNTRY_LU = 'LU'; // Luxembursko
	const COUNTRY_HR = 'HR'; // Chorvátsko
	


	/**
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 */
	public function __construct(string $name, string $street, string $zipCode, string $city, string $country = NULL)
	{
		$this->setName($name);
		$this->setStreet($street);
		$this->setZipCode($zipCode);
		$this->setCity($city);

		if ($country !== NULL) {
			$this->setCountry($country);
		}
	}



	/**
	 * @param string
	 * @return self
	 */
	public function setStreet(string $value): Address
	{
		$this->street = mb_substr($value, 0, 50);
		return $this;
	}



	/**
	 * @return string
	 */
	public function getStreet(): string
	{
		return $this->street;
	}



	/**
	 * @param string
	 * @return self
	 */
	public function setZipCode(string $value): Address
	{
		$this->zipCode = mb_substr($value, 0, 10);
		return $this;
	}



	/**
	 * @return string
	 */
	public function getZipCode(): string
	{
		return $this->zipCode;
	}



	/**
	 * @param string
	 * @return self
	 */
	public function setCity(string $value): Address
	{
		$this->city = mb_substr($value, 0, 50);
		return $this;
	}



	/**
	 * @return string
	 */
	public function getCity(): string
	{
		return $this->city;
	}



	/**
	 * @param string
	 * @return self
	 * @throws \Exception
	 */
	public function setCountry(string $value): Address
	{
		$code = strtoupper($value);
		if (in_array($code, self::COUNTRIES)) {
			$this->country = $code;
		} else {
			throw new \Exception("Country '" . $value . "' not found");
		}
		return $this;
	}



	/**
	 * @return string
	 */
	public function getCountry(): ?string
	{
		return $this->country;
	}



	/**
	 * @param string
	 * @return self
	 */
	public function setName(string $value): Address
	{
		$this->name = mb_substr($value, 0, 50);
		return $this;
	}



	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}



	/**
	 * @param string
	 * @return self
	 */
	public function setName2(string $value): Address
	{
		$this->name2 = mb_substr($value, 0, 50);
		return $this;
	}



	/**
	 * @return string
	 */
	public function getName2(): ?string
	{
		return $this->name2;
	}


	/**
	 * @param string
	 * @return self
	 * @throws \Exception
	 */
	public function setEmail(string $value): Address
	{
		$email = mb_substr($value, 0, 50);
		if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$this->email = $email;
		} else {
			throw new \Exception("E-mail '" . $email . "' has invalid format");
		}
		return $this;
	}



	/**
	 * @return string
	 */
	public function getEmail(): ?string
	{
		return $this->email;
	}


	/**
	 * @param string
	 * @return self
	 */
	public function setPhone(string $value): Address
	{
		$this->phone = mb_substr($value, 0, 30);
		return $this;
	}



	/**
	 * @return string
	 */
	public function getPhone(): ?string
	{
		return $this->phone;
	}



	/**
	 * @param string
	 * @return self
	 */
	public function setContact(string $value): Address
	{
		$this->contact = mb_substr($value, 0, 300);
		return $this;
	}



	/**
	 * @return string
	 */
	public function getContact(): ?string
	{
		return $this->contact;
	}
}
