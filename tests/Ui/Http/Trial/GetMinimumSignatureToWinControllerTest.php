<?php

namespace App\Tests\Ui\Http\Trial;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GetMinimumSignatureToWinControllerTest extends WebTestCase
{
    public function testKingIsNeededWins(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/trial/K%23N/NNV/guess-signature');
        $response = $client->getResponse();
        $responseData = json_decode($response->getContent(), true);

        $this->assertResponseIsSuccessful();
        $this->assertEquals('No signature required to win the trial', $responseData['result']);
    }

    public function testInvalidsGuessException(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/trial/N%23R/KNN/guess-signature');

        $this->assertResponseStatusCodeSame(400);
    }

    public function testUnreachableGuessException(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/trial/V%23/KNN/guess-signature');

        $this->assertResponseStatusCodeSame(404);
    }
}
