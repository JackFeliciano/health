<?php

declare(strict_types=1);

namespace Health\Home;

use InvalidArgumentException;

/**
 * A set of reps against one drill. The score is the share of reps that landed
 * inside the tolerance, counted in whole percent, so a set of four with three
 * clean reps scores 75.
 */
final class Session
{
    /** @var list<Rep> */
    private array $reps = [];

    public function __construct(public readonly Drill $drill)
    {
    }

    public function add(Rep $rep): void
    {
        $this->reps[] = $rep;
    }

    /** @return list<Rep> */
    public function reps(): array
    {
        return $this->reps;
    }

    public function counted(): int
    {
        $count = 0;
        foreach ($this->reps as $rep) {
            if ($rep->counts($this->drill->toleranceDegrees)) {
                $count++;
            }
        }

        return $count;
    }

    public function score(): int
    {
        if ($this->reps === []) {
            throw new InvalidArgumentException('A session with no reps has no score.');
        }

        return intdiv($this->counted() * 100, count($this->reps));
    }

    public function complete(): bool
    {
        return $this->counted() >= $this->drill->targetReps;
    }

    /**
     * The mean drift across the set, rounded down. A set of 4 and 8 reads 6.
     */
    public function meanDrift(): int
    {
        if ($this->reps === []) {
            return 0;
        }
        $sum = 0;
        foreach ($this->reps as $rep) {
            $sum += $rep->drift;
        }

        return intdiv($sum, count($this->reps));
    }
}
