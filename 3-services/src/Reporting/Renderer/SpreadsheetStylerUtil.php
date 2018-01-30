<?php

namespace App\Reporting\Renderer;

use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Comment;
use PhpOffice\PhpSpreadsheet\Exception;

class SpreadsheetStylerUtil
{
    public static function applyStylesFromArray(Cell $cell, array $styles): void
    {
        $mergeRange = $cell->getMergeRange();
        if ($mergeRange) {
            $cell->getWorksheet()->getStyle($mergeRange)->applyFromArray($styles, false);
        } else {
            $cell->getStyle()->applyFromArray($styles, false);
        }
    }

    public static function applyNoteStyle(Cell $cell, array $styles): void
    {
        $comment = self::getComment($cell);

        if ($comment) {
            $notes = $comment
                ->getText()
                ->getRichTextElements();

            foreach ($notes as $note) {
                $note->getFont()->applyFromArray($styles);
            }
        }
    }

    /**
     * Get comment for cell.
     *
     * @param Cell $cell
     *
     * @throws Exception
     *
     * @return Comment|bool
     */
    private static function getComment(Cell $cell)
    {
        $pCellCoordinate = $cell->getCoordinate();
        // Uppercase coordinate
        $pCellCoordinate = strtoupper($pCellCoordinate);

        if (strpos($pCellCoordinate, ':') !== false || strpos($pCellCoordinate, ',') !== false) {
            throw new Exception('Cell coordinate string can not be a range of cells.');
        } elseif (strpos($pCellCoordinate, '$') !== false) {
            throw new Exception('Cell coordinate string must not be absolute.');
        } elseif ($pCellCoordinate == '') {
            throw new Exception('Cell coordinate can not be zero-length string.');
        }

        $comments = $cell->getWorksheet()->getComments();
        // Check if we already have a comment for this cell.
        if (isset($comments[$pCellCoordinate])) {
            return $comments[$pCellCoordinate];
        }

        return false;
    }
}
