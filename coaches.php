<?php
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
    echo "Connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Final Project: Volleyball Database</title>
    <link rel="stylesheet" href="stylesheet.css" type="text/css">
</head>
<html>
<body>

<h1>All about coaches</h1>
<p> Remember the following rules apply to coaches:</p>
<ul>
	<li>Coaches can belong to more than one team.</li>
	<li>coaches can have multiple positions, but only one per team.</li>
</ul>
<h3>Table 1: Coach name, team, and position held </h3>

<div>
	<table>
		<tr class="heading">
			<th> First Name </th>
			<th> Last Name </th>
			<th> Team Name </th>
      <th> Age Group </th>
      <th> Level </th>
      <th> Position </th>
		</tr>
<?php
if(!($stmt = $mysqli->prepare("SELECT coaches.first_name, coaches.last_name, teams.name, teams.age_group, teams.level, positions.type
FROM coaches JOIN team_coach_setup ON coaches.id = team_coach_setup.coachID
JOIN teams ON team_coach_setup.teamID = teams.id
JOIN position_coach_team ON position_coach_team.coachID = coaches.id
JOIN positions ON positions.id = position_coach_team.positionID
ORDER BY teams.age_group DESC")))
{
	echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
}

if(!$stmt->execute()){
	echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
if(!$stmt->bind_result($fname, $lname, $tname, $ageGroup, $level, $pos)){
	echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
while($stmt->fetch()){
 echo "<tr>\n<td>\n" . $fname . "\n</td>\n<td>\n" . $lname . "\n</td>\n<td>\n" . $tname . "\n</td>\n<td>" . $ageGroup. "\n</td>\n<td>" . $level . "\n</td>\n<td>" . $pos . "\n</td>\n</tr>";
}
$stmt->close();
?>
	</table>
</div>

<br>
<h3>Update or Add Coach</h3>

<form method="post" action="addAthlete.php">

		<fieldset>
			<legend>Athlete Name</legend>
			<p>First Name: <input type="text" name="first_name" /></p>
			<p>Last Name: <input type="text" name="last_name" /></p>
		</fieldset>

		<fieldset>
			<legend>Athlete Age</legend>
			<p>Age: <input type="text" name="age" /></p>
		</fieldset>

		<fieldset>
			<legend>Athlete's Team</legend>
			<select name="teamID">
<?php
if(!($stmt = $mysqli->prepare("SELECT id, name FROM teams"))){
	echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
}

if(!$stmt->execute()){
	echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
if(!$stmt->bind_result($id, $teamname)){
	echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
while($stmt->fetch()){
	echo '<option value=" '. $id . ' "> ' . $teamname . '</option>\n';
}
$stmt->close();
?>

		</select>
		</fieldset>
		<input type="submit" name="Add" value="Add Athlete" />
		<input type="submit" name="Update" value="Update Athlete" />
	</form>


</body>
</html>
