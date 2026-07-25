<?php

namespace BrainGames\Games\Prime;

use function BrainGames\Engine\run;

use const BrainGames\Constants\ANSWER_NO;
use const BrainGames\Constants\ANSWER_YES;
use const BrainGames\Constants\MAX_NUMBER;
use const BrainGames\Constants\MIN_NUMBER;

const DESCRIPTION = 'Answer "' . ANSWER_YES . '" if given number is prime. Otherwise answer "' . ANSWER_NO . '".';
const MIN_PRIME_NUMBER = 2;

function isPrime(int $number): bool
{
    if ($number < MIN_PRIME_NUMBER) {
        return false;
    }

    for ($divisor = MIN_PRIME_NUMBER; $divisor <= sqrt($number); $divisor++) {
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
        $correctAnswer = isPrime($number) ? ANSWER_YES : ANSWER_NO;

        return [$question, $correctAnswer];
    };

    run(DESCRIPTION, $generateRound);
}
