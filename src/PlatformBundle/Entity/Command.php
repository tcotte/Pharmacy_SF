<?php

namespace PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Command
 *
 * @ORM\Table(name="command")
 * @ORM\Entity(repositoryClass="PlatformBundle\Repository\CommandRepository")
 */
class Command
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
     * @ORM\ManyToOne(targetEntity="PlatformBundle\Entity\Category", fetch="EAGER", cascade={"persist","remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", fetch="EAGER", cascade={"persist","remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="PlatformBundle\Entity\CommandProduct", mappedBy="command", fetch="EAGER",cascade={"persist","remove"})
     */
    private $commandProducts;

    /**
     * @ORM\Column(name="creationDate", type="datetime")
     */
    private $creationDate;

    /**
     * @ORM\Column(name="treatmentDate", type="datetime", nullable=true)
     */
    private $treatmentDate;

    /**
     * @var boolean
     *
     * @ORM\Column(name="treat", type="boolean")
     */
    private $treat;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->creationDate       = new \Datetime();
        $this->treat     = false;
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
     * Set creationDate
     *
     * @param \DateTime $creationDate
     *
     * @return Command
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * Get creationDate
     *
     * @return \DateTime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Set category
     *
     * @param \PlatformBundle\Entity\Category $category
     *
     * @return Command
     */
    public function setCategory(\PlatformBundle\Entity\Category $category = null)
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
     * Add commandProduct
     *
     * @param \PlatformBundle\Entity\CommandProduct $commandProduct
     *
     * @return Command
     */
    public function addCommandProduct(\PlatformBundle\Entity\CommandProduct $commandProduct)
    {
        $this->commandProducts[] = $commandProduct;

        return $this;
    }

    /**
     * Remove commandProduct
     *
     * @param \PlatformBundle\Entity\CommandProduct $commandProduct
     */
    public function removeCommandProduct(\PlatformBundle\Entity\CommandProduct $commandProduct)
    {
        $this->commandProducts->removeElement($commandProduct);
    }

    /**
     * Get commandProducts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCommandProducts()
    {
        return $this->commandProducts;
    }

    /**
     * Set treat
     *
     * @param boolean $treat
     *
     * @return Command
     */
    public function setTreat($treat)
    {
        $this->treat = $treat;

        return $this;
    }

    /**
     * Get treat
     *
     * @return boolean
     */
    public function getTreat()
    {
        return $this->treat;
    }

    /**
     * Set user
     *
     * @param \UserBundle\Entity\User $user
     *
     * @return Command
     */
    public function setUser(\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set treatmentDate
     *
     * @param \DateTime $treatmentDate
     *
     * @return Command
     */
    public function setTreatmentDate($treatmentDate)
    {
        $this->treatmentDate = $treatmentDate;

        return $this;
    }

    /**
     * Get treatmentDate
     *
     * @return \DateTime
     */
    public function getTreatmentDate()
    {
        return $this->treatmentDate;
    }
}
