<?php declare(strict_types=1);

namespace App;

use SplQueue;

class ChessBoardBfsSolver extends AChessBoardSolver
{

    /**
     * Returns shortest path for knight, does not check special cases and parameters
     *
     * @param Square $from start square
     * @param Square $to final square
     * @return array|Square[]|null path from start square to final square, null if no path exists
     */
    public function solve(Square $from, Square $to): ?array
    {
        $board = $this->getBfsFilledBoard($from, $to);

        // can happen in 2x2 and 3x3 board
        if ($board === NULL) {
            return NULL;
        }

        return $this->backtrackSolutionFromBoard($to, $board);
    }


    /**
     * Fills board by numeric representation of length of shortest path from fixed square to others using BFS
     * Filling is terminated by reaching final square
     *
     * @param Square $from start square for filled, marked with 0
     * @param Square $to final square marked with length of shortest path from start to this square
     * @return array|null board filled with lengths from start square
     */
    private function getBfsFilledBoard(Square $from, Square $to): ?array
    {
        // init board and queue with entry data
        $board = [];
        $board[$from->getRow()] = [];
        $board[$from->getRow()][$from->getColumn()] = 0;
        $queue = new SplQueue();
        $queue->enqueue([self::ROW_INDEX => $from->getRow(), self::COLUMN_INDEX => $from->getColumn()]);

        while ($queue->count() > 0) {
            $first = $queue->dequeue();
            [self::ROW_INDEX => $row, self::COLUMN_INDEX => $col] = $first;
            $length = $board[$row][$col];

            // explore all moves and add them to queue
            foreach (self::KNIGHT_MOVES as $move) {
                $newRow = $row + $move[self::ROW_INDEX];
                $newColumn = $col + $move[self::COLUMN_INDEX];

                // check if new square is in board
                if ($newRow < 0 || $newRow >= $this->size || $newColumn < 0 || $newColumn >= $this->size) {
                    continue;
                }

                // check if square is still not occupied
                if (isset($board[$newRow][$newColumn])) {
                    continue;
                }

                if (!isset($board[$newRow])) {
                    $board[$newRow] = [];
                }
                $board[$newRow][$newColumn] = $length + 1;

                // found solution
                if ($to->getRow() === $newRow && $to->getColumn() === $newColumn) {
                    return $board;
                }

                $queue->enqueue([self::ROW_INDEX => $newRow, self::COLUMN_INDEX => $newColumn]);
            }
        }

        return NULL;
    }


    /**
     * Find path using backtracking from final square
     *
     * @param Square $to final square
     * @param array $board board filled by BFS
     * @return Square[] path from start square to final square
     */
    private function backtrackSolutionFromBoard(Square $to, array $board): array
    {
        $solution = new \SplDoublyLinkedList();
        $solution->unshift($to);
        $length = $board[$to->getRow()][$to->getColumn()];

        while ($length > 1) {
            $square = $solution[0];
            $length--;

            // find square with one smaller length from last backtracked square
            foreach (self::KNIGHT_MOVES as $move) {
                $newRow = $square->getRow() + $move[self::ROW_INDEX];
                $newColumn = $square->getColumn() + $move[self::COLUMN_INDEX];

                if (($board[$newRow][$newColumn] ?? -1) === $length) {
                    $solution->unshift(new Square($newRow, $newColumn));
                    break;
                }
            }
        }

        return iterator_to_array($solution);
    }

}