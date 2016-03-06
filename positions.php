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
    <title>Positions</title>
    <link rel="stylesheet" href="stylesheet.css" type="text/css">
</head>
<html>
<body>


  <ul class="NavBar">
    <li class="navItem"><a class="navlink" href="vbphp.php">Home</a></li>
    <li class="navItem"><a class="navlink" href="athletes.php">Athletes</a></li>
    <li class="navItem"><a class="navlink"  href="coaches.php">Coaches</a></li>
    <li class="navItem"><a class="navlink"  href="teams.php">Teams</a></li>
    <li class="navItem"><a class="active navlink"  href="positions.php">Positions</a></li>
  </ul>


<br>

<h1>Current positions of player's and coaches</h1>
<p> Remember the following applies to positions:</p>
<ul>
	<li>Positions belong to both coaches and teams.</li>
	<li>Athletes can have more than one position.</li>
	<li>Coaches can coach multiple teams, with the same or different position type.</li>
	<li>Coaches <i>should</i> not different positions of the same team.</li>
</ul>
<h3>Table 1: Coach positions and team by position type </h3>

<div>
	<table>
		<tr class="heading">
			<th> First Name </th>
			<th> Last Name </th>
			<th> Team Name </th>
			<th> Position Type </th>
		</tr>
<?php
if(!($stmt = $mysqli->prepare("SELECT c.first_name, c.last_name, t.name, p.type FROM coaches c Left Join position_coach_team pct ON
pct.coachID = c.id Left Join teams t on t.id = pct.teamID Left Join positions p ON p.id=pct.positionID Order By p.type desc")))
{
	echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
}

if(!$stmt->execute()){
	echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
if(!$stmt->bind_result($fname, $lname, $tname, $ptype)){
	echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
while($stmt->fetch()){
 echo "<tr>\n<td>\n" . $fname . "\n</td>\n<td>\n" . $lname . "\n</td>\n<td>\n" . $tname . "\n</td>\n<td>"  . $ptype . "\n</td>\n</tr>";
}
$stmt->close();
?>
	</table>
</div>

<h3>Table 2: All positions held by each athlete, ordered by team</h3>


<div>
	<table>
		<tr class="heading">
			<th> First Name </th>
			<th> Last Name </th>
			<th> Team Name </th>
			<th> Position Type </th>
		</tr>
<?php
if(!($stmt = $mysqli->prepare("SELECT a.first_name, a.last_name, t.name, p.type FROM athletes a Left JOIN athlete_position ap ON
ap.athleteID = a.id Left JOIN positions p ON
p.id = ap.positionID Left Join teams t ON a.teamID=t.id Order By t.name asc")))
{
	echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
}

if(!$stmt->execute()){
	echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
if(!$stmt->bind_result($fname, $lname,$tname, $type)){
	echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
while($stmt->fetch()){
 echo "<tr>\n<td>\n" . $fname . "\n</td>\n<td>\n" . $lname . "\n</td>\n<td>\n" . $tname . "\n</td>\n<td>\n" . $type . "\n</td>\n</tr>";
}
$stmt->close();
?>
	</table>
</div>
<br>


<div class="formHeader">
<h3>Add or Update Coach Position</h3>
<p>*Remeber a coach <i>should</i> not have more than one position per team.</p>
<p>**To update, enter valid first and last name of pre-existing coach.</p>
<form method="post" action="add_up_positions.php">

		<fieldset>
			<legend>Coach Name</legend>
			<p>First Name: <input type="text" name="first_name" /></p>
			<p>Last Name: <input type="text" name="last_name" /></p>
		</fieldset>

		<fieldset>
			<legend>Position</legend>
			<select name="positionID">
<?php
if(!($stmt = $mysqli->prepare("SELECT id, type FROM positions WHERE positions.type = 'Head Coach' OR positions.type = 'Assistant Coach' "))){
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



		<fieldset>
			<legend>Team</legend>
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

		<input type="submit" name="AddC" value="Add Coach Position" />
		<input type="submit" name="UpdateC" value="Update Coach Position" />
	</form>
</div>




<br>
<div class="formHeader">
<h3>Add or Update Athlete Position</h3>
<p>*Remeber a coach <i>should</i> not have more than one position per team.</p>
<p>**To update, enter valid first and last name of pre-existing coach.</p>
<form method="post" action="add_up_positions.php">

		<fieldset>
			<legend>Coach Name</legend>
			<p>First Name: <input type="text" name="first_name" /></p>
			<p>Last Name: <input type="text" name="last_name" /></p>
		</fieldset>

		<fieldset>
			<legend>Position</legend>
			<select name="teamID">
<?php
if(!($stmt = $mysqli->prepare("SELECT id, type FROM positions WHERE type NOT IN ('Head Coach','Assistant Coach')"))){
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



		<fieldset>
			<legend>Team</legend>
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

		<input type="submit" name="AddA" value="Add Athlete Position" />
		<input type="submit" name="UpdateA" value="Update Coach Position" />
	</form>

</div>



<br>
<h3>Table 3: Number of positions held by an athlete</h3>
<div>
	<table>
		<tr class="heading">
			<th> First Name </th>
			<th> Last Name </th>
			<th> Positions Held </th>
		</tr>
<?php
if(!($stmt = $mysqli->prepare("SELECT a.first_name, a.last_name, COUNT(p.type) AS Positions_Held FROM athletes a LEFT JOIN teams t ON a.teamID = t.id LEFT JOIN
athlete_position ap ON ap.athleteID = a.id LEFT JOIN 
positions p ON p.id = ap.positionID 
GROUP BY a.first_name, a.last_name ORDER BY a.last_name asc")))
{
	echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
}

if(!$stmt->execute()){
	echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
if(!$stmt->bind_result($fname, $lname, $countEm)){
	echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
while($stmt->fetch()){
 echo "<tr>\n<td>\n" . $fname . "\n</td>\n<td>\n" . $lname . "\n</td>\n<td>\n" . $countEm . "\n</td>\n</tr>";
}
$stmt->close();
?>
	</table>
</div>

<br><br>
<br>
<br>
<footer> Final Project by Brett Anderson and Joseph McMurrough</footer>
<br>

</body>
</html>
