<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="client_order")
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 */
class Order
{
    const STATUS_DRAFT = 0;
    const STATUS_PAID = 1;
    const STATUS_READY = 2;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="last_name", type="string", length=255, nullable=false)
     * @Assert\Length(max="255")
     */
    private $lastName;

    /**
     * @ORM\Column(name="email", type="string", length=100, nullable=false)
     * @Assert\Length(max="150")
     */
    private $email;

    /**
     * @ORM\Column(name="amount", type="float", nullable=true)
     */
    private $amount;

    /**
     * @ORM\Column(name="sk_id", type="integer", nullable=true)
     */
    private $sk_id;

    /**
     * @ORM\Column(name="id_payment", type="integer", nullable=true)
     */
    private $id_payment;

    /**
     * @ORM\Column(name="status", type="smallint", nullable=false)
     */
    private $status = self::STATUS_DRAFT;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tariff", inversedBy="orders")
     * @ORM\JoinColumn(name="tariff_id", referencedColumnName="id", nullable=true)
     */
    private $tariff;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getIdPayment()
    {
        return $this->id_payment;
    }

    /**
     * @param mixed $id_payment
     */
    public function setIdPayment($id_payment): void
    {
        $this->id_payment = $id_payment;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return mixed
     */
    public function getSkId()
    {
        return $this->sk_id;
    }

    /**
     * @param mixed $sk_id
     */
    public function setSkId($sk_id): void
    {
        $this->sk_id = $sk_id;
    }

    /**
     * @return mixed
     */
    public function getTariff()
    {
        return $this->tariff;
    }

    /**
     * @param mixed $tariff
     */
    public function setTariff($tariff): void
    {
        $this->tariff = $tariff;
    }
}