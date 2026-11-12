<?php

declare(strict_types=1);

namespace Health\Home;

/**
 * One rep as the camera read it: the angle at the joint and how far that angle
 * sat from the drill's own range.
 */
final class Rep
{
    public function __construct(
        public readonly int $index,
        public readonly int $angle,
        public readonly int $drift,
    ) {
    }

    public function counts(int $tolerance): bool
    {
        return $this->drift <= $tolerance;
    }
}
