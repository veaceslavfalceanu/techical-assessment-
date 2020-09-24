<?php

namespace App\Entity;

use App\ValueObject\Point;

class Line
{
    private $pointStart;
    private $pointEnd;

    public function __construct(Point $pointStart, Point $pointEnd)
    {
        $this->pointStart = $pointStart;
        $this->pointEnd = $pointEnd;
    }

    public function getPointStart(): Point
    {
        return $this->pointStart;
    }

    public function setPointStart(Point $pointStart): void
    {
        $this->pointStart = $pointStart;
    }

    public function getPointEnd(): Point
    {
        return $this->pointEnd;
    }

    public function setPointEnd(Point $pointEnd): void
    {
        $this->pointEnd = $pointEnd;
    }

    public function isPointInLine(Point $point)
    {
        if ($point === $this->getPointStart() || $point === $this->getPointEnd()){
            return true;
        }

        return false;
    }

    public function equalPointStart(Point $point)
    {
        return $this->pointStart === $point;
    }

    public function equalPointEnd(Point $point)
    {
        return $this->pointEnd === $point;
    }
}