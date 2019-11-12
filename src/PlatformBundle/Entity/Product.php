<?php

namespace PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="PlatformBundle\Repository\ProductRepository")
 */
class Product
{


    /**
     * @ORM\ManyToOne(targetEntity="PlatformBundle\Entity\Category", inversedBy="products",fetch="EAGER", cascade={"persist","remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $category;



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
     * @ORM\Column(name="designation", type="string", length=255)
     */
    private $designation;

    /**
     * @var int
     *
     * @ORM\Column(name="code", type="integer")
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="reference", type="string", length=255)
     */
    private $reference;

    /**
     * @var string
     *
     * @ORM\Column(name="supplier", type="string", length=255)
     */
    private $supplier;

    /**
     * @var string
     *
     * @ORM\Column(name="market", type="string", length=255)
     */
    private $market;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * @var int
     *
     * @ORM\Column(name="cdt", type="integer")
     */
    private $cdt;


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
     * Set designation
     *
     * @param string $designation
     *
     * @return Product
     */
    public function setDesignation($designation)
    {
        $this->designation = $designation;

        return $this;
    }

    /**
     * Get designation
     *
     * @return string
     */
    public function getDesignation()
    {
        return $this->designation;
    }

    /**
     * Set code
     *
     * @param integer $code
     *
     * @return Product
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set reference
     *
     * @param string $reference
     *
     * @return Product
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Set supplier
     *
     * @param string $supplier
     *
     * @return Product
     */
    public function setSupplier($supplier)
    {
        $this->supplier = $supplier;

        return $this;
    }

    /**
     * Get supplier
     *
     * @return string
     */
    public function getSupplier()
    {
        return $this->supplier;
    }

    /**
     * Set market
     *
     * @param string $market
     *
     * @return Product
     */
    public function setMarket($market)
    {
        $this->market = $market;

        return $this;
    }

    /**
     * Get market
     *
     * @return string
     */
    public function getMarket()
    {
        return $this->market;
    }

    /**
     * Set price
     *
     * @param float $price
     *
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set cdt
     *
     * @param integer $cdt
     *
     * @return Product
     */
    public function setCdt($cdt)
    {
        $this->cdt = $cdt;

        return $this;
    }

    /**
     * Get cdt
     *
     * @return int
     */
    public function getCdt()
    {
        return $this->cdt;
    }

    /**
     * Set category
     *
     * @param \PlatformBundle\Entity\Category $category
     *
     * @return Product
     */
    public function setCategory(\PlatformBundle\Entity\Category $category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \PlatformBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set commandProduct
     *
     * @param \PlatformBundle\Entity\CommandProduct $commandProduct
     *
     * @return Product
     */
    public function setCommandProduct(\PlatformBundle\Entity\CommandProduct $commandProduct = null)
    {
        $this->commandProduct = $commandProduct;

        return $this;
    }

    /**
     * Get commandProduct
     *
     * @return \PlatformBundle\Entity\CommandProduct
     */
    public function getCommandProduct()
    {
        return $this->commandProduct;
    }
}
