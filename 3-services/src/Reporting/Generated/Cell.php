<?php

namespace App\Reporting\Generated;

class Cell
{
    private $value;

    private $purpose;

    private $colspan;

    private $rowspan;

    private $note;

    /**
     * @param mixed $value
     */
    public function __construct(
        $value = '',
        string $purpose = CellPurpose::SIMPLE,
        int $colspan = 1,
        int $rowspan = 1,
        string $note = null
    ) {
        $this->value = $value;
        $this->purpose = $purpose;
        $this->colspan = $colspan;
        $this->rowspan = $rowspan;
        $this->note = $note;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getPurpose(): string
    {
        return $this->purpose;
    }

    /**
     * @return int
     */
    public function getColspan(): int
    {
        return $this->colspan;
    }

    /**
     * @return int
     */
    public function getRowspan(): int
    {
        return $this->rowspan;
    }

    public function setNote(?string $note): void
    {
        $this->note = $note;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function requiresMerge(): bool
    {
        return $this->colspan > 1 || $this->rowspan > 1;
    }
}
