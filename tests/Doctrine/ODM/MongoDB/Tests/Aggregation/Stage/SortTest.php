<?php

declare(strict_types=1);

namespace Doctrine\ODM\MongoDB\Tests\Aggregation\Stage;

use Doctrine\ODM\MongoDB\Aggregation\Stage\Sort;
use Doctrine\ODM\MongoDB\Tests\Aggregation\AggregationTestTrait;
use Doctrine\ODM\MongoDB\Tests\BaseTest;

class SortTest extends BaseTest
{
    use AggregationTestTrait;

    /**
     * @param string|array<string, string> $field
     *
     * @dataProvider provideSortOptions
     */
    public function testSortStage(array $expectedSort, $field, ?string $order = null): void
    {
        $sortStage = new Sort($this->getTestAggregationBuilder(), $field, $order);

        $this->assertSame(['$sort' => $expectedSort], $sortStage->getExpression());
    }

    /**
     * @param string|array<string, string> $field
     *
     * @dataProvider provideSortOptions
     */
    public function testSortFromBuilder(array $expectedSort, $field, ?string $order = null): void
    {
        $builder = $this->getTestAggregationBuilder();
        $builder->sort($field, $order);

        $this->assertSame([['$sort' => $expectedSort]], $builder->getPipeline());
    }

    public static function provideSortOptions(): array
    {
        return [
            'singleFieldSeparated' => [
                ['field' => -1],
                'field',
                'desc',
            ],
            'singleFieldCombined' => [
                ['field' => -1],
                ['field' => 'desc'],
            ],
            'multipleFields' => [
                ['field' => -1, 'otherField' => 1],
                ['field' => 'desc', 'otherField' => 'asc'],
            ],
            'sortMeta' => [
                ['field' => ['$meta' => 'textScore'], 'invalidField' => -1],
                ['field' => 'textScore', 'invalidField' => 'nonExistingMetaField'],
            ],
        ];
    }
}
