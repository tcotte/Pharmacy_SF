<?php

namespace PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="PlatformBundle\Repository\CategoryRepository")
 */
class Category
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
     * @ORM\ManyToOne(targetEntity="PlatformBundle\Entity\Command", fetch="EAGER", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $command;


    /**
     * @ORM\OneToMany(targetEntity="PlatformBundle\Entity\Product", mappedBy="category", fetch="EAGER",cascade={"persist"})
     */
    private $products;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;


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
     * Set name
     *
     * @param string $name
     *
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->products = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add product
     *
     * @param \PlatformBundle\Entity\Product $product
     *
     * @return Category
     */
    public function addProduct(\PlatformBundle\Entity\Product $product)
    {
        // $this->products[] = $product;
        $product->setCategory($this);
        $this->products->add($product);
        // return $this;
    }

    /**
     * Remove product
     *
     * @param \PlatformBundle\Entity\Product $product
     */
    public function removeProduct(\PlatformBundle\Entity\Product $product)
    {
        $this->products->removeElement($product);
    }

    /**
     * Get products
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * Set command
     *
     * @param \PlatformBundle\Entity\Command $command
     *
     * @return Category
     */
    public function setCommand(\PlatformBundle\Entity\Command $command = null)
    {
        $this->command = $command;

        return $this;
    }

    /**
     * Get command
     *
     * @return \PlatformBundle\Entity\Command
     */
    public function getCommand()
    {
        return $this->command;
    }
}
