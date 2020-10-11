<?php


namespace App\Tests\Controller\Api;

use App\Test\ApiTestCase;

class RecordControllerTest extends ApiTestCase
{
    public function testCreateRecord()
    {
        $artist = $this->createArtist('testArtist');
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


    /**
     * @dataProvider updateDataProvider
     */
    public function testUpdateRecord($title, $desc, $price)
    {
        $old_artist = $this->createArtist('testArtist');
        $new_artist = $this->createArtist('newArtist');
        $record = $this->createRecord('oldTitle', 'oldDesc', 10000, $old_artist);

        $this->client->request(
            'PUT',
            '/api/records/' . $record->getId(),
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(
                array(
                    'title' => $title,
                    'description' => $desc,
                    'price' => $price,
                    'artist' => $new_artist->getId()
                )
            )
        );


        $itemArray = json_decode($this->client->getResponse()->getContent(), true);

        if (!empty($title)) {
            $this->assertEquals($title, $itemArray['title']);
        }
        if (!empty($desc)) {
            $this->assertEquals($desc, $itemArray['description']);
        }
        if (!empty($price)) {
            $this->assertEquals($price, $itemArray['price']);
        }
        $this->assertEquals($new_artist->getName(), $itemArray['artist']['name']);


        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function updateDataProvider()
    {
        return [
            ['new_title', 'new_description', 9999],
            [null, 'new_description', 9999],
            [null, null, 9999],
            [null, null, null],
        ];
    }

}
