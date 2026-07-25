<?php

namespace BrainGames\Games\Calc;

use function BrainGames\Engine\run;

use const BrainGames\Constants\MAX_NUMBER;
use const BrainGames\Constants\MIN_NUMBER;

const DESCRIPTION = 'What is the result of the expression?';
const OPERATORS = ['+', '-', '*'];

function play(): void
{
    $generateRound = function (): array {
        $a = random_int(MIN_NUMBER, MAX_NUMBER);
        $b = random_int(MIN_NUMBER, MAX_NUMBER);
        $operator = OPERATORS[array_rand(OPERATORS)];

        $question = "$a $operator $b";
        $correctAnswer = (string) match ($operator) {
            '+' => $a + $b,
            '-' => $a - $b,
            '*' => $a * $b,
        };

        return [$question, $correctAnswer];
    };

    run(DESCRIPTION, $generateRound);
}
