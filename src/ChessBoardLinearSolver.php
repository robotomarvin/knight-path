<?php declare(strict_types=1);

namespace App;

class ChessBoardLinearSolver extends AChessBoardSolver
{

    private const MIN_SIZE_FOR_BFS_ONLY = 8;


    /**
     * Returns shortest path for knight, does not check special cases and parameters
     *
     * @param Square $from start square
     * @param Square $to final square
     * @return array|Square[]|null path from start square to final square, null if no path exists
     */
    protected function solve(Square $from, Square $to): ?array
    {
        if ($this->size <= self::MIN_SIZE_FOR_BFS_ONLY) {
            return (new ChessBoardBfsSolver($this->size))->getShortestKnightPath($from, $to);
        }

        $path = [];
        $fromRow = $from->getRow();
        $fromColumn = $from->getColumn();

        // until more than 4 diff on one axis, use linear approach
        while(abs($fromRow - $to->getRow()) > 4 || abs($fromColumn - $to->getColumn()) > 4) {
            $rowDiff = $to->getRow() - $fromRow;
            $colDiff = $to->getColumn() - $fromColumn;

            // move by 2 on axis with more difference
            if (abs($rowDiff) > abs($colDiff)) {
                $rowDelta = 2 * ($rowDiff <=> 0);
                $colDelta = 1 * ($colDiff <=> 0);
            } else {
                $rowDelta = 1 * ($rowDiff <=> 0);
                $colDelta = 2 * ($colDiff <=> 0);
            }

            // if one delta is 0 we can choose both directions, decide by border
            if ($rowDelta === 0) {
                $rowDelta = $fromRow === 0 ? 1 : -1;
            }

            if ($colDelta === 0) {
                $colDelta = $fromColumn === 0 ? 1 : -1;
            }

            $fromRow += $rowDelta;
            $fromColumn += $colDelta;
            $path[] = new Square($fromRow, $fromColumn);
        }

        // solve rest by BFS, rest is with small fixed size so its constant complexity
        $fromSquare = end($path) ?: $from;
        $restOfPath = (new ChessBoardBfsSolver($this->size))->getShortestKnightPath($fromSquare, $to);

        return array_merge($path, $restOfPath);
    }

}