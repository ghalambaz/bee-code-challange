<?php


namespace App\Tests\Controller\Api;

use App\Test\ApiTestCase;

class RecordControllerTest extends ApiTestCase
{
    public function testNotFoundException()
    {
        $this->client->request(
            'POST',
            '/api/notfound',
            [],
            [],
            $this->getTokenHeader()
        );
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateRecord()
    {
        $artist = $this->createArtist('testArtist');
        $this->client->request(
            'POST',
            '/api/records',
            [],
            [],
            $this->getTokenHeader(),
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
            $this->getTokenHeader()
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
            $this->getTokenHeader(),
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

    /**
     * @dataProvider searchData
     */
    public function testSearchRecord($query, $results)
    {
        $this->client->request(
            'GET',
            '/api/records?' . $query,
            [],
            []
        );

        $arrayData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('records', $arrayData);
        $this->assertArrayHasKey('count', $arrayData);
        $this->assertEquals(count($arrayData['records']), $arrayData['count']);
        $this->assertEquals(count($arrayData['records']), $results);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function searchData()
    {
        return [
            ['', 10],
            ['artist=aaa', 2],
            ['artist=aaa&title=A', 2],
            ['artist=aaa&title=A&description=F', 1],
            ['artist=Q', 0]
        ];
    }


    public function testAuthenticationRequired()
    {
        $this->client->request(
            'POST',
            '/api/records',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json']
        );
        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());
    }


}
