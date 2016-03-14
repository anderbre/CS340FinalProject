<?php
//Turn on error reporting
//Turn on error reporting
ini_set('display_errors', 'On');
//Connects to the database

if (file_exists("brett")){
  $dbhost = 'oniddb.cws.oregonstate.edu';
  $dbname = 'anderbre-db';
  $dbuser = 'anderbre-db';
  $dbpass = 'mkfCwxMsmsXjCDc7';
} else {
  $dbhost = 'oniddb.cws.oregonstate.edu';
  $dbname = 'mcmurroj-db';
  $dbuser = 'mcmurroj-db';
  $dbpass = 'uHM64jmm6DzuW1qr';
}
// prepare the database connection
$mysqli = new mysqli($dbhost,$dbname,$dbpass,$dbuser);
if($mysqli->connect_errno){
    echo "Connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;}

    $name = $_POST['team_name'];
    $age = $_POST['agegroup'];
    $level = $_POST['teamlevel'];
    $teamid = $_POST['team_id'];

//echo "<pre>"; print_r($_POST); echo "</pre>";

// next, execute the query based on if it is an update or an insert.

// if type is set, then this is an update request.
if(isset($_POST['type'])) // updting if type is set
{
  // update team
  if(!($updateQ = $mysqli->prepare("UPDATE teams SET name=?, age_group=?, level=? WHERE id=? "))){
    echo "Prepare failed: "  . $updateQ->errno . " " . $updateQ->error;
  }
  if(!($updateQ->bind_param("ssii",$name, $age, $level, $teamid))){
    echo "Bind failed: "  . $updateQ->errno . " " . $updateQ->error;
  }
  if(!$updateQ->execute()){
    echo "Execute failed: "  . $updateQ->errno . " " . $updateQ->error;
  }
  else if($updateQ->affected_rows > 0)
  {
    echo "Team updated!";
  }
  else
  {
    echo "No rows affected, please enter the first name and last of current athlete to update.";
  }
} else
{ // type is not set, so we are adding a team.
  if(!$mysqli || $mysqli->connect_errno){
    echo "Connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
  }

  if(!($stmt = $mysqli->prepare("INSERT INTO `teams` (`name`,`age_group`, `level`) VALUES (?,?,?)"))){
    echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
  }
  if(!($stmt->bind_param("ssi",$name , $age, $level))){
    echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
  }
  if(!$stmt->execute()){
    echo "Execute failed: "  . $stmt->errno . " " . $stmt->error;
  } else {
    echo "Team has been added to database.";
  }
}


?>

<p>Navigate back to <a href="teams.php">Teams</a> page.</p>
