<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use PlatformBundle\Entity\Category;

class LayoutDataService
{
    /** @var EntityManager $em */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getLayoutData(): array
    {
        $categories = $this->em->getRepository('PlatformBundle:Category')->findAll();

        return $categories;
    }
}
