<?php

namespace App\Reporting\Generated;

/**
 * Модель сгенерированного отчета
 */
class GeneratedReport implements \IteratorAggregate
{
    /**
     * @var Row[]
     */
    private $rows = [];

    /**
     * @param Row[] $rows
     */
    public function __construct(iterable $rows = [])
    {
        foreach ($rows as $row) {
            $this->addRow($row);
        }
    }

    public function addRow(Row $row)
    {
        $this->rows[] = $row;
    }

    /**
     * @return Row[]
     */
    public function getRows(): iterable
    {
        return $this->rows;
    }

    /**
     * @return Row[]
     */
    public function getIterator()
    {
        return yield from $this->rows;
    }

    /**
     * @param Cell[] $cells
     */
    public function createRow(iterable $cells = []): Row
    {
        return new Row($cells);
    }

    /**
     * @param Cell[] $cells
     */
    public function createAndAddRow(iterable $cells = [])
    {
        $this->addRow($this->createRow($cells));
    }
}
