<?php declare(strict_types=1);

namespace App;

abstract class AChessBoardSolver
{

    public const DEFAULT_SIZE = 8;

    protected const ROW_INDEX = 'row';
    protected const COLUMN_INDEX = 'col';

    protected const KNIGHT_MOVES = [
        [self::ROW_INDEX =>  1, self::COLUMN_INDEX =>  2],
        [self::ROW_INDEX =>  2, self::COLUMN_INDEX =>  1],
        [self::ROW_INDEX => -1, self::COLUMN_INDEX =>  2],
        [self::ROW_INDEX => -2, self::COLUMN_INDEX =>  1],
        [self::ROW_INDEX => -1, self::COLUMN_INDEX => -2],
        [self::ROW_INDEX => -2, self::COLUMN_INDEX => -1],
        [self::ROW_INDEX =>  1, self::COLUMN_INDEX => -2],
        [self::ROW_INDEX =>  2, self::COLUMN_INDEX => -1],
    ];

    protected int $size;

    public function __construct($size = self::DEFAULT_SIZE)
    {
        $this->size = $size;
    }

    /**
     * Returns shortest path for knight
     *
     * @param Square $from start square
     * @param Square $to final square
     * @return array|Square[]|null path from start square to final square, null if no path exists
     */
    public function getShortestKnightPath(Square $from, Square $to): ?array
    {
        $this->checkBoundaries($from);
        $this->checkBoundaries($to);

        // special case, when from is same os to
        if ($from->getRow() === $to->getRow() && $from->getColumn() === $to->getColumn()) {
            return [];
        }

        return $this->solve($from, $to);
    }


    /**
     * Returns shortest path for knight, does not check special cases and parameters
     *
     * @param Square $from start square
     * @param Square $to final square
     * @return array|Square[]|null path from start square to final square, null if no path exists
     */
    abstract protected function solve(Square $from, Square $to): ?array;

    /**
     * Checks if square if inside board boundaries
     * @param Square $square
     */
    private function checkBoundaries(Square $square): void
    {
        $row = $square->getRow();
        $column = $square->getColumn();

        if ($row < 0 || $row >= $this->size) {
            throw new \InvalidArgumentException('Row is out of bounds');
        }

        if ($column < 0 || $column >= $this->size) {
            throw new \InvalidArgumentException('Column is out of bounds');
        }
    }


}