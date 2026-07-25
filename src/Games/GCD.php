<?php

namespace BrainGames\Games\GCD;

use function BrainGames\Engine\run;

use const BrainGames\Engine\MAX_NUMBER;
use const BrainGames\Engine\MIN_NUMBER;

const DESCRIPTION = 'Find the greatest common divisor of given numbers.';

function findGCD(int $number1, int $number2): int
{
    while ($number2 !== 0) {
        $temp = $number2;
        $number2 = $number1 % $number2;
        $number1 = $temp;
    }
    return $number1;
}

function play(): void
{
    $generateRound = function (): array {
        $number1 = random_int(MIN_NUMBER, MAX_NUMBER);
        $number2 = random_int(MIN_NUMBER, MAX_NUMBER);
        $question = "{$number1} {$number2}";
        $correctAnswer = (string) findGCD($number1, $number2);

        return [$question, $correctAnswer];
    };

    run(DESCRIPTION, $generateRound);
}
