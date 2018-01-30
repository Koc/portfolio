<?php

namespace App\Tests\Reporting\Report\Basic;

use App\Entity\Price\Commodity;
use App\Entity\Price\Item;
use App\Entity\Price\Report;
use App\Entity\Price\ReportLine;
use App\Reporting\Generated\Cell;
use App\Reporting\Generated\CellPurpose;
use App\Reporting\Report\Basic\BasicGenerator;
use App\Reporting\Report\Basic\BasicReportSource;
use App\Reporting\Source\ReportSource;
use PHPUnit\Framework\TestCase;

class BasicGeneratorTest extends TestCase
{
    /**
     * @expectedException \App\Reporting\Generator\Exception\UnsupportedReportSourceException
     */
    public function testGenerateWithInvalidSource()
    {
        $generator = new BasicGenerator();
        $generator->generate($this->createMock(ReportSource::class));
    }

    public function testGenerate()
    {
        $actualDate = '2018-08-02';
        $baseDate = '2018-08-01';

        $report = new Report();
        $report->setName('Тестовый отчет');
        $reportLines = [];
        $items = [];

        $commodity1 = new Commodity();
        self::setProperty($commodity1, 'id', 1);
        $commodity1->setName('Пшеница');

        $reportLine1 = new ReportLine();
        $reportLine1->setCommodity($commodity1);
        $reportLines[] = $reportLine1;

        $commodity2 = new Commodity();
        self::setProperty($commodity2, 'id', 2);
        $commodity2->setName('Овес');

        $reportLine2 = new ReportLine();
        $reportLine2->setCommodity($commodity2);
        $reportLines[] = $reportLine2;

        $item = new Item();
        $item->setCommodity($commodity1);
        $item->setMinPrice(200);
        $item->setAvgPrice(250);
        $item->setMaxPrice(300);
        $items[1][$actualDate] = $item;

        $item = new Item();
        $item->setCommodity($commodity1);
        $item->setMinPrice(100);
        $item->setAvgPrice(120);
        $item->setMaxPrice(220);
        $items[1][$baseDate] = $item;

        $item = new Item();
        $item->setCommodity($commodity1);
        $item->setMinPrice(50);
        $item->setAvgPrice(60);
        $item->setMaxPrice(100);
        $items[2][$actualDate] = $item;

        // допустим, прошлых показателей не было
        $items[2][$baseDate] = null;

        $reportSource = new BasicReportSource(
            $report,
            $reportLines,
            $items,
            new \DateTime($baseDate),
            new \DateTime($actualDate)
        );

        $generator = new BasicGenerator();
        $generatedReport = $generator->generate($reportSource);
        $rows = $generatedReport->getRows();

        // заголовок + подписи колонок + пшеница + овес
        $this->assertCount(4, $rows);

        // заголовок таблицы
        $this->assertEquals([new Cell('Тестовый отчет', CellPurpose::TABLE_CAPTION, 8)], $rows[0]->getCells());

        // заголовки колонок
        $this->assertEquals(
            [
                new Cell(' ', CellPurpose::COLUMN_CAPTION, 2),
                new Cell('мин.', CellPurpose::COLUMN_CAPTION),
                new Cell('изм.', CellPurpose::COLUMN_CAPTION),
                new Cell('макс.', CellPurpose::COLUMN_CAPTION),
                new Cell('изм.', CellPurpose::COLUMN_CAPTION),
                new Cell('сред.', CellPurpose::COLUMN_CAPTION),
                new Cell('изм.', CellPurpose::COLUMN_CAPTION),
            ],
            $rows[1]->getCells()
        );

        // пошла первая строка
        $this->assertEquals(
            [
                new Cell('Пшеница', CellPurpose::ROW_CAPTION, 2),
                new Cell(200),
                new Cell(100),
                new Cell(300),
                new Cell(80),
                new Cell(250),
                new Cell(130),
            ],
            $rows[2]->getCells()
        );

        // пошла вторая строка
        $this->assertEquals(
            [
                new Cell('Овес', CellPurpose::ROW_CAPTION, 2),
                new Cell(50),
                new Cell(50),
                new Cell(100),
                new Cell(100),
                new Cell(60),
                new Cell(60),
            ],
            $rows[3]->getCells()
        );
    }

    private static function setProperty($object, string $property, $value): void
    {
        $reflection = new \ReflectionProperty($object, $property);
        $reflection->setAccessible(true);
        $reflection->setValue($object, $value);
    }
}
