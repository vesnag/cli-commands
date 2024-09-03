<?php

declare(strict_types=1);

namespace App\Tests\RepoStatusBundle\Collection;

use App\RepoStatusBundle\Collection\ResponseCollection;
use App\RepoStatusBundle\Question\QuestionInterface;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ResponseCollectionTest extends TestCase
{
    public function testAddAndGetResponse(): void
    {
        $collection = new ResponseCollection();

        $questionMock = $this->createMock(QuestionInterface::class);

        $collection->addResponse('key1', 'value1', $questionMock);
        $collection->addResponse('key2', true, $questionMock);

        $this->assertSame('value1', $collection->getResponse('key1'));
        $this->assertTrue($collection->getResponse('key2'));
    }

    public function testAddDuplicateResponseThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Response for key 'key1' already exists.");

        $collection = new ResponseCollection();
        $questionMock = $this->createMock(QuestionInterface::class);

        $collection->addResponse('key1', 'value1', $questionMock);
        $collection->addResponse('key1', 'value2', $questionMock);
    }

    public function testGetNonExistentResponse(): void
    {
        $collection = new ResponseCollection();

        $this->assertNull($collection->getResponse('non_existent_key'));
    }

    public function testGetAllResponses(): void
    {
        $collection = new ResponseCollection();
        $questionMock = $this->createMock(QuestionInterface::class);

        $collection->addResponse('key1', 'value1', $questionMock);
        $collection->addResponse('key2', true, $questionMock);

        $allResponses = $collection->all();

        $this->assertCount(2, $allResponses);
        $this->assertSame('value1', $allResponses['key1']);
        $this->assertTrue($allResponses['key2']);
    }
}
