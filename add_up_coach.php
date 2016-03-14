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
// prepare the database connection object.
$mysqli = new mysqli($dbhost,$dbname,$dbpass,$dbuser);
if($mysqli->connect_errno){
    echo "Connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;}

    $fname = $_POST['first_name'];
    $lname = $_POST['last_name'];
    $coachid = $_POST['coach_id'];


// what type of submission did we get? If a type object is present, then we are updating.
if(isset($_POST['type'])) // updting if type is set
{
  // update coach
  if(!($updateQ = $mysqli->prepare("UPDATE coaches SET first_name=?, last_name=? WHERE id=? "))){
    echo "Prepare failed: "  . $updateQ->errno . " " . $updateQ->error;
  }
  if(!($updateQ->bind_param("ssi",$fname, $lname, $coachid))){
    echo "Bind failed: "  . $updateQ->errno . " " . $updateQ->error;
  }
  if(!$updateQ->execute()){
    echo "Execute failed: "  . $updateQ->errno . " " . $updateQ->error;
  }
  // affected rows will be positive if the query succeded in updating.
  else if($updateQ->affected_rows > 0)
  {
    echo "Coach updated!";
  }
  else
  {
    echo "No rows affected, please enter the first name and last of current athlete to update.";
  }
} else
{ // no type object, so we are adding a coach
  if(!$mysqli || $mysqli->connect_errno){
    echo "Connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
  }
  // prepare the insert query
  if(!($stmt = $mysqli->prepare("INSERT INTO `coaches` (`first_name`,`last_name`) VALUES (?,?)"))){
    echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
  }
  if(!($stmt->bind_param("ss",$fname , $lname))){
    echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
  }
  if(!$stmt->execute()){
    echo "Execute failed: "  . $stmt->errno . " " . $stmt->error;
  } else {
    echo "Coach has been added to database.";
  }
  echo "<p>Continue to the <a href=\"positions.php\">Positions</a> page to add the coach to a team.</p>";
}


?>

<p>Navigate back to <a href="coaches.php">Coaches</a> page.</p>
