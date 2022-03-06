<?php

namespace App\Tests\Domain\Contract\Model;

use App\Domain\Contract\Exception\InvalidSignatureException;
use App\Domain\Contract\Model\Contract;
use PHPUnit\Framework\TestCase;

class ContractTest extends TestCase
{
    public function testScore()
    {
        $contract = Contract::createFromSignaturesString('KNV');
        $this->assertEquals(7, $contract->getScore());

        $contract = Contract::createFromSignaturesString('K');
        $this->assertEquals(5, $contract->getScore());

        $contract = Contract::createFromSignaturesString('N');
        $this->assertEquals(2, $contract->getScore());

        $contract = Contract::createFromSignaturesString('NN');
        $this->assertEquals(4, $contract->getScore());

        $contract = Contract::createFromSignaturesString('V');
        $this->assertEquals(1, $contract->getScore());

        $contract = Contract::createFromSignaturesString('NV');
        $this->assertEquals(3, $contract->getScore());
    }

    public function testSignatureXThrowsException()
    {
        $this->expectException(InvalidSignatureException::class);
        Contract::createFromSignaturesString('X');
    }

    public function testSignaturesShouldBeCreatedSorted()
    {
        $contract = Contract::createFromSignaturesString('VVNKV');
        $this->assertEquals('VVNKV', $contract->getSignatures());
    }
}
