<?php

namespace BrainGames\Games\Progression;

use function BrainGames\Engine\run;

use const BrainGames\Engine\MAX_NUMBER;
use const BrainGames\Engine\MIN_NUMBER;

const DESCRIPTION = 'What number is missing in the progression?';
const LENGTH = 10;
const MIN_STEP = 1;
const MAX_STEP = 10;

function makeProgression(int $start, int $step, int $length): array
{
    $progression = [];

    for ($index = 0; $index < $length; $index++) {
        $progression[] = $start + $index * $step;
    }

    return $progression;
}

function play(): void
{
    $generateRound = function (): array {
        $start = random_int(MIN_NUMBER, MAX_NUMBER);
        $step = random_int(MIN_STEP, MAX_STEP);
        $hiddenIndex = random_int(0, LENGTH - 1);

        $progression = makeProgression($start, $step, LENGTH);
        $correctAnswer = (string) $progression[$hiddenIndex];
        $progression[$hiddenIndex] = '..';
        $question = implode(' ', $progression);

        return [$question, $correctAnswer];
    };

    run(DESCRIPTION, $generateRound);
}
