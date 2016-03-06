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
	<table id="coach_table">
		<tr class="heading" >
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
 echo "<tr>\n<td>" . $fname . "</td>\n<td>" . $lname . "</td>\n<td>" . $tname . "</td>\n<td>" . $ageGroup. "</td>\n<td>" . $level . "</td>\n<td>" . $pos . "</td>\n</tr>";
}
$stmt->close();
?>
	</table>
</div>

<br>
<h3>Update or Add Coach</h3>


<form method="post" action="add_up_coach.php">
  <input type="checkbox" name="type" value="update" checked="checked" id="formType">Update
  <select name=coachToUpdate id="coach_name">
    <option value="-1">Select a coach</option>
    <?php
    if(!($stmt = $mysqli->prepare("SELECT id, first_name, last_name FROM coaches ORDER BY last_name"))){
    	echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
    }

    if(!$stmt->execute()){
    	echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
    }
    if(!$stmt->bind_result($id, $firstname, $lastname)){
    	echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
    }
    while($stmt->fetch()){
    	echo '<option value=" '. $id . ' "> ' . $lastname .', '. $firstname .'</option>';
    }
    $stmt->close();
    ?>
  </select>
		<fieldset>
			<legend>Coach</legend>
      <input type="hidden" name="coach_id" id="coach_ID"/>
			<p>First Name: <input type="text" name="first_name" id="coach_f_name"/></p>
			<p>Last Name: <input type="text" name="last_name" id="coach_l_name"/></p>

			<label>Team</label>
			<select name="teamID" id="team_list">
        <option value="-1">Select Team</option>
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
        	echo '<option value="'. $id . ' ">'. $teamname .'</option>\n';
        }
        $stmt->close();
        ?>


		</select>
		</fieldset>
		<input type="submit" name="Submit" value="submit" />
	</form>

<script type="text/javascript">
document.getElementById("formType").onchange = function(){
  if (this.checked){
    document.getElementById("coach_name").style.visibility = "visible";
  } else {
    document.getElementById("coach_name").style.visibility = "hidden";
    document.getElementById("coach_f_name").value = "";
    document.getElementById("coach_l_name").value = "";
    document.getElementById("coach_ID").value = this.value;
    document.getElementById("team_list").selectedIndex = 0;
  }
}
document.getElementById("coach_name").onchange = function(){
  if (this.value != -1){
    var box = document.getElementById("coach_name");
    var name = box.options[box.selectedIndex].text.split(',');
    first = name[1].substr(1);
    last = name[0];
    document.getElementById("coach_f_name").value = first;
    document.getElementById("coach_l_name").value = last;
    document.getElementById("coach_ID").value = this.value;

    var table = document.getElementById("coach_table");
    var team_name = "";
    for (var i = 0; i < table.rows.length; i++){

      if (table.rows[i].cells[1].textContent == last ){
        if (table.rows[i].cells[0].textContent == first){
          team_name = table.rows[i].cells[2].textContent;
        }
      }
    }

    var teamlist = document.getElementById("team_list");
    for (var i = 0; i < teamlist.options.length; i++){
      if (teamlist.options[i].textContent == team_name){
        teamlist.selectedIndex = i;
      }
    }
  } else {
    document.getElementById("coach_f_name").value = "";
    document.getElementById("coach_l_name").value = "";
    document.getElementById("coach_ID").value = this.value;
    document.getElementById("team_list").selectedIndex = 0;
  }
}
</script>

</body>
</html>
