<?php

namespace App\Tests\Reporting\Renderer\MatrixTraverser;

use App\Reporting\Generated\CellPurpose;
use App\Reporting\Generated\GeneratedReport;
use App\Reporting\Renderer\MatrixTraverser\ReportMatrixTraveller;
use PHPUnit\Framework\TestCase;

class ReportMatrixTravellerTest extends TestCase
{
    public function testIterate(): void
    {
        $report = new GeneratedReport();

        // 1 2 2 3
        // 4 5 6 7
        // 4 8 8 9
        // 0 8 8 1

        $iterations = [];

        $row = $report->createRow();
        $report->addRow($row);
        $row->createAndAddCell(1);
        $row->createAndAddCell(2, CellPurpose::SIMPLE, 2);
        $row->createAndAddCell(3);
        $iterations[] = [1, 0, 0];
        $iterations[] = [2, 0, 1];
        $iterations[] = [3, 0, 3];

        $row = $report->createRow();
        $report->addRow($row);
        $row->createAndAddCell(4, CellPurpose::SIMPLE, 1, 2);
        $row->createAndAddCell(5);
        $row->createAndAddCell(6);
        $row->createAndAddCell(7);
        $iterations[] = [4, 1, 0];
        $iterations[] = [5, 1, 1];
        $iterations[] = [6, 1, 2];
        $iterations[] = [7, 1, 3];

        $row = $report->createRow();
        $report->addRow($row);
        $row->createAndAddCell(8, CellPurpose::SIMPLE, 2, 2);
        $row->createAndAddCell(9);
        $iterations[] = [8, 2, 1];
        $iterations[] = [9, 2, 3];

        $row = $report->createRow();
        $report->addRow($row);
        $row->createAndAddCell(0);
        $row->createAndAddCell(1);
        $iterations[] = [0, 3, 0];
        $iterations[] = [1, 3, 2];

        $travellerIterations = [];
        foreach (new ReportMatrixTraveller($report) as $iteration) {
            $travellerIterations[] = [
                $iteration->getCell()->getValue(),
                $iteration->getLine(),
                $iteration->getColumn(),
            ];
        }

//        dump($iterations, $travellerIterations);
//        exit;

        $this->assertSame($iterations, $travellerIterations);
    }

    public function testIterateWithDifferentCellCountPerLine(): void
    {
        $report = new GeneratedReport();

        // 1
        // 4 5 6 7
        // 4 8
        // 0 9 9 1

        $iterations = [];

        $row = $report->createRow();
        $report->addRow($row);
        $row->createAndAddCell(1);
        $iterations[] = [1, 0, 0];

        $row = $report->createRow();
        $report->addRow($row);
        $row->createAndAddCell(4, CellPurpose::SIMPLE, 1, 2);
        $row->createAndAddCell(5);
        $row->createAndAddCell(6);
        $row->createAndAddCell(7);
        $iterations[] = [4, 1, 0];
        $iterations[] = [5, 1, 1];
        $iterations[] = [6, 1, 2];
        $iterations[] = [7, 1, 3];

        $row = $report->createRow();
        $report->addRow($row);
        $row->createAndAddCell(8);
        $iterations[] = [8, 2, 1];

        $row = $report->createRow();
        $report->addRow($row);
        $row->createAndAddCell(0);
        $row->createAndAddCell(9, CellPurpose::SIMPLE, 2);
        $row->createAndAddCell(1);
        $iterations[] = [0, 3, 0];
        $iterations[] = [9, 3, 1];
        $iterations[] = [1, 3, 3];

        $travellerIterations = [];
        foreach (new ReportMatrixTraveller($report) as $iteration) {
            $travellerIterations[] = [
                $iteration->getCell()->getValue(),
                $iteration->getLine(),
                $iteration->getColumn(),
            ];
        }

//        dump($iterations, $travellerIterations);
//        exit;

        $this->assertSame($iterations, $travellerIterations);
    }
}
