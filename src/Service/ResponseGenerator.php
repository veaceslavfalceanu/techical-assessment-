<?php

namespace App\Service;

use App\ValueObject\Point;

class ResponseGenerator
{
    public function validStartNode(string $player)
    {
        return [
            "msg" => "VALID_START_NODE",
            "body" => [
                "newLine" => null,
                "heading" => $player,
                "message" => "Select a second node to complete the line."
            ]
        ];
    }

    public function invalidStartNode(string $player, string $message)
    {
        return [
            "msg" => "INVALID_START_NODE",
            "body" => [
                "newLine" => null,
                "heading" => $player,
                "message" => $message
            ]
        ];
    }

    public function validEndNode(Point $start, Point $end): array
    {
        return [
            "msg" => "VALID_END_NODE",
            "body" => [
                "newLine" => [
                    "start" => [
                        "x" => $start->getX(),
                        "y" => $start->getY()
                    ],
                    "end" => [
                        "x" => $end->getX(),
                        "y" => $end->getY()
                    ],
                ],
                "heading" => "Player 2",
                "message" => null
            ]
        ];
    }

    public function invalidEndNode(string $message, string $player)
    {
        return [
            "msg" => "INVALID_END_NODE",
            "body" => [
                "newLine" => null,
                "heading" => "Player 2",
                "message" => "Invalid move!"
            ]
        ];
    }
}