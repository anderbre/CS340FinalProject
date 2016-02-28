<?php
//Turn on error reporting
ini_set('display_errors', 'On');
//Connects to the database

//$dbhost = 'oniddb.cws.oregonstate.edu';
//$dbname = 'mcmurroj-db';
//$dbuser = 'mcmurroj-db';
//$dbpass = 'uHM64jmm6DzuW1qr';

$dbhost = 'oniddb.cws.oregonstate.edu';
$dbname = 'anderbre-db';
$dbuser = 'anderbre-db';
$dbpass = 'mkfCwxMsmsXjCDc7';

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

<table>
		<label> Basic Table </label>
		<tr>
			<td>Name</td>
			<td>Age</td>
			<td>Homeworld</td>
		</tr>
<?php
if(!($stmt = $mysqli->prepare("SELECT first_name,last_name FROM athletes"))){
	echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
}

if(!$stmt->execute()){
	echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
if(!$stmt->bind_result($firstName, $lastName)){
	echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
while($stmt->fetch()){
 echo "<tr>\n<td>\n" . $firstName . "\n</td>\n<td>\n" . $lastName . "\n</td>\n<td>\n";
}
$stmt->close();
?>
	</table>



</body>
</html>
