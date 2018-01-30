<?php

namespace Tests\AppBundle\Controller;

use Helmich\JsonAssert\JsonAssertions;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Client;
use Symfony\Component\HttpFoundation\Response;

class BidsControllerTest extends WebTestCase
{
    /**
     * @dataProvider invalidPostProvider
     */
    public function testPostInvalidBid(array $postData)
    {
        $client = static::createClient();
        $client->request('POST', '/bids', $postData);
        $this->assertStatusCode(Response::HTTP_BAD_REQUEST, $client);
    }

    public function invalidPostProvider()
    {
        yield 'invalid direction' => [
            'direction' => 'demand1',
            'commodity' => 1,
            'unit' => 1,
            'priceMin' => 0,
            'priceMax' => 100,
        ];

        yield 'invalid min price' => [
            'direction' => 'demand',
            'commodity' => 1,
            'unit' => 1,
            'priceMin' => -100,
            'priceMax' => 100,
        ];
    }

    public function testPostBid()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/bids',
            [
                'direction' => 'demand',
                'commodity' => 1,
                'unit' => 1,
                'priceMin' => 0,
                'priceMax' => 100,
            ]
        );

        $this->assertStatusCode(Response::HTTP_OK, $client);
        $this->assertJsonValueEquals($client->getResponse()->getContent(), 'bid.direction', 'demand');
    }

    public function testGetBidsByTarget()
    {
        $client = static::createClient();
        $client->request(
            'GET',
            '/bids',
            [
                'target' => 'demand'
            ],
            [],
            ['HTTP_Accept' => 'application/json']
        );
        $this->assertStatusCode(Response::HTTP_OK, $client);
        $this->assertAllJsonValuesMatch($client->getResponse()->getContent(), 'bids..target', \PHPUnit_Framework_Assert::contains('demand'));
    }

    public function testPatchBidDirection()
    {
        $client = static::createClient();
        $client->request(
            'PATCH',
            '/bids/1',
            [
                'direction' => 'offer',
            ],
            [],
            ['HTTP_Accept' => 'application/json']
        );

        $this->assertStatusCode(Response::HTTP_OK, $client);
        $this->assertJsonValueEquals($client->getResponse()->getContent(), 'bid.direction', 'offer');
    }

    public function testDeleteBid()
    {
        $client = static::createClient();
        $client->request(
            'GET',
            '/bids/1',
            [],
            [],
            ['HTTP_Accept' => 'application/json']
        );
        $this->assertStatusCode(Response::HTTP_OK, $client);

        $client->request(
            'DELETE',
            '/bids/1'
        );
        $this->assertStatusCode(Response::HTTP_OK, $client);

        $client->request(
            'GET',
            '/bids/1',
            [],
            [],
            ['HTTP_Accept' => 'application/json']
        );

        $this->assertStatusCode(Response::HTTP_NOT_FOUND, $client);
    }
}
