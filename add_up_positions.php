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

$mysqli = new mysqli($dbhost,$dbname,$dbpass,$dbuser);
if($mysqli->connect_errno){
    echo "Connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;}





if(isset($_POST['AddC']))
{
    $fname = $_POST['first_name'];
    $lname = $_POST['last_name'];
    $positionID = $_POST['positionID'];
    $teamName= $_POST['teamID'];


$coachIDquery = $mysqli->query("SELECT id FROM coaches WHERE first_name ='$fname' AND last_name='$lname'")->fetch_assoc();
$coachID = $coachIDquery['id'];


if(!($stmt = $mysqli->prepare("INSERT INTO `position_coach_team` (`coachID`,`positionID`,`teamID`) VALUES (?,?,?)"))){
  echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
}
if(!($stmt->bind_param("iii",$coachID , $positionID, $teamName))){
  echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
}
if(!$stmt->execute()){
  echo "Execute failed: "  . $stmt->errno . " " . $stmt->error;
} else {
  echo "  Coach position has been added to database.";}



}





else if(isset($_POST['UpdateC']))
{

    $fname = $_POST['first_name'];
    $lname = $_POST['last_name'];
    $positionID = $_POST['positionID'];
    $teamName= $_POST['teamID'];


$coachIDquery = $mysqli->query("SELECT id FROM coaches WHERE first_name ='$fname' AND last_name='$lname'")->fetch_assoc();
$coachID = $coachIDquery['id'];

if(!($updateQ = $mysqli->prepare("UPDATE position_coach_team SET positionID=? WHERE teamID=? AND coachID=?"))){
  echo "Prepare failed: "  . $updateQ->errno . " " . $updateQ->error;
}
if(!($updateQ->bind_param("iii", $positionID,$teamName , $coachID))){
  echo "Bind failed: "  . $updateQ->errno . " " . $updateQ->error;
}
if(!$updateQ->execute()){
  echo "Execute failed: "  . $updateQ->errno . " " . $updateQ->error;
} 
else if($updateQ->affected_rows > 0)
{
  echo "Coach position has been updated!";
}
  else
  {
    echo "No rows affected, please enter the first name and last of current athlete to update.";
  }


}

else if(isset($_POST['AddA']))
{
    $fname = $_POST['first_name'];
    $lname = $_POST['last_name'];
    $positionID = $_POST['positionID'];


$athleteIDquery= $mysqli->query("SELECT id FROM athletes WHERE first_name ='$fname' AND last_name='$lname'")->fetch_assoc();
$athleteID = $athleteIDquery['id'];


if(!($stmt = $mysqli->prepare("INSERT INTO `athlete_position` (`athleteID`,`positionID`) VALUES (?,?)"))){
  echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
}
if(!($stmt->bind_param("ii",$athleteID , $positionID))){
  echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
}
if(!$stmt->execute()){
  echo "Execute failed: "  . $stmt->errno . " " . $stmt->error;
} else {
  echo "  Athlete position has been added to database.";}

}


?>
<p>Navigate back to <a href="positions.php"> positions</a> page.
