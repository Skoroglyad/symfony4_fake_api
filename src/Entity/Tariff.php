<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="tariff")
 * @ORM\Entity(repositoryClass="App\Repository\TariffRepository")
 */
class Tariff
{
    public function __construct()
    {
        $this->orders = new ArrayCollection();
    }

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     * @Assert\Length(max="255")
     */
    private $title;

    /**
     * @ORM\Column(name="coefficient", type="float", nullable=false)
     * @Assert\GreaterThan(value = 0)
     */
    private $coefficient;

    /**
     * @ORM\Column(name="tariff_option", type="integer", nullable=false)
     */
    private $option;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Order", mappedBy="tariff")
     */
    private $orders;

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
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getCoefficient()
    {
        return $this->coefficient;
    }

    /**
     * @param mixed $coefficient
     */
    public function setCoefficient($coefficient): void
    {
        $this->coefficient = $coefficient;
    }

    /**
     * @return mixed
     */
    public function getOption()
    {
        return $this->option;
    }

    /**
     * @param mixed $option
     */
    public function setOption($option): void
    {
        $this->option = $option;
    }

    /**
     * @return mixed
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * @param Order $order
     * @return $this
     */
    public function addOrder(Order $order)
    {
        $this->orders[] = $order;
        return $this;
    }

    public function removeOrder(Order $order)
    {
        $this->orders->removeElement($order);
        return $this;
    }
}