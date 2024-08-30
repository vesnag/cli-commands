<?php

declare(strict_types=1);

namespace App\Tests\Utils;

use App\Utils\ConvertTo;
use App\Exception\InvalidStringValueException;
use App\Exception\InvalidIntValueException;
use PHPUnit\Framework\TestCase;

class ConvertToTest extends TestCase
{
    public function testStringWithValidScalarValue(): void
    {
        $this->assertSame('123', ConvertTo::string(123, 'testKey'));
        $this->assertSame('test', ConvertTo::string('test', 'testKey'));
        $this->assertSame('1', ConvertTo::string(true, 'testKey'));
        $this->assertSame('', ConvertTo::string(false, 'testKey'));
    }

    public function testStringWithInvalidValue(): void
    {
        $this->expectException(InvalidStringValueException::class);
        ConvertTo::string(['invalid'], 'testKey');
    }

    public function testIntWithValidScalarValue(): void
    {
        $this->assertSame(123, ConvertTo::int(123, 'testKey'));
        $this->assertSame(0, ConvertTo::int('0', 'testKey'));
        $this->assertSame(1, ConvertTo::int(true, 'testKey'));
        $this->assertSame(0, ConvertTo::int(false, 'testKey'));
    }

    public function testIntWithInvalidValue(): void
    {
        $this->expectException(InvalidIntValueException::class);
        ConvertTo::int(['invalid'], 'testKey');
    }
}
