<?php

declare(strict_types=1);

namespace Health\Home;

/**
 * One movement the camera can score. The drill counts what the body must show
 * and the tolerance is how far a joint may drift before the frame is marked.
 */
final class Drill
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $kind,
        public readonly int $targetReps,
        public readonly int $toleranceDegrees,
    ) {
    }
}
