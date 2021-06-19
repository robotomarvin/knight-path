<?php declare(strict_types=1);

namespace App;

class Square
{

    private int $row;
    private int $column;

    public function __construct(int $row, int $column)
    {
        $this->row = $row;
        $this->column = $column;
    }


    public function getRow(): int
    {
        return $this->row;
    }


    public function getColumn(): int
    {
        return $this->column;
    }

}