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
    const ARTIST_COUNT = 30;
    const RECORD_COUNT = 1000;
    /**
     * @var Generator
     */
    protected $faker;
    /**
     * @var ObjectManager
     */
    protected $manager;

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
        for ($i = 1; $i <= self::ARTIST_COUNT; $i++) {
            $artist = new Artist();
            $artist->setName($this->faker->name);
            $this->manager->persist($artist);
            $artists[] = $artist;
        }
        return $artists;
    }

    private function recordFixtures($artists)
    {
        for ($i = 1; $i <= self::RECORD_COUNT; $i++) {
            $record = new Record();
            $record->setTitle($this->faker->text(50));
            $record->setDescription($this->faker->realText());
            $record->setPrice($this->faker->numberBetween($min = 1000, $max = 9000));
            $record->setArtist($this->faker->randomElement($artists));
            $this->manager->persist($record);
        }
    }
}
