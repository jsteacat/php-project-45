<?php

namespace BrainGames\Games\Even;

use function BrainGames\Engine\run;

use const BrainGames\Constants\ANSWER_NO;
use const BrainGames\Constants\ANSWER_YES;
use const BrainGames\Constants\MAX_NUMBER;
use const BrainGames\Constants\MIN_NUMBER;

const DESCRIPTION = 'Answer "' . ANSWER_YES . '" if the number is even, otherwise answer "' . ANSWER_NO . '".';
const EVEN_DIVISOR = 2;

function isEven(int $number): bool
{
    return $number % EVEN_DIVISOR === 0;
}

function play(): void
{
    $generateRound = function (): array {
        $number = random_int(MIN_NUMBER, MAX_NUMBER);
        $question = (string) $number;
        $correctAnswer = isEven($number) ? ANSWER_YES : ANSWER_NO;

        return [$question, $correctAnswer];
    };

    run(DESCRIPTION, $generateRound);
}
