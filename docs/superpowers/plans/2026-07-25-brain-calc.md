# Brain Calc Implementation Plan

> **For agentic workers:** Implement task-by-task in this session (level 2 — no subagents). Steps use checkbox (`- [ ]`) syntax for tracking. Do not commit unless the user asks.

**Goal:** Add the Calculator game (`brain-calc`) reusing `Engine::run`, with shared number range constants.

**Architecture:** Games only supply description + round generator `[question, correctAnswer]`. Engine owns greeting, 3 rounds, answer check, win/lose messages. `MIN_NUMBER`/`MAX_NUMBER` live in Engine and are used by Even and Calc.

**Tech Stack:** PHP 8+, Composer, `wp-cli/php-cli-tools`, Makefile, asciinema for README demo.

## Global Constraints

- Rounds: exactly 3 (`ROUNDS_COUNT` in Engine)
- Operators: `+`, `-`, `*` only; choose via `match` (not `switch`)
- Answer comparison: strict string equality
- Description copy: `What is the result of the expression?`
- Number range: `1`–`100`, shared via Engine constants
- Autoload games via `composer.json` `autoload.files` (same pattern as Even)

## File Structure

| File | Role |
|------|------|
| `src/Engine.php` | Shared loop + `MIN_NUMBER` / `MAX_NUMBER` |
| `src/Games/Even.php` | Import range from Engine |
| `src/Games/Calc.php` | Calc game: description + generateRound + play |
| `bin/brain-calc` | Executable entry |
| `composer.json` | bin + autoload |
| `Makefile` | `brain-calc` target |
| `README.md` | asciinema demo link |

---

### Task 1: Shared range in Engine + Even refactor

**Files:**
- Modify: `src/Engine.php`
- Modify: `src/Games/Even.php`

**Interfaces:**
- Produces: `BrainGames\Engine\MIN_NUMBER`, `BrainGames\Engine\MAX_NUMBER` (ints)

- [ ] **Step 1: Add range constants to Engine**

```php
const ROUNDS_COUNT = 3;
const MIN_NUMBER = 1;
const MAX_NUMBER = 100;
```

- [ ] **Step 2: Update Even to use Engine constants**

Remove local `MIN_NUMBER` / `MAX_NUMBER`. Add:

```php
use function BrainGames\Engine\run;
use const BrainGames\Engine\MIN_NUMBER;
use const BrainGames\Engine\MAX_NUMBER;
```

Keep `random_int(MIN_NUMBER, MAX_NUMBER)`.

- [ ] **Step 3: Verify Even still loads**

Run: `php -r 'require "vendor/autoload.php"; echo BrainGames\Engine\MIN_NUMBER, "-", BrainGames\Engine\MAX_NUMBER, PHP_EOL;'`
Expected: `1-100`

---

### Task 2: Calc game + bin entry

**Files:**
- Create: `src/Games/Calc.php`
- Create: `bin/brain-calc`
- Modify: `composer.json`
- Modify: `Makefile`

**Interfaces:**
- Consumes: `Engine\run`, `MIN_NUMBER`, `MAX_NUMBER`, `ROUNDS_COUNT` (via run)
- Produces: `BrainGames\Games\Calc\play(): void`

- [ ] **Step 1: Create `src/Games/Calc.php`**

```php
<?php

namespace BrainGames\Games\Calc;

use function BrainGames\Engine\run;
use const BrainGames\Engine\MIN_NUMBER;
use const BrainGames\Engine\MAX_NUMBER;

const DESCRIPTION = 'What is the result of the expression?';
const OPERATORS = ['+', '-', '*'];

function play(): void
{
    $generateRound = function (): array {
        $a = random_int(MIN_NUMBER, MAX_NUMBER);
        $b = random_int(MIN_NUMBER, MAX_NUMBER);
        $operator = OPERATORS[array_rand(OPERATORS)];

        $question = "{$a} {$operator} {$b}";
        $correctAnswer = (string) match ($operator) {
            '+' => $a + $b,
            '-' => $a - $b,
            '*' => $a * $b,
        };

        return [$question, $correctAnswer];
    };

    run(DESCRIPTION, $generateRound);
}
```

- [ ] **Step 2: Create `bin/brain-calc`**

```php
#!/usr/bin/env php
<?php

require_once __DIR__ . '/../vendor/autoload.php';

use function BrainGames\Games\Calc\play;

play();
```

Then: `chmod +x bin/brain-calc`

- [ ] **Step 3: Wire composer + Makefile**

In `composer.json`:
- Add `"src/Games/Calc.php"` to `autoload.files`
- Add `"bin/brain-calc"` to `bin`

In `Makefile` add:

```makefile
brain-calc:
	./bin/brain-calc
```

- [ ] **Step 4: Dump autoload and validate**

Run:
```bash
composer dump-autoload
composer validate
```
Expected: `composer.json is valid`

- [ ] **Step 5: Smoke-test win and fail**

Win (pipe 3 correct answers — compute manually or use a fixed seed if needed). Practical check with expect or manual:

```bash
printf 'Tirion\n14\n14\n175\n' | ./bin/brain-calc
```

Note: questions are random — for automated smoke, prefer a short PHP one-liner that imports Calc logic, or interactive/expect script. Minimum: run once interactively / with expect showing correct dialogue shape; separately feed wrong answer and confirm fail message:

```
'...' is wrong answer ;(. Correct answer was '...'.
Let's try again, Name!
```

- [ ] **Step 6: Lint**

Run: `make lint`
Expected: no PSR-12 errors on `src` and `bin`

---

### Task 3: README asciinema

**Files:**
- Modify: `README.md`
- Optional: `docs/brain-calc.cast`, `docs/demo-brain-calc.exp` (if project records casts locally before upload)

- [ ] **Step 1: Record demo** covering welcome, name, expression question, at least one Correct!, and one wrong-answer ending (or two separate casts / one cast with both outcomes if already the project style for Even).

- [ ] **Step 2: Upload to asciinema.org and put badge/link under Demo in `README.md`** (alongside or instead of existing Even demo — keep existing Even link; add Calc section/link).

---

## Self-Review

1. **Spec coverage:** bin, Calc logic, composer bin, validate, Makefile, Engine reuse, Games dir, 3 rounds, `match`, README — all mapped.
2. **Placeholders:** none.
3. **Consistency:** `play()` / `run(string, callable)` / answer as string — matches Even + Engine.
