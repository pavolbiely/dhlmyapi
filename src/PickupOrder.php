<?php declare(strict_types=1);

namespace DhlMyApi;

class PickupOrder
{
	/* @var string */
	protected $orderReferenceId;

	/* @var string */
	protected $customerRefs;

	/* @var int */
	protected $countPackages;

	/* @var string */
	protected $email;

	/** @var \DateTime */
	protected $sendDate;

	/** @var \DateTime */
	protected $sendTimeFrom;

	/** @var \DateTime */
	protected $sendTimeTo;

	/* @var string */
	protected $note;

	/* @var \DhlMyApi\Address */
	protected $sender;
	


	/**
	 * @param string
	 * @param string
	 * @param int
	 * @param Address
	 * @param string
	 * @param \DateTime
	 * @param \DateTime
	 * @param \DateTime
	 * @param string
	 */
	public function __construct(string $orderReferenceId, string $customerRefs = NULL, int $countPackages, Address $sender, string $email = NULL, \DateTime $sendDate = NULL, \DateTime $sendTimeFrom = NULL, \DateTime $sendTimeTo = NULL, string $note = NULL)
	{
		$this->setOrderReferenceId($orderReferenceId);
		$this->setCustomerRefs($customerRefs);
		$this->setCountPackages($countPackages);
		$this->setSender($sender);
		$this->setEmail($email);
		$this->setSendDate($sendDate);
		$this->setSendTimeFrom($sendTimeFrom);
		$this->setSendTimeTo($sendTimeTo);
		$this->setNote($note);
	}



	/**
	 * @param string
	 * @return self
	 */
	public function setOrderReferenceId(string $id): PickupOrder
	{
		$this->orderReferenceId = $id;
		return $this;
	}



	/**
	 * @return string
	 */
	public function getOrderReferenceId(): string
	{
		return $this->orderReferenceId;
	}



	/**
	 * @param string
	 * @return self
	 */
	public function setCustomerRefs(string $refs): PickupOrder
	{
		$this->customerRefs = $refs;
		return $this;
	}



	/**
	 * @return string
	 */
	public function getCustomerRefs(): string
	{
		return $this->customerRefs;
	}



	/**
	 * @param int
	 * @return self
	 */
	public function setCountPackages(int $count): PickupOrder
	{
		$this->countPackages = $count;
		return $this;
	}



	/**
	 * @return int
	 */
	public function getCountPackages(): int
	{
		return $this->countPackages;
	}



	/**
	 * @param string
	 * @return self
	 * @throws \Exception
	 */
	public function setEmail(string $email): PickupOrder
	{
		if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
			throw new \Exception("E-mail '" . $email . "' has invalid format");
		}

		$this->email = $email;
		return $this;
	}



	/**
	 * @return string
	 */
	public function getEmail(): string
	{
		return $this->email;
	}



	/**
	 * @param \DateTime
	 * @return self
	 */
	public function setSendDate(\DateTime $date = NULL): PickupOrder
	{
		$this->sendDate = $date;
		return $this;
	}



	/**
	 * @return \DateTime|NULL
	 */
	public function getSendDate(): ?\DateTime
	{
		return $this->sendDate;
	}



	/**
	 * @param \DateTime
	 * @return self
	 */
	public function setSendTimeFrom(\DateTime $time = NULL): PickupOrder
	{
		$this->sendTimeFrom = $time;
		return $this;
	}



	/**
	 * @return \DateTime|NULL
	 */
	public function getSendTimeFrom(): ?\DateTime
	{
		return $this->sendTimeFrom;
	}



	/**
	 * @param \DateTime
	 * @return self
	 */
	public function setSendTimeTo(\DateTime $time = NULL): PickupOrder
	{
		$this->sendTimeTo = $time;
		return $this;
	}



	/**
	 * @return \DateTime|NULL
	 */
	public function getSendTimeTo(): ?\DateTime
	{
		return $this->sendTimeTo;
	}



	/**
	 * @param string
	 * @return self
	 */
	public function setNote(string $note): PickupOrder
	{
		$this->note = $note;
		return $this;
	}



	/**
	 * @return string
	 */
	public function getNote(): string
	{
		return $this->note;
	}



	/**
	 * @param \DhlMyApi\Address
	 * @return self
	 */
	public function setSender(Address $sender): PickupOrder
	{
		$this->sender = $sender;
		return $this;
	}



	/**
	 * @return \DhlMyApi\Address
	 */
	public function getSender(): Address
	{
		return $this->sender;
	}
}
