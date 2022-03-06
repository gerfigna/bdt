<?php

namespace App\Tests\Application\Trial\Handler;

use App\Application\Trial\Handler\GetTrialWinnerHandler;
use App\Application\Trial\Query\GetTrialWinnerQuery;
use App\Domain\Contract\Exception\InvalidSignatureException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CompareContractSignatureHandlerTest extends KernelTestCase
{
    protected GetTrialWinnerHandler $handler;

    protected static function createQuery($plaintiffSignatures, $defendantSignatures): GetTrialWinnerQuery
    {
        return new GetTrialWinnerQuery($plaintiffSignatures, $defendantSignatures);
    }

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
        $container = static::getContainer();

        /** @var GetTrialWinnerHandler $handler */
        $handler = $container->get(GetTrialWinnerHandler::class);
        $this->handler = $handler;
    }

    public function testInvalidPlaintiffSignature()
    {
        $this->expectException(InvalidSignatureException::class);
        $query = self::createQuery('A', 'KNV');

        $this->handler->__invoke($query);
    }

    public function testInvalidDefendantSignature()
    {
        $this->expectException(InvalidSignatureException::class);
        $query = self::createQuery('KNV', '');

        $this->handler->__invoke($query);
    }

    public function testPlaintiffWins()
    {
        $query = self::createQuery('KVV', 'NVV');
        $this->assertStringContainsString('plaintiff', $this->handler->__invoke($query)->getTrialResult());
    }

    public function testNoOneWins()
    {
        $query = self::createQuery('VV', 'VV');
        $this->assertStringContainsString('Nobody', $this->handler->__invoke($query)->getTrialResult());
    }

    public function testDefendantWins()
    {
        $query = self::createQuery('VV', 'NV');
        $this->assertStringContainsString('defendant', $this->handler->__invoke($query)->getTrialResult());
    }

    public function testSignatureOrderDoesNotAlterTheResult()
    {
        $query = self::createQuery('VNK', 'KNV');
        $this->assertStringContainsString('Nobody', $this->handler->__invoke($query)->getTrialResult());
    }
}
