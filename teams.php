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
// prepare the database connection object.
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

<h1>All about Teams</h1>
<p> Teams have names, age groups and a level of the team. Top team is 1, second best is 2...</p>
<p> The team age group and level should be unique, but do not have to be. Team names must be unique, because duplicating
  a team name is pretty lame.</p>

<div>
	<table id="team_table">
		<tr class="heading">
			<th> Team Name </th>
			<th> Age Group </th>
			<th> Level </th>
      <th> Players </th>
		</tr>
<?php
// prepare the query to select the teams, including a count of all athletes on the team.
if(!($stmt = $mysqli->prepare("SELECT teams.name, teams.age_group, teams.level,  COUNT(athletes.id) AS players FROM teams
LEFT JOIN athletes ON athletes.teamID = teams.id
GROUP BY teams.name
ORDER BY teams.age_group DESC, teams.level ASC")))
{
	echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
}

if(!$stmt->execute()){
	echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
if(!$stmt->bind_result($tname, $tage, $tlevel, $playercount)){
	echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
// add rows for each of the rows returned.
while($stmt->fetch()){
 echo "<tr>\n<td>" . $tname . "</td>\n<td>" . $tage . "</td>\n<td>" . $tlevel . "</td>\n<td>"  . $playercount . "</td>\n</tr>";
}
$stmt->close();
?>
	</table>
</div>

<br>
<h3>Update or Add Team</h3>
<p>To Add a team, enter the team name, select their age group, and designate their level within the club. When done, click submit.</p>
<p>To update a team, check the update box, and select the team name from the drop down list. Edit anything you wish, and click submit.</p>

<form method="post" action="add_up_team.php">
  <input type="checkbox" name="type" value="update" id="formType">Update
  <select name=teamToUpdate id="team_name" style="visibility: hidden">
    <option value="-1">Select a team</option>
    <?php
    if(!($stmt = $mysqli->prepare("SELECT id, name, age_group, level FROM teams ORDER BY age_group DESC, level ASC"))){
    	echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
    }

    if(!$stmt->execute()){
    	echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
    }
    if(!$stmt->bind_result($id, $name, $age, $level)){
    	echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
    }
    // populate the drop down menu with the names.
    while($stmt->fetch()){
    	echo '<option value=" '. $id .'">'.$name.'</option>';
    }
    $stmt->close();
    ?>
  </select>
		<fieldset>
			<legend>Team</legend>
      <input type="hidden" name="team_id" id="team_ID"/>
			<label>Team Name:<input type="text" name="team_name" id="team"/></label>
			<select name="agegroup" id="agegroup">
        <option value="18 & Under">18 & under</option>
        <option value="17 & Under">17 & under</option>
        <option value="16 & Under">16 & under</option>
        <option value="15 & Under">15 & under</option>
        <option value="14 & Under">14 & under</option>
        <option value="13 & Under">13 & under</option>
        <option value="12 & Under">12 & under</option>
      </select><br/>
      <label>Team Level: <input type="number" name="teamlevel" id="teamlevel"/></label>
		</fieldset>
		<input type="submit" name="Submit" value="submit" />
	</form>

<script type="text/javascript">
// The js exists to make the page cleaner and dynamic.
// in short, it changes the form between update and insert.
// if updating, it pre-fills the form when you select from the drop down.
document.getElementById("formType").onchange = function(){
  if (this.checked){
    document.getElementById("team_name").style.visibility = "visible";
  } else {
    document.getElementById("team_name").style.visibility = "hidden";
    document.getElementById("teamlevel").value = "";
    document.getElementById("team").value = "";
    document.getElementById("agegroup").selectedIndex = -1;
    document.getElementById("team_ID").value = -1;
  }
}
document.getElementById("team_name").onchange = function(){
  if (this.value != -1){
    var name = document.getElementById("team_name");
    var name_text = name.options[name.selectedIndex].textContent;
    document.getElementById("team").value = name_text;
    document.getElementById("team_ID").value = this.value;
    // search for table rows here, and fill in the rest.
    var t_table = document.getElementById("team_table");
    for (var i = 0; i < t_table.rows.length; i++){
      if (t_table.rows[i].cells[0].textContent == name_text){
        document.getElementById("teamlevel").value = Number(t_table.rows[i].cells[2].textContent);
        var a_list = document.getElementById("agegroup");
        for (var a = 0; a < a_list.options.length; a++){
          if (a_list.options[a].text == t_table.rows[i].cells[1].textContent){
            a_list.selectedIndex = a;
          }
        }
      }
    }
  } else {
    document.getElementById("team").value = "";
    document.getElementById("team_ID").value = -1;
    document.getElementById("agegroup").selectedIndex = -1;
    document.getElementById("teamlevel").value = "";
  }
}
</script>

<br>
<footer> Final Project by Brett Anderson and Joseph McMurrough</footer>
<br>
</body>
</html>
