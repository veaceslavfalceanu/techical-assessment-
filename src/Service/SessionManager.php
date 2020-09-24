<?php

namespace App\Service;

use App\Entity\Line;
use App\Entity\Matrix;
use App\ValueObject\Point;
use http\Env\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionManager
{
    const FIRST_NODE = 'FIRST_NODE';
    const LAST_NODE = 'LAST_NODE';
    const CURRENT_PLAYER_MOVE = 'PLAYER_MOVE';
    const END_OF_THE_PATH = 'END_OF_THE_PATH';

    private $session;
    private $matrix;

    public function __construct(SessionInterface $session, Matrix $matrix)
    {
        $this->session = $session;
        $this->matrix = $matrix;
    }

    public function setMatrix()
    {
        $this->session->set('matrix', $this->matrix->initialize());
    }

    public function getMatrix()
    {
        return $this->session
            ->get('matrix')
        ;
    }

    public function setFirstNode(?Point $point)
    {
        $this->session->set(self::FIRST_NODE, $point);
    }

    public function getFirstNode()
    {
        return $this->session->get(self::FIRST_NODE);
    }

    public function setLastNode(?Point $point)
    {
        $this->session->set(self::LAST_NODE, $point);
    }

    public function getLastNode()
    {
        return $this->session->get(self::LAST_NODE);
    }

    public function setMove(int $move)
    {
        $this->session->set(self::CURRENT_PLAYER_MOVE, $move);
    }

    public function getMove()
    {
        return $this->session->get(self::CURRENT_PLAYER_MOVE);
    }

    public function isFirstMove(bool $isFirstMove)
    {
        $this->session->set('isFirstMove', $isFirstMove);
    }

    public function setEndOfThePath(Point $oneEnd, Point $anotherEnd)
    {
        $this->session->set(self::END_OF_THE_PATH, new Line($oneEnd, $anotherEnd));
    }

    public function getEndOfThePath()
    {
        return $this->session->get(self::END_OF_THE_PATH);
    }

    public function resetSession()
    {
        $this->session->invalidate();
    }

    public function startSession()
    {
        $this->session->start();
    }

    public function markMatrixCoordinate(Point $point)
    {
        $currentMatrix = $this->getMatrix();

        $currentMatrix[$point->getX()][$point->getY()] = 'x';

        $this->session->set('matrix', $currentMatrix);
    }

    public function clearSession()
    {
        //$this->session->invalidate();
        $this->session->clear();
    }

    public function all()
    {
        return $this->session->all();
    }

    public function getSessionId()
    {
        return $this->session->getId();
    }

    public function sessionStart()
    {
        $this->session->start();
    }
}