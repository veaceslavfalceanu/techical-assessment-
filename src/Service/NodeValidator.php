<?php

namespace App\Service;

use App\ValueObject\Point;

class NodeValidator implements ResponseMessage
{
    private $responseGenerator;
    private $sessionManager;

    public function __construct(ResponseGenerator $responseGenerator, SessionManager $sessionManager)
    {
        $this->responseGenerator = $responseGenerator;
        $this->sessionManager = $sessionManager;
    }

    public function processCurrentNode(Point $point)
    {
        if (!$this->isEitherEndOfThePath($point)) {
            $this->responseGenerator->invalidStartNode(
                "Player 2",
                "You must start on either end of the path!"
            );
        }

        if (!$this->sessionManager->getFirstNode()) {
            $this->sessionManager->setFirstNode($point);
            $this->sessionManager->setMove(1);

            return $this->responseGenerator->validStartNode("Player 1");
        } else {
            if (!$this->sessionManager->getLastNode()) {
                $this->sessionManager->setLastNode($point);
                $this->sessionManager->setMove(2);
            }
        }

        $startPoint = $this->sessionManager->getFirstNode();
        $endPoint = $this->sessionManager->getLastNode();

        if ($this->isValidLine($startPoint, $endPoint)) {

            if (null == $this->sessionManager->getEndOfThePath()) {
                $this->sessionManager->setEndOfThePath($startPoint, $endPoint);
            }

            if ($this->sessionManager->getEndOfThePath()) {
                $this->setAllowedPathEnds($endPoint);
            }

            return $this->responseGenerator->validEndNode(
                $startPoint,
                $endPoint
            );
        }
    }

    public function isValidLine(Point $startPoint, Point $endPoint): bool
    {
        if ($startPoint->getX() == $endPoint->getX()) {
            $this->verticalLine($startPoint, $endPoint);

            return true;
        }

        if ($startPoint->getY() == $endPoint->getY()){
            $this->horizontalLine($startPoint, $endPoint);

            return true;
        }

        if (($startPoint->getX() + $startPoint->getY()) == ($endPoint->getX() + $endPoint->getY())) {
            $this->diagonalRightLine($startPoint, $endPoint);

            return true;
        }

        if (($startPoint->getX() - $startPoint->getY()) == ($endPoint->getX() - $endPoint->getY())) {
            $this->diagonalLeftLine($startPoint, $endPoint);

            return true;
        }
    }

    public function verticalLine(Point $startPoint, Point $endPoint)
    {
        if ($startPoint->getY() > $endPoint->getY()) {
            for ($i = $startPoint->getY() - 1; $i < $endPoint->getY(); $i--) {
                $this->sessionManager->markMatrixCoordinate(
                    new Point($endPoint->getX(), $i)
                );
            }
        }

        if ($startPoint->getY() < $endPoint->getY()) {
            for ($i = $startPoint->getY() + 1; $i < $endPoint->getY(); $i++) {
                $this->sessionManager->markMatrixCoordinate(
                    new Point($endPoint->getX(), $i)
                );
            }
        }

        $this->updateSessionManager($startPoint, $endPoint);
    }

    public function horizontalLine(Point $startPoint, Point $endPoint)
    {
        if ($startPoint->getX() < $endPoint->getX()) {
            for ($i = $startPoint->getX() + 1; $i < $endPoint->getX(); $i++) {
                $this->sessionManager->markMatrixCoordinate(
                    new Point($i, $endPoint->getY())
                );
            }
        }

        if ($startPoint->getX() > $endPoint->getX()) {
            for ($i = $startPoint->getX() - 1; $i < $endPoint->getX(); $i--) {
                $this->sessionManager->markMatrixCoordinate(
                    new Point($i, $endPoint->getY())
                );
            }
        }

        $this->updateSessionManager($startPoint, $endPoint);
    }

    public function diagonalRightLine(Point $startPoint, Point $endPoint)
    {
        if ($startPoint->getY() < $endPoint->getY()) {
            $numberOfPoints = ($endPoint->getY() - $startPoint->getY()) - 1;

            for ($i = 1; $i <= $numberOfPoints; $i++) {
                $this->sessionManager->markMatrixCoordinate(
                    new Point($startPoint->getX() - $i, $startPoint->getY() + $i)
                );
            }
        }

        if ($startPoint->getY() > $endPoint->getY()) {
            $numberOfPoints = ($startPoint->getY() - $endPoint->getY()) - 1;

            for ($i = 1; $i <= $numberOfPoints; $i++) {
                $this->sessionManager->markMatrixCoordinate(
                    new Point($startPoint->getX() + 1, $startPoint->getY() - $i)
                );
            }
        }

        $this->updateSessionManager($startPoint, $endPoint);
    }

    public function diagonalLeftLine(Point $startPoint, Point $endPoint)
    {
        if ($startPoint->getY() < $endPoint->getY()) {
            $numberOfPoints = ($endPoint->getY() - $startPoint->getY()) - 1;

            for ($i = 1; $i <= $numberOfPoints; $i++) {
                $this->sessionManager->markMatrixCoordinate(
                    new Point($startPoint->getX() + $i, $startPoint->getY() + $i)
                );
            }
        }

        if ($startPoint->getY() > $endPoint->getY()) {
            $numberOfPoints = ($startPoint->getY() - $endPoint->getY()) - 1;

            for ($i = 1; $i <= $numberOfPoints; $i++) {
                $this->sessionManager->markMatrixCoordinate(
                    new Point($startPoint->getX() - 1, $startPoint->getY() - $i)
                );
            }
        }

        $this->updateSessionManager($startPoint, $endPoint);
    }

    public function updateSessionManager(Point $startPoint, Point $endPoint)
    {
        $this->sessionManager->markMatrixCoordinate($startPoint);
        $this->sessionManager->markMatrixCoordinate($endPoint);
        $this->sessionManager->setFirstNode(null);
        $this->sessionManager->setLastNode(null);
    }

    public function isEitherEndOfThePath(Point $startPoint)
    {
        if (null == $this->sessionManager->getEndOfThePath()) {
            // first move when start the game
            return true;
        }

        if ($this->sessionManager->getEndOfThePath()->isPointInLine($startPoint)) {
            return true;
        }

        return false;
    }

    public function setAllowedPathEnds(Point $point)
    {
        if ($this->sessionManager->getEndOfThePath()->equalPointStart($point)) {
            $this->sessionManager->setEndOfThePath(
                $this->sessionManager->getEndOfThePath()->getPointEnd(),
                $point
            );
        }

        if ($this->sessionManager->getEndOfThePath()->equalPointEnd($point)) {
            $this->sessionManager->setEndOfThePath(
                $this->sessionManager->getEndOfThePath()->getPointStart(),
                $point
            );
        }
    }

    public function isEndOfTheLine(int $move)
    {
        if ($move !== 2) {
            return false;
        }

        return true;
    }
}