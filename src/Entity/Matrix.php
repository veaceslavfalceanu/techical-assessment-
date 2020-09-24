<?php

namespace App\Entity;

class Matrix
{
    const MATRIX_DIMENSION = 3;

    private $matrix = [];

    public function initialize()
    {
        foreach (range(0,self::MATRIX_DIMENSION) as $row) {
            foreach (range(0,self::MATRIX_DIMENSION) as $col) {
                $this->matrix[$row][$col] = $col;
            }
        }

        return $this->matrix;
    }
}