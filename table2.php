<html>
<head>
	<style>
		.red {color: red}
	</style>
	<title>Table</title>
</head>
<body>

<?php



 if ($_SERVER["REQUEST_METHOD"] == "POST"  ){
 
	$tablesPage = "http://www.bbc.com/sport/football/tables";
	
	if(!empty($_POST["team"])){
	
		$teamData= getTeamData($_POST["team"] , $tablesPage); //get associative array of team data
	
		if(!empty($_POST["data"]) && $_POST["data"] == "position"){
		
			echo getPosition($teamData);
		}
	}
}

function getPosition($teamData){
	/*This function takes an array of team data and returns a string containing the name of the team and its position in the leauge */
	
	return  "Team ". $teamData["team"]  ." are currently number " . $teamData["position"] . "  in the league ";

}


function getTeamData($team, $tablesPage){
	/* This function takes a webpage url and the name of a team as two string arguments. e.g. getTeam(""http://www.bbc.com/sport/football/tables", "liverpool")
	It returns an array of data about the team. 
	
	You don't need to understand what this function does just that it returns an array which contains keya and values. The values map to the following keys:
	"position", "team", "played", "won", "drew", "lost", "for", "against", "difference", "points"
	
	
	*/
	$html = new DOMDocument(); 
	@$html->loadHtmlFile($tablesPage); //use DOM
   echo $html->saveHTML(); 

	$xpath = new DOMXPath($html); //use XPath
	$items = $xpath->query('//td/a[text()="' . $team . '"]/../..'); //get the relevant table row
	$values[] =  array();
	foreach($items as $node){
	    foreach($node->childNodes as $child) {
		if($child->nodeType == 1) array_push($values, $child->nodeValue); //KLUDGE
	    }
	}
	$values[2]  = substr($values[2], -1); //KLUDGE
	$values = array_slice( $values, 2, count($values)-4); //KLUDGE
	$keys = array("position", "team", "played", "won", "drew", "lost", "for", "against", "difference", "points");
	return array_combine($keys, $values);
}


?>

<br>
Select a team and a data item about that team
<form  method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
<select name="team">
<option value=""></option>
  <option value="Everton">Everton</option>
  <option value="Arsenal">Arsenal</option>
</select>
<select name="data">
  <option value="position">position</option>
<select>

<input type="submit" value="Get Data"></input>
</select>
</form>
</body>
</html>
