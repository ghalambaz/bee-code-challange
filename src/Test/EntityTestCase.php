<?php


namespace App\Test;


use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EntityTestCase extends KernelTestCase
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    protected function setUp()
    {
        $this->entityManager = $this->getService('doctrine')
            ->getManager();

        $this->purgeDatabase();
    }

    protected function getService($id)
    {
        return self::bootKernel()->getContainer()
            ->get($id);
    }

    private function purgeDatabase()
    {
        $purger = new ORMPurger($this->entityManager);
        $purger->purge();
    }

}
