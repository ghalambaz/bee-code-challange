<?php

namespace App\DataFixtures;

use App\Entity\Artist;
use App\Entity\Record;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class RecordFixtures extends Fixture
{
    /**
     * @var int
     */
    private $artistCount;
    /**
     * @var int
     */
    private $recordCount;
    /**
     * @var Generator
     */
    protected $faker;
    /**
     * @var ObjectManager
     */
    protected $manager;

    /**
     * RecordFixtures constructor.
     * @param int $artistCount
     * @param int $recordCount
     */
    public function __construct(int $artistCount = 20, int $recordCount = 100)
    {
        $this->artistCount = $artistCount;
        $this->recordCount = $recordCount;
    }

    public function load(ObjectManager $manager)
    {
        $this->faker = Factory::create();
        $this->manager = $manager;
        $artists = $this->artistFixtures();
        $this->recordFixtures($artists);
        $manager->flush();
    }

    /**
     * @return array|Artist
     */
    private function artistFixtures()
    {
        $artists = [];
        for ($i = 1; $i <= $this->artistCount; $i++) {
            $artist = new Artist();
            $artist->setName($this->faker->name);
            $this->manager->persist($artist);
            $artists[] = $artist;
        }
        return $artists;
    }

    private function recordFixtures($artists)
    {
        for ($i = 1; $i <= $this->recordCount; $i++) {
            $record = new Record(
                $this->faker->text(50),
                $this->faker->realText(),
                $this->faker->randomElement($artists),
                $this->faker->numberBetween($min = 1000, $max = 9000)
            );
            $this->manager->persist($record);
        }
    }
}
