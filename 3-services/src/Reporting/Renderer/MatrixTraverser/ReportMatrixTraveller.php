<?php

namespace App\Reporting\Renderer\MatrixTraverser;

use App\Reporting\Generated\Cell;
use App\Reporting\Generated\GeneratedReport;

class ReportMatrixTraveller implements \IteratorAggregate
{
    private $report;

    private $line = 0;

    private $column = 0;

    private $cellMatrix = [];

    public function __construct(GeneratedReport $report)
    {
        $this->report = $report;
    }

    /**
     * @return WriteIteration[]
     */
    public function getIterator(): iterable
    {
        foreach ($this->report as $row) {
            foreach ($row as $cell) {
                $this->column = $this->getNextEmptyColumn();
                $this->line = $this->getNextEmptyLine();
                $this->fillBlock($cell);

                yield new WriteIteration($cell, $this->line, $this->column);
            }
            $this->column = 0;
            $this->line++;
        }
    }

    private function getNextEmptyLine(): int
    {
        $line = $this->line;
        while (!empty($this->cellMatrix[$line][$this->column])) {
            $line++;
        }

        return $line;
    }

    private function getNextEmptyColumn(): int
    {
        $column = $this->column;
        while (!empty($this->cellMatrix[$this->line][$column])) {
            $column++;
        }

        return $column;
    }

    private function fillBlock(Cell $cell): void
    {
        for ($i = 0; $i < $cell->getColspan(); $i++) {
            $this->cellMatrix[$this->line][$this->column + $i] = true;
        }
        for ($i = 0; $i < $cell->getRowspan(); $i++) {
            $this->cellMatrix[$this->line + $i][$this->column] = true;
        }
//        if ($cell->getValue() == 11) {
//            dump($this->cellMatrix);
//        }
    }
}
