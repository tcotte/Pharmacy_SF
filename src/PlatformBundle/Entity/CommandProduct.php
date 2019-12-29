<?php

namespace PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CommandProduct
 *
 * @ORM\Table(name="commandproduct")
 * @ORM\Entity(repositoryClass="PlatformBundle\Repository\CommandProductRepository")
 */
class CommandProduct
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
     * @ORM\ManyToOne(targetEntity="PlatformBundle\Entity\Command", fetch="EAGER", cascade={"persist", "remove"})
     * @ORM\joinColumn(onDelete="SET NULL")
     */
    private $command;

    /**
     * @ORM\ManyToOne(targetEntity="PlatformBundle\Entity\Product", fetch="EAGER", cascade={"persist"})
     * @ORM\joinColumn(onDelete="SET NULL")
     */
    private $product;

    /**
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;



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
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return CommandProduct
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set command
     *
     * @param \PlatformBundle\Entity\Command $command
     *
     * @return CommandProduct
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

    /**
     * Set product
     *
     * @param \PlatformBundle\Entity\Product $product
     *
     * @return CommandProduct
     */
    public function setProduct(\PlatformBundle\Entity\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \PlatformBundle\Entity\Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return CommandProduct
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }
}
