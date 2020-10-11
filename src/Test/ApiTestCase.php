<?php


namespace App\Test;

use App\DataFixtures\RecordFixtures;
use App\Entity\Artist;
use App\Entity\Record;
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
        $this->purgeDatabase();
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

    private function purgeDatabase()
    {
        $purger = new ORMPurger($this->entityManager);
        $purger->purge();
    }

    /**
     * @param $name
     * @return Artist
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    protected function createArtist($name)
    {
        $artist = new Artist();
        $artist->setName($name);
        $this->entityManager->persist($artist);
        $this->entityManager->flush();
        return $artist;
    }

    /**
     * @param $title
     * @param $description
     * @param $price
     * @param null $artist
     * @return Record
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    protected function createRecord($title, $description, $price, $artist = null)
    {
        if (is_null($artist)) {
            $artist = $this->createArtist('test');
        }
        $record = new Record($title, $description, $artist, $price);
        $this->entityManager->persist($record);
        $this->entityManager->flush();
        return $record;
    }

}
