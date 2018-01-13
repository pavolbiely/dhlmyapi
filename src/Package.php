<?php declare(strict_types=1);

namespace DhlMyApi;

class Package
{
	/* @var int */
	protected $number;

	/* @var int */
	protected $type;

	/* @var int */
	protected $depo;

	/* @var \DhlMyApi\Address */
	protected $recipient;

	/* @var \DhlMyApi\Address */
	protected $sender;

	/* @var \DhlMyApi\Payment */
	protected $payment;

	/** @var array */
	protected $flags = [];

	/* @var string */
	protected $note;

	const DEPOS = [50,51,52,53,54,58,59];

	const DEPO_HQ_BRATISLAVA = 50; // HQ Bratislava
	const DEPO_BRATISLAVA = 51; // Bratislava
	const DEPO_ZILINA = 52; // Žilina
	const DEPO_BANSKA_BYSTRICA = 53; // Banská Bystrica
	const DEPO_KOSICE = 54; // Košice
	const DEPO_HUB_BRATISLAVA = 59; // HUB Bratislava
	const DEPO_HUB_ZILINA = 58; // HUB Žilina

	const TYPES = [101,102,103,104,105,106,107,108,109,110];
	const TYPES_COD = [102,104,106,108,110];

	const TYPE_SK = 101; // DHL Parcel Slovensko
	const TYPE_SK_COD = 102; // DHL Parcel Slovensko - dobierka
	const TYPE_INTERNATIONAL = 103; // DHL Parcel International
	const TYPE_INTERNATIOANL_COD = 104; // DHL Parcel International - dobierka
	const TYPE_FORYOU_SK = 105; // DHL Parcel For You Slovensko
	const TYPE_FORYOU_SK_COD = 106; // DHL Parcel For You Slovensko - dobierka
	const TYPE_FORYOU_INTERNATIONAL = 107; // DHL Parcel For You International
	const TYPE_FORYOU_INTERNATIONAL_COD = 108; // DHL Parcel For You International - dobierka
	const TYPE_IMPORT = 109; // DHL Parcel Import
	const TYPE_IMPORT_COD = 110; // DHL Parcel Import - dobierka

	const FLAG_SAT = 'SAT';
	const FLAGS = [self::FLAG_SAT];



	/**
	 * @param int
	 * @param int
	 * @param int
	 * @param \DhlMyApi\Address
	 * @param \DhlMyApi\Address
	 * @param \DhlMyApi\Payment
	 * @param array
	 * @param string
	 */
	public function __construct(int $number, $type, $depo, Address $recipient, Address $sender = NULL, Payment $payment = NULL, array $flags = [], string $note = NULL)
	{
		$this->setNumber($number);
		$this->setType($type);
		$this->setDepo($depo);
		$this->setRecipient($recipient);
		$this->setSender($sender);
		$this->setPayment($payment);
		$this->setFlags($flags);
		$this->setNote($note);
	}



	/**
	 * @param int
	 * @return \DhlMyApi\Package
	 */
	public function setNumber(int $number): Package
	{
		$this->number = $number;
		return $this;
	}



	/**
	 * @return int
	 */
	public function getNumber(): int
	{
		return $this->number;
	}



	/**
	 * @param int
	 * @return \DhlMyApi\Package
	 * @throws \Exception
	 */
	public function setType(int $type): Package
	{
		if (!in_array($type, self::TYPES)) {
			throw new \Exception("Type '" . $type . "' not found");
		}

		$this->type = $type;
		return $this;
	}



	/**
	 * @return int
	 */
	public function getType(): int
	{
		return $this->type;
	}



	/**
	 * @return bool
	 */
	public function isCashOnDelivery(): bool
	{
		return in_array($this->type, [self::TYPE_SK_COD, self::TYPE_INTERNATIOANL_COD, self::TYPE_FORYOU_SK_COD, self::TYPE_FORYOU_INTERNATIONAL_COD, self::TYPE_IMPORT_COD]);
	}



	/**
	 * @param int
	 * @return \DhlMyApi\Package
	 * @throws \Exception
	 */
	public function setDepo(int $depo): Package
	{
		if (!in_array($depo, self::DEPOS)) {
			throw new \Exception("Depo '" . $depo . "' not found");
		}

		$this->depo = $depo;
		return $this;
	}



	/**
	 * @return int
	 */
	public function getDepo(): int
	{
		return $this->depo;
	}



	/**
	 * @param \DhlMyApi\Address
	 * @return self
	 */
	public function setRecipient(Address $address = NULL): Package
	{
		$this->recipient = $address;
		return $this;
	}



	/**
	 * @return \DhlMyApi\Address
	 */
	public function getRecipient(): Address
	{
		return $this->recipient;
	}



	/**
	 * @param \DhlMyApi\Address
	 * @return self
	 */
	public function setSender(Address $address = NULL): Package
	{
		$this->sender = $address;
		return $this;
	}



	/**
	 * @return \DhlMyApi\Address
	 */
	public function getSender(): ?Address
	{
		return $this->sender;
	}



	/**
	 * @param \DhlMyApi\Payment
	 * @return self
	 * @throws \Exception
	 */
	public function setPayment(Payment $payment = NULL): Package
	{
		if ($payment && !$this->isCashOnDelivery()) {
			throw new \Exception('Payment is available only for cash on delivery packages');
		}

		$this->payment = $payment;
		return $this;
	}



	/**
	 * @return \DhlMyApi\Payment
	 */
	public function getPayment(): Payment
	{
		return $this->payment;
	}



	/**
	 * @param array
	 * @return self
	 * @throws \Exception
	 */
	public function setFlags(array $flags): Package
	{
		foreach ($flags as $flag) {
			if (!in_array($flag, self::FLAGS)) {
				throw new \Exception("Unknown flag '" . $flag . "'");
			}
			$this->flags[] = $flag;
		}
		return $this;
	}



	/**
	 * @return array
	 */
	public function getFlags(): array
	{
		return $this->flags;
	}



	/**
	 * @param string
	 * @return self
	 */
	public function setNote(string $note): Package
	{
		$this->note = mb_substr($note, 0, 300);
		return $this;
	}



	/**
	 * @return string
	 */
	public function getNote(): ?string
	{
		return $this->note;
	}
}
