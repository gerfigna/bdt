<?php

namespace App\Tests\Ui\Http\Trial;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GetTrialWinnerControllerTest extends WebTestCase
{
    public function testPlaintiffWins(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/trial/KNN/NNV/winner');

        $this->assertResponseIsSuccessful();

        $response = $client->getResponse();
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('plaintiff part wins with KNN signatures and 9 points', $responseData['result']);
    }

    public function testKingSignatureInvalidateValidator(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/trial/KNN/KNNV/winner');

        $this->assertResponseIsSuccessful();

        $response = $client->getResponse();
        $responseData = json_decode($response->getContent(), true);

        $this->assertEquals('Nobody wins', $responseData['result']);
    }

    public function testInvalidException(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/trial/CAS/KNNV/winner');

        $this->assertResponseStatusCodeSame(400);
    }
}
