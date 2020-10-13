<?php


namespace App\Test;

use App\DataFixtures\RecordFixtures;
use App\Entity\Artist;
use App\Entity\Record;
use App\Entity\User;
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

    /**
     * @var array
     */
    protected $tokenHeader;

    protected function setUp()
    {
        $this->client = static::createClient();
        $this->entityManager = $this->getService('doctrine')
            ->getManager();
        $this->purgeDatabase();
        $this->fillTestData();
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

    protected function fillTestData()
    {
        $artists = [];
        $names = ['aaa', 'bbb', 'ccc', 'ddd', 'eee'];
        for ($i = 0; $i < count($names); $i++) {
            $artist = new Artist();
            $artist->setName($names[$i]);
            $this->entityManager->persist($artist);
            $artists[] = $artist;
        }

        $record = new Record('A', 'F', $artists[0], 1000);
        $this->entityManager->persist($record);
        $record = new Record('A', 'G', $artists[0], 1000);
        $this->entityManager->persist($record);
        $record = new Record('B', 'H', $artists[1], 2000);
        $this->entityManager->persist($record);
        $record = new Record('B', 'I', $artists[1], 2000);
        $this->entityManager->persist($record);
        $record = new Record('C', 'J', $artists[2], 3000);
        $this->entityManager->persist($record);
        $record = new Record('C', 'K', $artists[2], 3000);
        $this->entityManager->persist($record);
        $record = new Record('D', 'L', $artists[3], 4000);
        $this->entityManager->persist($record);
        $record = new Record('D', 'M', $artists[3], 4000);
        $this->entityManager->persist($record);
        $record = new Record('E', 'N', $artists[4], 5000);
        $this->entityManager->persist($record);
        $record = new Record('E', 'O', $artists[4], 5000);
        $this->entityManager->persist($record);

        $this->entityManager->flush();
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

    /**
     * @param string $username
     * @param string $password
     * @return User
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createUser($username = 'ali', $password = 'password')
    {
        $user = new User();
        $user->setPassword($password);
        $user->setUsername($username);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    public function getToken(User $user)
    {
        return $this->getService('lexik_jwt_authentication.encoder')
            ->encode(['username' => $user->getUsername()]);
    }

    private function getNewToken()
    {
        $user = $this->createUser('ali' . rand(0, 10000), 'password' . rand(0, 10000));
        return $this->getService('lexik_jwt_authentication.encoder')
            ->encode(['username' => $user->getUsername()]);
    }

    public function getTokenHeader(?User $user = null)
    {
        if (!is_null($this->tokenHeader)) {
            return $this->tokenHeader;
        }
        if (is_null($user)) {
            $user = $this->createUser();
        }
        $this->tokenHeader = [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $this->getToken($user),
            'CONTENT_TYPE' => 'application/json',
        ];
        return $this->tokenHeader;
    }

}
