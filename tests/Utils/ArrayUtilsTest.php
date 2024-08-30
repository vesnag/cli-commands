<?php

declare(strict_types=1);

namespace App\Tests\Utils;

use App\Utils\ArrayUtils;
use PHPUnit\Framework\TestCase;

class ArrayUtilsTest extends TestCase
{
    public function testGetWithExistingKey(): void
    {
        $array = ['key1' => 'value1', 'key2' => 'value2'];
        $this->assertSame('value1', ArrayUtils::get($array, 'key1'));
        $this->assertSame('value2', ArrayUtils::get($array, 'key2'));
    }

    public function testGetWithNonExistingKeyAndNoDefault(): void
    {
        $array = ['key1' => 'value1', 'key2' => 'value2'];
        $this->assertNull(ArrayUtils::get($array, 'key3'));
    }

    public function testGetWithNonExistingAndDefault(): void
    {
        $array = ['key1' => 'value1', 'key2' => 'value2'];
        $this->assertSame('default', ArrayUtils::get($array, 'key3', 'default'));
    }

    public function testGetWithNullValue(): void
    {
        $array = ['key1' => null];
        $this->assertNull(ArrayUtils::get($array, 'key1'));
    }
}
