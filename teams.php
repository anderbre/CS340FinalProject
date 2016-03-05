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


  <ul class="NavBar">
    <li class="navItem"><a class="navlink" href="vbphp.php">Home</a></li>
    <li class="navItem"><a class="navlink" href="athletes.php">Athletes</a></li>
    <li class="navItem"><a class="navlink"  href="coaches.php">Coaches</a></li>
    <li class="navItem"><a class="active navlink"  href="teams.php">Teams</a></li>
    <li class="navItem"><a class="navlink"  href="positions.php">Positions</a></li>
  </ul>


<br>

<h1>All about athletes</h1>
<p> Remember the following rules apply to athletes:</p>
<ul>
	<li>Athletes can only belong to one team.</li>
	<li>Athletes can have multiple positions.</li>
</ul>
<h3>Table 1: Athlete name, team, and number of positions held </h3>

<div>
	<table>
		<tr>
			<th> First Name </th>
			<th> Last Name </th>
			<th> Age </th>
			<th> Team Name </th>
			<th> Age Group </th>
			<th> Count Positions </th>
		</tr>
<?php
if(!($stmt = $mysqli->prepare("SELECT a.first_name, a.last_name, a.age, t.name, t.age_group, COUNT(p.type) AS Positions_Held FROM athletes a INNER JOIN teams t ON a.teamID = t.id INNER JOIN
athlete_position ap ON ap.athleteID = a.id INNER JOIN
positions p ON p.id = ap.positionID
GROUP BY a.first_name, a.last_name, a.age, t.name, t.age_group")))
{
	echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
}

if(!$stmt->execute()){
	echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
if(!$stmt->bind_result($fname, $lname, $age, $tname, $ageGroup, $countType)){
	echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
while($stmt->fetch()){
 echo "<tr>\n<td>\n" . $fname . "\n</td>\n<td>\n" . $lname . "\n</td>\n<td>\n" . $age . "\n</td>\n<td>"  . $tname . "\n</td>\n<td>" . $ageGroup . "\n</td>\n<td>" . $countType . "\n</td>\n</tr>";
}
$stmt->close();
?>
	</table>
</div>

<h3>Table 2: All positions held by each athlete </h3>


<div>
	<table>
		<tr>
			<th> First Name </th>
			<th> Last Name </th>
			<th> Position Type </th>
		</tr>
<?php
if(!($stmt = $mysqli->prepare("SELECT a.first_name, a.last_name, p.type FROM athletes a INNER JOIN athlete_position ap ON
ap.athleteID = a.id INNER JOIN positions p ON
p.id = ap.positionID ")))
{
	echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
}

if(!$stmt->execute()){
	echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
if(!$stmt->bind_result($fname, $lname, $type)){
	echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
while($stmt->fetch()){
 echo "<tr>\n<td>\n" . $fname . "\n</td>\n<td>\n" . $lname . "\n</td>\n<td>\n" . $type . "\n</td>\n</tr>";
}
$stmt->close();
?>
	</table>
</div>
<br>
<h3>Update or Add Athlete</h3>
<p>**You may update athlete age and/or team</p>
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
			<select name="Homeworld">
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
