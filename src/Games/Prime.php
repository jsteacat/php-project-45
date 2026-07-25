<?php

namespace BrainGames\Games\Prime;

use function BrainGames\Engine\run;

use const BrainGames\Engine\MAX_NUMBER;
use const BrainGames\Engine\MIN_NUMBER;

const DESCRIPTION = 'Answer "yes" if given number is prime. Otherwise answer "no".';

function isPrime(int $number): bool
{
    if ($number < 2) {
        return false;
    }

    for ($divisor = 2; $divisor <= sqrt($number); $divisor++) {
        if ($number % $divisor === 0) {
            return false;
        }
    }

    return true;
}

function play(): void
{
    $generateRound = function (): array {
        $number = random_int(MIN_NUMBER, MAX_NUMBER);
        $question = (string) $number;
        $correctAnswer = isPrime($number) ? 'yes' : 'no';

        return [$question, $correctAnswer];
    };

    run(DESCRIPTION, $generateRound);
}
