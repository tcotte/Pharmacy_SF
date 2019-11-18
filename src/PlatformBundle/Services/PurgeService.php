<?php

namespace PlatformBundle\Services;



use Doctrine\ORM\EntityManager;
use PlatformBundle\Entity\Command;

class PurgeService
{
    private $nbDays;

    public function __construct(EntityManager $entityManager, $nbDays)
    {
        $this->em = $entityManager;
        $this->nbDays = (int) $nbDays;
    }

    public function purge()
    {
        $em = $this->em;
        $nbDays = $this->nbDays;

        $oldCommand = $em->getRepository(Command::class)->findByTreatementAndDate( $nbDays);
        foreach ($oldCommand as $command)
        {
            $em->remove($command);
        }
        $em->flush();
    }
}