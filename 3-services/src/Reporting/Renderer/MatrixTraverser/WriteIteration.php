<?php

namespace App\Reporting\Renderer\MatrixTraverser;

use App\Reporting\Generated\Cell;

class WriteIteration
{
    private $cell;

    private $line;

    private $column;

    public function __construct(Cell $cell, int $line, int $column)
    {
        $this->cell = $cell;
        $this->line = $line;
        $this->column = $column;
    }

    public function getCell(): Cell
    {
        return $this->cell;
    }

    public function getLine(): int
    {
        return $this->line;
    }

    public function getColumn(): int
    {
        return $this->column;
    }
}
