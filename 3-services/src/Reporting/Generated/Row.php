<?php

namespace App\Reporting\Generated;

class Row implements \IteratorAggregate
{
    /**
     * @var Cell[]
     */
    private $cells = [];

    /**
     * @param Cell[] $cells
     */
    public function __construct(iterable $cells = [])
    {
        foreach ($cells as $cell) {
            $this->addCell($cell);
        }
    }

    public function addCell(Cell $cell)
    {
        $this->cells[] = $cell;
    }

    /**
     * @return Cell[]
     */
    public function getCells(): iterable
    {
        return $this->cells;
    }

    /**
     * @return Cell[]
     */
    public function getIterator()
    {
        return yield from $this->cells;
    }

    public function createCell(
        $value = '',
        string $purpose = CellPurpose::SIMPLE,
        int $colspan = 1,
        int $rowspan = 1,
        string $note = null
    ): Cell {
        return new Cell($value, $purpose, $colspan, $rowspan, $note);
    }

    public function createAndAddCell(
        $value = '',
        string $purpose = CellPurpose::SIMPLE,
        int $colspan = 1,
        int $rowspan = 1,
        string $note = null
    ) {
        $this->addCell($this->createCell($value, $purpose, $colspan, $rowspan, $note));
    }
}
