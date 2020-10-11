<?php


namespace App\Tests\Controller\Api;

use App\Test\ApiTestCase;

class RecordControllerTest extends ApiTestCase
{
    public function testCreateRecord()
    {
        $artist = $this->createArtist('test');
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

    public function testDeleteRecord()
    {
        $record = $this->createRecord('record_title', 'record_desc', 10000);
        $this->client->request(
            'DELETE',
            '/api/records/' . $record->getId(),
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
        );
        $this->assertEquals(204, $this->client->getResponse()->getStatusCode());
    }

}
