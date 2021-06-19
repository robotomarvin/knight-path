<?php declare(strict_types=1);

use App\ChessBoardBfsSolver;
use App\ChessBoardLinearSolver;
use App\Square;
use PHPUnit\Framework\TestCase;

class ChessBoardShortestNightPathTest extends TestCase
{

    public function testAdjecentCornerPosition()
    {
        $this->checkPath(
            0, 0,
            0, 1,
            3
        );
    }

    public function testSamePosition()
    {
        $this->checkPath(
            4, 4,
            4, 4,
            0
        );
    }


    public function testLargeBoard()
    {
        $this->checkPath(
            0, 0,
            499, 400,
            301,
            500
        );
    }


    public function testNoSolution()
    {
        $chessBoard = new ChessBoardBfsSolver(3);
        $from = new Square(1, 1);
        $to = new Square(0, 0);

        $path = $chessBoard->getShortestKnightPath($from, $to);

        self::assertNull($path, 'Impossible path exists');
    }

    private function checkPath(
        int $fromRow, int $fromColumn,
        int $toRow, int $toColumn,
        int $expectedLength,
        int $boardSize = ChessBoardBfsSolver::DEFAULT_SIZE
    ) {
        $chessBoard = new ChessBoardLinearSolver($boardSize);
        // $chessBoard = new ChessBoardBfsSolver($boardSize);
        $from = new Square($fromRow, $fromColumn);
        $to = new Square($toRow, $toColumn);

        $path = $chessBoard->getShortestKnightPath($from, $to);

        self::assertNotNull($path, 'Path not found');
        self::assertCount($expectedLength, $path);
        array_unshift($path, $from);

        $this->checkPathContinuity($path);
    }


    /**
     * @param array|Square[] $path
     */
    private function checkPathContinuity(array $path): void
    {
        $size = count($path);
        for ($i = 1; $i < $size; $i++) {
            $from = $path[$i-1];
            $to = $path[$i];

            $rowDelta = $from->getRow() - $to->getRow();
            $colDelta = $from->getColumn() - $to->getColumn();

            // rowDelta * colDelta can give -2 or +2 only if its valid knight move
            self::assertEquals(2, abs($rowDelta * $colDelta), 'Path contains invalid move ' . ($i-1) . ' - ' . $i);
        }
    }

}
