# Roadmap

The project holds a model under src/Home/. The model covers drills, repetitions, sessions and a plan. src/App/ holds the router, the front controller and the HTML. showroom/ holds the demo entry file and the stylesheet. tests/ holds a suite of 16 checks. deploy/ holds the systemd unit and the nginx location block. A contributor could add any capability from the list below.

1. The sessions live only in memory. A contributor could add a store that saves sessions and reads them back. The work would touch src/Home/Session.php, src/Home/Plan.php and a new store class.
2. The demo takes its angles from Sample. A contributor could add a live camera reader that turns each captured frame into a repetition angle. The work would touch showroom/ and src/Home/Rep.php.
3. The /status route reports the whole plan. A contributor could add a route that answers the details of a single session, including meanDrift. The work would touch src/App/ and src/Home/Session.php.
4. Plan holds a single day. A contributor could add a week that groups plans by day and scores the whole week. The work would touch src/Home/Plan.php and src/Home/Sample.php.
5. Sample fixes the drills in code. A contributor could add a form that creates a drill with a joint, a target angle, a tolerance and a target repetition count. The work would touch src/App/ and src/Home/Drill.php.
