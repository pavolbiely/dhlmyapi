<?php declare(strict_types=1);

namespace DhlMyApi;

class Payment
{
	/* @var string */
	protected $iban;

	/* @var string */
	protected $swift;

	/* @var string */
	protected $codVarSymbol;

	/* @var float */
	protected $codPrice;

	/* @var string */
	protected $codCurrency = self::CURRENCY_EUR;

	/* @var float */
	protected $insurPrice = 0.0;

	/* @var string */
	protected $insurCurrency = self::CURRENCY_EUR;

	/* @var string */
	protected $specSymbol;

	const CURRENCIES = ['CZK','USD','EUR','BGN','DKK','EMS','GBP','HUF','CHF','LVL','PLN','RON','RUB','SEK','TRY'];

	const CURRENCY_CZK = 'CZK'; // Česká koruna
	const CURRENCY_USD = 'USD'; // Americký dolar
	const CURRENCY_EUR = 'EUR'; // Euro
	const CURRENCY_BGN = 'BGN'; // Bulharský lev
	const CURRENCY_DKK = 'DKK'; // Dánska koruna
	const CURRENCY_EMS = 'EMS';
	const CURRENCY_GBP = 'GBP'; // Libra
	const CURRENCY_HUF = 'HUF'; // Maďarský forint
	const CURRENCY_CHF = 'CHF'; // Švajčarsky frank
	const CURRENCY_LVL = 'LVL'; // Lotyšský lat
	const CURRENCY_NOK = 'NOK'; // Norská koruna
	const CURRENCY_PLN = 'PLN'; // Polský zloty
	const CURRENCY_RON = 'RON'; // Rumunské leu
	const CURRENCY_RUB = 'RUB'; // Ruský rubel
	const CURRENCY_SEK = 'SEK'; // Švédska koruna
	const CURRENCY_TRY = 'TRY'; // Nová turecká líra
	


	/**
	 * @param string
	 * @param string
	 * @param string
	 * @param float
	 * @param string
	 * @param float
	 * @param string
	 * @param string
	 */
	public function __construct(string $iban, string $swift, string $codVarSymbol, float $codPrice, string $codCurrency = self::CURRENCY_EUR, float $insurPrice = 0.0, string $insurCurrency = self::CURRENCY_EUR, string $specSymbol = NULL)
	{
		$this->setIban($iban);
		$this->setSwift($swift);
		$this->setCodVarSymbol($codVarSymbol);
		$this->setCodCurrency($codCurrency);
		$this->setCodPrice($codPrice);
		$this->setInsurPrice($insurPrice);
		$this->setInsurCurrency($insurCurrency);
		$this->setSpecSymbol($specSymbol);
	}



	/**
	 * @param string
	 * @return self
	 * @todo IBAN regex validator
	 */
	public function setIban(string $value): Payment
	{
		$this->iban = preg_replace('~([^a-zA-Z0-9]+)~', '', $value);
		return $this;
	}



	/**
	 * @return string
	 */
	public function getIban(): string
	{
		return $this->iban;
	}



	/**
	 * @param string
	 * @return self
	 * @todo SWIFT validator
	 */
	public function setSwift(string $value): Payment
	{
		$this->swift = $value;
		return $this;
	}



	/**
	 * @return string
	 */
	public function getSwift(): string
	{
		return $this->swift;
	}



	/**
	 * @param string
	 * @return self
	 * @throws \Exception
	 */
	public function setCodVarSymbol(string $value): Payment
	{
		if (preg_match('~^(\d{1,10})$~', $value)) {
			$this->codVarSymbol = $value;
		} else {
			throw new \Exception('Variable symbol must contain only numbers and be max. 10 characters long');
		}
		return $this;
	}



	/**
	 * @return string
	 */
	public function getCodVarSymbol(): string
	{
		return $this->codVarSymbol;
	}



	/**
	 * @param float
	 * @return self
	 * @throws \Exception
	 */
	public function setCodPrice(float $value): Payment
	{
		if ($this->codCurrency == self::CURRENCY_EUR && $value > 7000.0) {
			throw new \Exception('Cash on delivery price cannot exceed 7000 EUR');
		}

		$this->codPrice = max(0.0, round($value, 4));
		return $this;
	}



	/**
	 * @return float
	 */
	public function getCodPrice(): float
	{
		return $this->codPrice;
	}



	/**
	 * @param string
	 * @return self
	 * @throws \Exception
	 */
	public function setCodCurrency(string $value): Payment
	{
		if (!in_array($value, self::CURRENCIES)) {
			throw new \Exception("Currency '" . $value . "' not supported");
		}

		if ($value == self::CURRENCY_EUR && $this->codPrice > 7000.0) {
			throw new \Exception('Cash on delivery price cannot exceed 7000 EUR');
		}

		$this->codCurrency = $value;
		return $this;
	}



	/**
	 * @return string
	 */
	public function getCodCurrency(): string
	{
		return $this->codCurrency;
	}



	/**
	 * @param float
	 * @return self
	 */
	public function setInsurPrice(float $value): Payment
	{
		$this->insurPrice = max(0.0, round($value, 4));
		return $this;
	}



	/**
	 * @return float
	 */
	public function getInsurPrice(): float
	{
		return $this->insurPrice;
	}



	/**
	 * @param string
	 * @return self
	 * @throws \Exception
	 */
	public function setInsurCurrency(string $value): Payment
	{
		if (!in_array($value, self::CURRENCIES)) {
			throw new \Exception("Currency '" . $value . "' not supported");
		}

		$this->insurCurrency = $value;
		return $this;
	}



	/**
	 * @return string
	 */
	public function getInsurCurrency(): ?string
	{
		return $this->insurCurrency;
	}



	/**
	 * @param string
	 * @return self
	 * @throws \Exception
	 */
	public function setSpecSymbol(string $value = NULL): Payment
	{
		if ($value === NULL || preg_match('~^(\d{1,6})$~', $value)) {
			$this->specSymbol = $value;
		} else {
			throw new \Exception('Specific symbol must contain only numbers and be max. 6 characters long');
		}
		return $this;
	}



	/**
	 * @return string
	 */
	public function getSpecSymbol(): ?string
	{
		return $this->specSymbol;
	}
}
