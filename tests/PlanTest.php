<?php

declare(strict_types=1);

namespace Health\Tests;

use Health\Home\Drill;
use Health\Home\Plan;
use Health\Home\Rep;
use Health\Home\Sample;
use Health\Home\Session;

return [
    ['a rep inside tolerance counts', static function (): void {
        Assert::true((new Rep(1, 90, 5))->counts(5));
        Assert::false((new Rep(1, 90, 6))->counts(5));
    }],
    ['the score is counted reps over total', static function (): void {
        $session = new Session(new Drill('D-1', 'March', 'cardio', 3, 8));
        $session->add(new Rep(1, 90, 4));
        $session->add(new Rep(2, 90, 9));
        $session->add(new Rep(3, 90, 6));
        $session->add(new Rep(4, 90, 12));
        Assert::same(2, $session->counted());
        Assert::same(50, $session->score());
    }],
    ['an empty session has no score', static function (): void {
        $session = new Session(new Drill('D-1', 'March', 'cardio', 3, 8));
        try {
            $session->score();
            Assert::true(false, 'an empty set was scored');
        } catch (\InvalidArgumentException) {
            Assert::true(true);
        }
    }],
    ['a session completes on counted reps only', static function (): void {
        $session = new Session(new Drill('D-1', 'March', 'cardio', 2, 8));
        $session->add(new Rep(1, 90, 4));
        $session->add(new Rep(2, 90, 20));
        Assert::false($session->complete());
        $session->add(new Rep(3, 90, 7));
        Assert::true($session->complete());
    }],
    ['the mean drift rounds down', static function (): void {
        $session = new Session(new Drill('D-1', 'March', 'cardio', 2, 8));
        $session->add(new Rep(1, 90, 4));
        $session->add(new Rep(2, 90, 8));
        Assert::same(6, $session->meanDrift());
    }],
    ['an empty plan is not complete', static function (): void {
        Assert::false((new Plan('Monday'))->complete());
    }],
    ['a plan completes when every session does', static function (): void {
        $plan = new Plan('Monday');
        $session = new Session(new Drill('D-1', 'March', 'cardio', 1, 8));
        $session->add(new Rep(1, 90, 4));
        $plan->add($session);
        Assert::true($plan->complete());
    }],
    ['the day score is the mean of session scores', static function (): void {
        $plan = new Plan('Monday');
        $first = new Session(new Drill('D-1', 'March', 'cardio', 2, 8));
        $first->add(new Rep(1, 90, 4));
        $first->add(new Rep(2, 90, 4));
        $second = new Session(new Drill('D-2', 'Reach', 'stretch', 2, 5));
        $second->add(new Rep(1, 70, 3));
        $second->add(new Rep(2, 70, 9));
        $plan->add($first);
        $plan->add($second);
        Assert::same(75, $plan->score());
    }],
    ['pending lists the sessions not yet complete', static function (): void {
        $plan = new Plan('Monday');
        $done = new Session(new Drill('D-1', 'March', 'cardio', 1, 8));
        $done->add(new Rep(1, 90, 4));
        $todo = new Session(new Drill('D-2', 'Reach', 'stretch', 5, 5));
        $todo->add(new Rep(1, 70, 3));
        $plan->add($done);
        $plan->add($todo);
        Assert::count(1, $plan->pending());
        Assert::same('Reach', $plan->pending()[0]->drill->name);
    }],
    ['the sample plan leaves one session pending', static function (): void {
        $plan = Sample::plan();
        Assert::count(1, $plan->pending());
        Assert::false($plan->complete());
    }],
];
