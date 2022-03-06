<?php

namespace App\Tests\Domain\Contract\Model;

use App\Domain\Contract\Exception\InvalidSignatureException;
use App\Domain\Contract\Model\IncompleteContract;
use PHPUnit\Framework\TestCase;

class IncompleteContractTest extends TestCase
{
    /**
     * @throws InvalidSignatureException
     */
    public function testScore()
    {
        $contract = IncompleteContract::createFromSignaturesString('K#NV');
        $this->assertEquals(7, $contract->getScore());

        $contract = IncompleteContract::createFromSignaturesString('K#');
        $this->assertEquals(5, $contract->getScore());

        $contract = IncompleteContract::createFromSignaturesString('#N');
        $this->assertEquals(2, $contract->getScore());

        $contract = IncompleteContract::createFromSignaturesString('#NN');
        $this->assertEquals(4, $contract->getScore());

        $contract = IncompleteContract::createFromSignaturesString('#V');
        $this->assertEquals(1, $contract->getScore());

        $contract = IncompleteContract::createFromSignaturesString('N#V');
        $this->assertEquals(3, $contract->getScore());
    }

    public function testSignatureWithoutHashThrowsException()
    {
        $this->expectException(InvalidSignatureException::class);
        IncompleteContract::createFromSignaturesString('KV');
    }

    public function testSignatureDoubleHashThrowsException()
    {
        $this->expectException(InvalidSignatureException::class);
        IncompleteContract::createFromSignaturesString('K#V#');
    }

    public function testSignatureExceptionMessageShouldContainsTheSignature()
    {
        $this->expectException(InvalidSignatureException::class);
        $this->expectExceptionMessage("Signatures string 'KC' is not valid");
        IncompleteContract::createFromSignaturesString('K#C');
    }
}
