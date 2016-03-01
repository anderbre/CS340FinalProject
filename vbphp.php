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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Final Project: Volleyball Database</title>
    <link rel="stylesheet" href="stylesheet.css" type="text/css">
</head>

<body>
<h1>Final Project: Volleyball Database</h1>
<br>
<br>
<h3>About VollyBall</h3>

<p>This is some general information about Vollyball.
	Here we can detail positions types, relationship contraints (i.e coaches can coach more than one team...)
</p>


<h4>Links to different page:</h4>
<br>
	<ul>
		<li><a href="athletes.php">List of Athletes</a></li>
		<li></li>
		<li></li>
		<li></li>
	</ul>

<table class="data_display">
		<label> Athletes </label>
		<tr class="heading">
			<th>First Name</th>
			<th>Last Name</th>
			<th>Age</th>
      <th>Team</th>
		</tr>
<?php

// create the sql query
if(!($stmt = $mysqli->prepare("SELECT first_name,last_name,age,teamID FROM athletes"))){
	echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
}
// execute the sql query
if(!$stmt->execute()){
	echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
// bind the results to variables
if(!$stmt->bind_result($firstName, $lastName, $age, $team)){
	echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
// operate for each returned row. Inline the html.
while($stmt->fetch()){
 echo "<tr>\n<td>" . $firstName . "</td>\n<td>" . $lastName . "</td>\n<td>" . $age . "</td><td>". $team . "</td>\n</tr>\n";
}
// close out the sql query.
$stmt->close();
?>
	</table>



</body>
</html>
