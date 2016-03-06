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

if(!$mysqli || $mysqli->connect_errno){
  echo "Connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
  }

if(!($stmt = $mysqli->prepare("INSERT INTO `coaches` (`first_name`,`last_name`) VALUES (?,?)"))){
  echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
}
if(!($stmt->bind_param("ss",$fname , $lname))){
  echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
}
if(!$stmt->execute()){
  echo "Execute failed: "  . $stmt->errno . " " . $stmt->error;
} else {
  echo "Coach has been added to database.";}

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

if(!($updateQ = $mysqli->prepare("UPDATE athletes SET teamID=?, age=? WHERE first_name=?  AND last_name=? "))){
  echo "Prepare failed: "  . $updateQ->errno . " " . $updateQ->error;
}
if(!($updateQ->bind_param("iiss",$teamName , $age, $fname, $lname))){
  echo "Bind failed: "  . $updateQ->errno . " " . $updateQ->error;
}
if(!$updateQ->execute()){
  echo "Execute failed: "  . $updateQ->errno . " " . $updateQ->error;
} 
else if($updateQ->affected_rows > 0)
{
  echo "Athlete age and team have been updated!";
}
  else
  {
    echo "No rows affected, please enter the first name and last of current athlete to update.";
  }


}

else if(isset($_POST['AddA']))
{
    
if(!$mysqli || $mysqli->connect_errno){
  echo "Connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
  }

if(!($stmt = $mysqli->prepare("INSERT INTO `athletes` (`first_name`,`last_name`,`age`,`teamID`) VALUES (?,?,?,?)"))){
  echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
}
if(!($stmt->bind_param("ssii",$fname , $lname, $age, $teamName))){
  echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
}
if(!$stmt->execute()){
  echo "Execute failed: "  . $stmt->errno . " " . $stmt->error;
} else {
  echo "Athlete has been added to database.";}
}

else if(isset($_POST['UpdateA']))
{

if(!($updateQ = $mysqli->prepare("UPDATE athletes SET teamID=?, age=? WHERE first_name=?  AND last_name=? "))){
  echo "Prepare failed: "  . $updateQ->errno . " " . $updateQ->error;
}
if(!($updateQ->bind_param("iiss",$teamName , $age, $fname, $lname))){
  echo "Bind failed: "  . $updateQ->errno . " " . $updateQ->error;
}
if(!$updateQ->execute()){
  echo "Execute failed: "  . $updateQ->errno . " " . $updateQ->error;
} 
else if($updateQ->affected_rows > 0)
{
  echo "Athlete age and team have been updated!";
}
  else
  {
    echo "No rows affected, please enter the first name and last of current athlete to update.";
  }


}


?>
<p>Navigate back to <a href="positions.php"> positions</a> page.
