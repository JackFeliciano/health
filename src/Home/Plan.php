<?php

declare(strict_types=1);

namespace Health\Home;

/**
 * A day of movement: cardio first, then stretching, then the drills. A plan is
 * complete only when every session in it is complete.
 */
final class Plan
{
    /** @var list<Session> */
    private array $sessions = [];

    public function __construct(public readonly string $day)
    {
    }

    public function add(Session $session): void
    {
        $this->sessions[] = $session;
    }

    /** @return list<Session> */
    public function sessions(): array
    {
        return $this->sessions;
    }

    public function complete(): bool
    {
        if ($this->sessions === []) {
            return false;
        }
        foreach ($this->sessions as $session) {
            if (!$session->complete()) {
                return false;
            }
        }

        return true;
    }

    /**
     * The whole day as one number, the mean of the session scores, rounded down.
     */
    public function score(): int
    {
        $scored = [];
        foreach ($this->sessions as $session) {
            if ($session->reps() !== []) {
                $scored[] = $session->score();
            }
        }
        if ($scored === []) {
            return 0;
        }

        return intdiv(array_sum($scored), count($scored));
    }

    /** @return list<Session> */
    public function pending(): array
    {
        $out = [];
        foreach ($this->sessions as $session) {
            if (!$session->complete()) {
                $out[] = $session;
            }
        }

        return $out;
    }
}
