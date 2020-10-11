<?php


namespace App\Tests\Entity;

use App\Entity\Artist;
use App\Test\EntityTestCase;

class ArtistTest extends EntityTestCase
{
    public function testCreation()
    {
        $artist = new Artist();
        $artist->setName('testName');
        $this->entityManager->persist($artist);
        $this->entityManager->flush();

        /** @var Artist $item */
        $item = $this->entityManager->getRepository(Artist::class)->find($artist->getId());
        $this->assertInstanceOf(Artist::class, $item);
        $this->assertEquals($artist->getId(), $item->getId());
        $this->assertEquals('testName', $item->getName());
    }

}
