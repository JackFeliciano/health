# Health

## What the project is

Health is a training plan that a camera reads. The plan trains cardio, stretching, mobility and agility. A drill carries a joint, a target angle in degrees, a tolerance in degrees and a target repetition count.

The camera reads the angle at that joint while a repetition runs. A repetition counts when that angle lands inside the drill's tolerance. A session holds the repetitions against a drill. The session counts the repetitions that counted. The plan scores a session as the share of repetitions that counted, in whole percent. A session completes when the count reaches the drill's target repetition count.

Sample builds the demo plan. The front controller renders that plan as HTML on the page route. The probe route answers JSON with the plan's state.

## How to run it

The commands below run from the repository root. The built-in server serves the site through the demo entry file:

```sh
php -S 127.0.0.1:8110 -t showroom showroom/index.php
```

The router reads the HEALTH_BASE environment variable and strips that base from the request path. The nginx location block serves the site under /health/. The variable takes this value:

```sh
HEALTH_BASE=/health
```

The hand-written runner runs the suite:

```sh
php tests/run.php
```

## What the routes answer

The page route is /. The front controller returns the HTML page for the plan.

The probe route is /status. The route answers JSON with ok, sessions, pending and score. The Plan class scores the whole plan and lists the sessions still pending. Those results fill the score and pending fields.

The probe route avoids the name health. The site's own base is /health/. A route with the name health would return the front page. The router strips the base before it matches a route.

## How the layout is arranged

- autoload.php loads the classes.
- src/Home/ holds the model.
  - Drill.php holds a drill. The drill carries an id, a name, a kind, a joint, a target angle in degrees, a tolerance in degrees and a target repetition count.
  - Rep.php holds a repetition. The repetition carries a joint angle in degrees. The counts method takes a tolerance and answers whether the angle sits within it.
  - Session.php holds a drill and its repetitions. The class provides count, score, complete and meanDrift. The count method counts the repetitions that counted. The score method returns the share in whole percent. The complete method answers whether the count reached the drill's target.
  - Plan.php holds a day and its sessions. The class scores the whole plan and lists the sessions still pending.
  - Sample.php builds the demo plan.
- src/App/ holds the router, the front controller and the HTML.
- showroom/ holds the demo entry file and the stylesheet.
- tests/ holds the hand-written runner, the assertion helper and the test file.
- deploy/ holds the systemd unit and the nginx location block.

## The state of the suite

The suite runs 16 checks. This command runs them:

```sh
php tests/run.php
```

## Licence

The repository uses the Mozilla Public License 2.0. The licence allows anyone to use, modify and distribute the code. The licence allows commercial use. The licence allows combining the code with files under other terms in a larger work. The licence requires that a modified file remain under the same licence.
