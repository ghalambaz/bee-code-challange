<?php


namespace App\Test;


use App\DataFixtures\RecordFixtures;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiTestCase extends WebTestCase
{
    /*
     * @var KernelBrowser
     */
    protected $client;
    /**
     * @var EntityManager
     */
    protected $entityManager;

    protected function setUp()
    {
        $this->client = static::createClient();
        $this->entityManager = $this->getService('doctrine')
            ->getManager();
        //$this->purgeDatabase();
    }

    /**
     * @param string $id
     * @return object|null
     */
    protected function getService(string $id)
    {
        return self::bootKernel()->getContainer()
            ->get($id);
    }

    /**
     * @param int $artists
     * @param int $records
     */
    protected function fillDatabase(int $artists = 20, int $records = 100)
    {
        $fixtures = new RecordFixtures($artists, $records);
        $fixtures->load($this->entityManager);
    }

    protected function getBaseUri(string $uri)
    {
        return $uri;
    }

    private function purgeDatabase()
    {
        $purger = new ORMPurger($this->entityManager);
        $purger->purge();
    }

}
