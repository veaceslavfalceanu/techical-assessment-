<?php

namespace App\Service;

interface ResponseMessage
{
    const VALID_START_NODE_MSG = 'VALID_START_NODE';
    const INVALID_START_NODE_MSG = 'INVALID_START_NODE';
    const VALID_END_NODE_MSG = 'VALID_END_NODE';
    const INVALID_END_NODE_MSG = 'INVALID_END_NODE';

    const GAME_OVER = 'GAME_OVER';
}