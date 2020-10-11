<?php


namespace App\Tests\Controller\Api;

use App\Entity\Artist;
use App\Test\ApiTestCase;

class RecordControllerTest extends ApiTestCase
{
    public function testRecordCreation()
    {
        $artist = new Artist();
        $artist->setName('testName');
        $this->entityManager->persist($artist);
        $this->entityManager->flush();

        $this->client->request(
            'POST',
            '/api/records',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(
                array(
                    'title' => 'test Title',
                    'description' => 'test Description',
                    'price' => 1234,
                    'artist' => $artist->getId()
                )
            )
        );
        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
    }

}
