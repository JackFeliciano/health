<?php

declare(strict_types=1);

namespace Health\Home;

final class Sample
{
    public static function plan(): Plan
    {
        $plan = new Plan('Monday');

        $cardio = new Session(new Drill('DR-01', 'March in place', 'cardio', 3, 8));
        $cardio->add(new Rep(1, 92, 4));
        $cardio->add(new Rep(2, 89, 6));
        $cardio->add(new Rep(3, 94, 7));
        $cardio->add(new Rep(4, 91, 12));
        $plan->add($cardio);

        $stretch = new Session(new Drill('DR-02', 'Standing hamstring reach', 'stretch', 2, 5));
        $stretch->add(new Rep(1, 71, 3));
        $stretch->add(new Rep(2, 68, 4));
        $plan->add($stretch);

        $drill = new Session(new Drill('DR-03', 'Sit to stand', 'drill', 5, 6));
        $drill->add(new Rep(1, 88, 2));
        $drill->add(new Rep(2, 86, 5));
        $drill->add(new Rep(3, 90, 3));
        $drill->add(new Rep(4, 84, 9));
        $drill->add(new Rep(5, 87, 11));
        $plan->add($drill);

        return $plan;
    }
}
