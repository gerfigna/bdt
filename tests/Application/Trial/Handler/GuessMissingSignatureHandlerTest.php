<?php

namespace App\Tests\Application\Trial\Handler;

use App\Application\Trial\Handler\GuessMissingSignatureHandler;
use App\Application\Trial\Query\GuessMissingSignatureQuery;
use App\Domain\Contract\Exception\InvalidSignatureException;
use App\Domain\Contract\Exception\UnreachableScoreException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GuessMissingSignatureHandlerTest extends KernelTestCase
{
    protected GuessMissingSignatureHandler $handler;

    protected static function createQuery($incompleteSignatures, $oppositionSignatures): GuessMissingSignatureQuery
    {
        return new GuessMissingSignatureQuery($incompleteSignatures, $oppositionSignatures);
    }

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
        $container = static::getContainer();

        /** @var GuessMissingSignatureHandler $handler */
        $handler = $container->get(GuessMissingSignatureHandler::class);
        $this->handler = $handler;
    }

    public function testInvalidIncompleteSignature()
    {
        $this->expectException(InvalidSignatureException::class);
        $query = self::createQuery('NV', 'KNV');

        $this->handler->__invoke($query);
    }

    public function testInvalidNonHashSignature()
    {
        $this->expectException(InvalidSignatureException::class);
        $query = self::createQuery('KNV', 'KVV');

        $this->handler->__invoke($query);
    }

    public function testNotaryToWin()
    {
        $query = self::createQuery('N#V', 'NVV');
        $this->assertStringContainsString('N', $this->handler->__invoke($query)->getSignatureToWin());
    }

    public function testValidatorWin()
    {
        $query = self::createQuery('N#V', 'NV');
        $this->assertStringContainsString('"V"', $this->handler->__invoke($query)->getSignatureToWin());
    }

    public function testKinkToWin()
    {
        $query = self::createQuery('N#', 'KV');
        $this->assertStringContainsString('"K"', $this->handler->__invoke($query)->getSignatureToWin());
    }

    public function testUnreachableResult()
    {
        $this->expectException(UnreachableScoreException::class);
        $query = self::createQuery('V#', 'KNV');
        $this->handler->__invoke($query);
    }

    public function testInvalidSign()
    {
        $this->expectException(UnreachableScoreException::class);
        $query = self::createQuery('K#', 'KNNN');
        $this->handler->__invoke($query);
    }
}
