<?php

declare(strict_types=1);

namespace Health\App;

use Health\Home\Plan;
use Health\Home\Sample;
use Health\Home\Session;

final class Views
{
    public function __construct(private readonly string $base)
    {
    }

    private function h(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    public function header(string $title): string
    {
        $base = $this->h($this->base);

        return <<<HTML
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{$this->h($title)}</title>
<base href="{$base}">
<link rel="stylesheet" href="assets/site.css">
</head>
<body>
<main>
HTML;
    }

    public function footer(): string
    {
        return "</main>\n</body>\n</html>\n";
    }

    public function notFound(string $path): string
    {
        return $this->header('Not found')
            . '<h1>Not found</h1>' . "\n"
            . '<p class="stage">' . $this->h($path) . ' is not a route here.</p>' . "\n"
            . $this->footer();
    }

    public function front(Sample $sample, string $today): string
    {
        $plan = $sample->plan();
        $out = $this->header('Health');
        $out .= '<h1>Movement scored by the camera</h1>' . "\n";
        $out .= '<p class="lede">Cardio, stretching and drills. The camera reads the joint angle, the drill counts the rep, and the day is scored on what landed inside tolerance.</p>' . "\n";
        $out .= '<p class="stage">' . $this->h($plan->day) . ', as of ' . $this->h($today) . '.</p>' . "\n";

        $out .= '<h2>Today</h2>' . "\n";
        $out .= '<dl>';
        $out .= '<dt>Plan complete</dt><dd>' . ($plan->complete() ? 'yes' : 'no, ' . count($plan->pending()) . ' sessions pending') . '</dd>';
        $out .= '<dt>Day score</dt><dd>' . $plan->score() . ' out of 100</dd>';
        $out .= '</dl>' . "\n";

        $out .= '<h2>Sessions</h2>' . "\n";
        $out .= '<table class="tiers">' . "\n";
        $out .= '<thead><tr><th>Drill</th><th>Kind</th><th>Reps counted</th><th>Target</th><th>Mean drift</th><th>Score</th></tr></thead>' . "\n";
        $out .= '<tbody>' . "\n";
        foreach ($plan->sessions() as $session) {
            $out .= $this->row($session);
        }
        $out .= '</tbody>' . "\n" . '</table>' . "\n";

        $out .= $this->drift($plan);
        $out .= $this->footer();

        return $out;
    }

    private function row(Session $session): string
    {
        $drill = $session->drill;

        return '<tr><td>' . $this->h($drill->name) . '</td>'
            . '<td>' . $this->h($drill->kind) . '</td>'
            . '<td>' . $session->counted() . ' of ' . count($session->reps()) . '</td>'
            . '<td>' . $drill->targetReps . '</td>'
            . '<td>' . $session->meanDrift() . ' degrees</td>'
            . '<td><span class="badge ' . ($session->complete() ? 'on' : 'off') . '">'
            . $session->score() . '</span></td></tr>' . "\n";
    }

    private function drift(Plan $plan): string
    {
        $out = '<h2>Where the set drifted</h2>' . "\n";
        $out .= '<div class="cards">' . "\n";
        foreach ($plan->sessions() as $session) {
            $out .= '<article class="card">' . "\n";
            $out .= '<h3>' . $this->h($session->drill->name) . '</h3>' . "\n";
            $out .= '<p>' . count($session->reps()) . ' reps read, ' . $session->counted() . ' inside ' . $session->drill->toleranceDegrees . ' degrees of tolerance.</p>' . "\n";
            $out .= '<dl>';
            foreach ($session->reps() as $rep) {
                $out .= '<dt>Rep ' . $rep->index . '</dt><dd>' . $rep->angle . ' degrees, drift ' . $rep->drift . '</dd>';
            }
            $out .= '</dl>' . "\n";
            $out .= '</article>' . "\n";
        }
        $out .= '</div>' . "\n";

        return $out;
    }
}
