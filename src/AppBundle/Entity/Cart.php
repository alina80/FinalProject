<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Cart
 *
 * @ORM\Table(name="cart")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CartRepository")
 */
class Cart
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="cookie", type="string", length=45)
     */
    private $cookie;

    /**
     * @var string
     *
     * @ORM\Column(name="cart_time", type="string", length=45)
     */
    private $cartTime;

    /**
     * @ORM\OneToMany(targetEntity="CartItem", mappedBy="cart")
     */
    private $cartItems;

    /**
     * @return ArrayCollection
     */
    public function getCartItems()
    {
        return $this->cartItems;
    }

    public function __construct()
    {
        $this->cartItems = new ArrayCollection();
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set cookie
     *
     * @param string $cookie
     *
     * @return Cart
     */
    public function setCookie($cookie)
    {
        $this->cookie = $cookie;

        return $this;
    }

    /**
     * Get cookie
     *
     * @return string
     */
    public function getCookie()
    {
        return $this->cookie;
    }

    /**
     * Set cartTime
     *
     * @param string $cartTime
     *
     * @return Cart
     */
    public function setCartTime($cartTime)
    {
        $this->cartTime = $cartTime;

        return $this;
    }

    /**
     * Get cartTime
     *
     * @return string
     */
    public function getCartTime()
    {
        return $this->cartTime;
    }
}

