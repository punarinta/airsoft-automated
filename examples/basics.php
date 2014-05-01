<?php

$t1 = microtime(true);

include '../Core.php';

// init The Core
$ai = new Core;

// setup randomness
$random = rand(1, 100);

// Create an object
$newTeam = $ai->getService('Team')->create(array
(
    'name'      => 'AT Dryshi #' . $random,
    'full_name' => 'Airsoft Team "Dryshi 2000" #' . $random,
    'region_id' => 1,
));

// Let's see what we've created
print_r($newTeam);


// OK, let's list all the teams
$teams = $ai->getService('Team')->findAll();
print_r($teams);


// Let's get more advanced and render the complete DDB with all the teams
$ai->getService('Partial')->render('teams-ddb', array
(
    'region_id' => 1,
));


// Let's find our team that we've created above
$team = $ai->getService('Team')->findById($newTeam->getId());
print_r($team);


// Let's change the name
$team->setFullName('Srakobolnaja komanda Dryshi 3000');

// and sync back to DB
$ai->getService('Team')->update($team);

// It's in the DB. I hope you believe me. Otherwise do check it. ;)

// Remove the team from DB
$ai->getService('Team')->delete($team);

// or you might have called this way if you like
// $ai->getService('Team')->delete($team->getId());


// measure time
echo "\nExecution time: " . number_format(1000 * (microtime(true) - $t1), 3) . " ms\n";

echo "\n\n";

// clean DB if you want
// $ai->getDb()->execute('TRUNCATE TABLE team');
