<html>
<?php
//make connection with database
$con = mysqli_connect("localhost","root","", "mysql");
//fail safe for if the connection dies
if (!$con)
  {
   die('Could not connect: ' . mysqli_connect_error());
  }
else{
//initialising session arrays
$session1 = array();
$session2 = array();
$session3 = array();
$session4 = array();
$subjectchoices = array();

//puts the student csv file provided into a 2d array
$studentTable = array();
if (($handle = fopen("students.csv", "r")) !== FALSE) {
	while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
		$studentTable[] = $data;
	}		fclose($handle);
}
//creates list that will store list of girls ids
$Studentids = array();
//runs through studentTable and appends the student ids to Studentids. 
for ($I = 1; $I < sizeof($studentTable); $I++){
       array_push($Studentids, $studentTable[$I][0]);
  }  
//counting up all the signups for each subjects
for ($X = 0; $X < sizeof($Studentids); $X++){
	$girl = $Studentids[$X];
	//gets the choices from the student choices table
	$query = "SELECT Choice1, Choice2,Choice3, Choice4 FROM studentchoices WHERE StudentID = $girl;";
	$result = mysqli_query($con, $query);
	//puts result in an array
	$choices = mysqli_fetch_array($result);
	array_push($subjectchoices, $choices);
	if (empty($choices)){
		$Studentids[$X] = "remove";
	}
}
$Studentids = array_unique($Studentids);
$Studentids = array_values($Studentids); 
$gone = "";
for ($done = 0; $done < sizeof($Studentids); $done++){
	if($Studentids[$done] == "remove"){
		$gone = $done;
	}
}
unset($Studentids[$gone]);
$Studentids = array_values($Studentids); 

//initialises signups array
$signups = array();
//these two for lists run through the subjectchoices array and adds the subjects in the list to signups
for ($J = 0; $J < sizeof($subjectchoices); $J++){
	for ($K = 0; $K < 4; $K++){
		//checks if specific subject has already been added to signups
		if ((array_key_exists($subjectchoices[$J][$K],$signups)) and ($subjectchoices[$J][$K] != [])){
			$add = $signups[$subjectchoices[$J][$K]][0]+1;
			$signups[$subjectchoices[$J][$K]][0] = $add;
		}
		else{
			if ($subjectchoices[$J][$K] != []){
			$signups[$subjectchoices[$J][$K]] = array(1);
			}
		}

}

}  
//defines findindex function, which essentially finds the index of a value in a 2d list
function findIndex($item, $li) {
	//if item not in list function returns negative -1
    $index = -1;
	for ($I = 0; $I < sizeof($li); $I++){
       if ($li[$I][0] == $item){
		   $index = $I;
	   }
  }
	return $index;
}

//defines in2D function, which finds out if a specific element is in a multidimensional arrray
function in2D($item, $li) {
	//returns false if element not there
    $here = False;
	for ($I = 0; $I < sizeof($li); $I++){
		for ($J = 0; $J < sizeof($li[$I]); $J++){
        if ($li[$I][$J] == $item){
		   $here = True;
	   }
		}
  }
	return $here;
}

//defines fixed subject array
$fixedSub = array();
//if specific subjects have less than 9 signups, they get added to the fixed subjects
foreach ($signups as $key => $value) {
	if ($signups[$key][0] < 9){
		$fixedSub[$key] = $value[0];
	}
}
//takes the smallest 4 subjects, and leaves them in the list, everything else is removed.
if (sizeof($fixedSub) > 4){
	//runs until list is less than or equal to 4 subjects.
	while(sizeof($fixedSub) > 4){
		$toR = max($fixedSub);
		$key = array_search($toR, $fixedSub);
		unset($fixedSub[$key]); 
}
}
$i = 0;	
//reformats the list for ease of programming
foreach ($fixedSub as $key => $value) {
		$fixedSub[$i] = $key;
		unset($fixedSub[$key]);
		$i = $i+1;
	}
	
//this whole block of ifs below, depending on how long the list is, 
//appends the fixed subjects to the sessions.
if (sizeof($fixedSub) == 1){
	array_push($session1, array($fixedSub[0],10,0));
	array_push($signups[$fixedSub[0]],"1");
}
if (sizeof($fixedSub) == 2){
	array_push($session1, array($fixedSub[0],10,0));
	array_push($signups[$fixedSub[0]],"1");
	array_push($session2, array($fixedSub[1],10,0));
	array_push($signups[$fixedSub[1]],"2");
}
if (sizeof($fixedSub) == 3){
	array_push($session1, array($fixedSub[0],10,0));
	array_push($signups[$fixedSub[0]],"1");
	array_push($session2, array($fixedSub[1],10,0));
	array_push($signups[$fixedSub[1]],"2");
	array_push($session3, array($fixedSub[2],10,0));
	array_push($signups[$fixedSub[2]],"3");
}
if (sizeof($fixedSub) == 4){
	array_push($session1, array($fixedSub[0],10,0));
	array_push($signups[$fixedSub[0]],"1");
	array_push($session2, array($fixedSub[1],10,0));
	array_push($signups[$fixedSub[1]],"2");
	array_push($session3, array($fixedSub[2],10,0));
	array_push($signups[$fixedSub[2]],"3");
	array_push($session4, array($fixedSub[3],10,0));
	array_push($signups[$fixedSub[3]],"4");
}
//this adds all the rest of the subjects to every session, with the capacities as well
foreach ($signups as $key => $value) {
	if (in_array($key, $fixedSub) == False){
		array_push($session1, array($key,10,0));
		array_push($session2, array($key,10,0));
		array_push($session3, array($key,10,0));
		array_push($session4, array($key,10,0));
	}
	}
//This procedure allocates the number of spots that should be available for each subject 
//depending on the number of people who have signed up for the subjects. These are stores in the session lists.
foreach ($signups as $key => $value) {
	if ($value[0]>40){
		$c = $value[0] - 40;
		$c = ceil($c/10);
	if ($c==1){
		$index = findIndex($key,$session1);
		$session1[$index][1] = $session1[$index][1] + 10;
	}
	if ($c==2){
		$index = findIndex($key,$session1);
		$session1[$index][1] = $session1[$index][1] + 10;
		$index = findIndex($key,$session2);
		$session2[$index][1] = $session2[$index][1] + 10;
	}
	if ($c==3){
		$index = findIndex($key,$session1);
		$session1[$index][1] = $session1[$index][1] + 10;
		$index = findIndex($key,$session2);
		$session2[$index][1] = $session2[$index][1] + 10;
		$index = findIndex($key,$session3);
		$session3[$index][1] = $session3[$index][1] + 10;
	}
	if ($c==4){
		$index = findIndex($key,$session1);
		$session1[$index][1] = $session1[$index][1] + 10;
		$index = findIndex($key,$session2);
		$session2[$index][1] = $session2[$index][1] + 10;
		$index = findIndex($key,$session3);
		$session3[$index][1] = $session3[$index][1] + 10;
		$index = findIndex($key,$session4);
		$session4[$index][1] = $session4[$index][1] + 10;
	}	
	}
}
//sets up the rejected list
$rejectlist = array();
//First pass procedure below, as well as the definition on studentplaces
$studentplaces = array();
//initialises the lists that store the respective subjects and sessions for each student
//this is stored in the studentplaces dictionary
for ($i = 0; $i < sizeof($Studentids); $i++){
	$studentplaces[$Studentids[$i]] = array(array(),array(),array(),array());
  }
$choices = array();
 //for testing reasons, i am using another loop.
 //runs through list of all students
for ($i = 0; $i < sizeof($Studentids); $i++){
	//puts the choices of the girls into a list to be operated on later down in the code
	$girl = $Studentids[$i];
	$query = "SELECT Choice1, Choice2,Choice3, Choice4 FROM studentchoices WHERE StudentID = $girl;";
	$result = mysqli_query($con, $query);
	$choices1 = mysqli_fetch_array($result);
	$choices[0] = $choices1[0];
	$choices[1] = $choices1[1];
	$choices[2] = $choices1[2];
	$choices[3] = $choices1[3];
	if (empty($choices) == False){
	//runs through the list of their choices
	for ($j = 0; $j < sizeof($choices); $j++){
		//runs through the list of fixed subjects
		for ($k = 0; $k < sizeof($fixedSub); $k++){
			//asks if the subject being compared is one in the list of fixed subjects
			if ($choices[$j] == $fixedSub[$k]){
				//finds out if subject is in session1
				if (findIndex($choices[$j], $session1) != -1){
					//finds index of subject in session1
					$index = findIndex($choices[$j], $session1);
					//add the student being handled to the subject array in session 1
					array_push($session1[$index],$Studentids[$i]);
					//add 1 to the number in the session1 array that counts the student in that subject
					$session1[$index][2] = $session1[$index][2] +1;
					//adds the subject to the girls key in student places if the spot is not empty
					 for ($x = 0; $x < 4; $x++){
						 if (empty($studentplaces[$Studentids[$i]][$x])){
							 array_push($studentplaces[$Studentids[$i]][$x], $choices[$j]);
							 //the addition of the 1, is to signify that the subject is in session 1
							 array_push($studentplaces[$Studentids[$i]][$x], 1);
							 break;
						 }
					 }
				}
			//session2 now
			//this code is a replica of the code above, only in relation to session2 instead of 1
				elseif (findIndex($choices[$j], $session2) != -1){
					$index = findIndex($choices[$j], $session2);
					array_push($session2[$index],$Studentids[$i]);
					$session2[$index][2] = $session2[$index][2] +1;
					 for ($x = 0; $x < 4; $x++){
						 if (empty($studentplaces[$Studentids[$i]][$x])){
							 array_push($studentplaces[$Studentids[$i]][$x], $choices[$j]);
							 array_push($studentplaces[$Studentids[$i]][$x], 2);
							 break;
						 }
					 }
				}
			//session3 now
			//this code is a replica of the code above, only in relation to session3 instead of 1
				elseif (findIndex($choices[$j], $session3) != -1){
					$index = findIndex($choices[$j], $session3);
					array_push($session3[$index],$Studentids[$i]);
					$session3[$index][2] = $session3[$index][2] +1;
					 for ($x = 0; $x < 4; $x++){
						 if (empty($studentplaces[$Studentids[$i]][$x])){
							 array_push($studentplaces[$Studentids[$i]][$x], $choices[$j]);
							 array_push($studentplaces[$Studentids[$i]][$x], 3);
							 break;
						 }
					 }
				}
			//session4 now
			//this code is a replica of the code above, only in relation to session4 instead of 1
				elseif (findIndex($choices[$j], $session4) != -1){
					$index = findIndex($choices[$j], $session4);
					array_push($session4[$index],$Studentids[$i]);
					$session4[$index][2] = $session4[$index][2] +1;
					 for ($x = 0; $x < 4; $x++){
						 if (empty($studentplaces[$Studentids[$i]][$x])){
							 array_push($studentplaces[$Studentids[$i]][$x], $choices[$j]);
							 array_push($studentplaces[$Studentids[$i]][$x], 4);
							 break;
						 }
					 }
				}
			}
		}
	}
  }
}
 //ACTUAL PASS
//the code in this section is pretty much exactly the same as the code above, with some extra functionality which
//I will comment to highlight
  for ($i = 0; $i < sizeof($Studentids); $i++){
	$girl = $Studentids[$i];
	$query = "SELECT Choice1, Choice2,Choice3, Choice4 FROM studentchoices WHERE StudentID = $girl;";
	$result = mysqli_query($con, $query);
	$choices1 = mysqli_fetch_array($result);
	$choices[0] = $choices1[0];
	$choices[1] = $choices1[1];
	$choices[2] = $choices1[2];
	$choices[3] = $choices1[3];
	if (empty($choices) == False){
	for ($j = 0; $j < sizeof($choices); $j++){
				//no need to check if the specific subject is in the fixed subject list anymore
				if (findIndex($choices[$j], $session1) != -1){
					$index = findIndex($choices[$j], $session1);
					//checks to see if the maximum class limit has been reached, and makes sure the girl has not already
					//been added to the specific section.
					if (($session1[$index][2] < $session1[$index][1]) and (in2D($Studentids[$i],$session1) == False)  
						and (in2D($choices[$j], $studentplaces[$Studentids[$i]]) == False)){
					array_push($session1[$index],$Studentids[$i]);
					$session1[$index][2] = $session1[$index][2] +1;
					 for ($x = 0; $x < 4; $x++){
						 if (empty($studentplaces[$Studentids[$i]][$x])){
							 array_push($studentplaces[$Studentids[$i]][$x], $choices[$j]);
							 array_push($studentplaces[$Studentids[$i]][$x], 1);
							 break;
						 }
					 }
					}
				}
			//session2 now
			//same as code above
				if (findIndex($choices[$j], $session2) != -1){
					$index = findIndex($choices[$j], $session2);
					//checks to see if the maximum class limit has been reached, and makes sure the girl has not already
					//been added to the specific section., it also makes sure this specific subject isnt already in their subject list
					if (($session2[$index][2] < $session2[$index][1]) and (in2D($Studentids[$i],$session2) == False) 
						and (in2D($choices[$j], $studentplaces[$Studentids[$i]]) == False)){
					array_push($session2[$index],$Studentids[$i]);
					$session2[$index][2] = $session2[$index][2] +1;
					 for ($x = 0; $x < 4; $x++){
						 if (empty($studentplaces[$Studentids[$i]][$x])){
							 array_push($studentplaces[$Studentids[$i]][$x], $choices[$j]);
							 array_push($studentplaces[$Studentids[$i]][$x], 2);
							 break;
						 }
					 }
				}
				}
			//session3 now
			//same as code above
				if (findIndex($choices[$j], $session3) != -1){
					$index = findIndex($choices[$j], $session3);
					//checks to see if the maximum class limit has been reached, and makes sure the girl has not already
					//been added to the specific section.  it also makes sure this specific subject isnt already in their subject list
					if (($session3[$index][2] < $session3[$index][1]) and (in2D($Studentids[$i],$session3) == False) 
						and (in2D($choices[$j], $studentplaces[$Studentids[$i]]) == False)){
					array_push($session3[$index],$Studentids[$i]);
					$session3[$index][2] = $session3[$index][2] +1;
					 for ($x = 0; $x < 4; $x++){
						 if (empty($studentplaces[$Studentids[$i]][$x])){
							 array_push($studentplaces[$Studentids[$i]][$x], $choices[$j]);
							 array_push($studentplaces[$Studentids[$i]][$x], 3);
							 break;
						 }
					 }
				}
				}
			//session4 now
			//same as code above
				if (findIndex($choices[$j], $session4) != -1){
					$index = findIndex($choices[$j], $session4);
					//checks to see if the maximum class limit has been reached, and makes sure the girl has not already
					//been added to the specific section.  it also makes sure this specific subject isnt already in their subject list
					if (($session4[$index][2] < $session4[$index][1]) and (in2D($Studentids[$i],$session4) == False) 
						and (in2D($choices[$j], $studentplaces[$Studentids[$i]]) == False)){
					array_push($session4[$index],$Studentids[$i]);
					$session4[$index][2] = $session4[$index][2] +1;
					 for ($x = 0; $x < 4; $x++){
						 if (empty($studentplaces[$Studentids[$i]][$x])){
							 array_push($studentplaces[$Studentids[$i]][$x], $choices[$j]);
							 array_push($studentplaces[$Studentids[$i]][$x], 4);
							 break;
						 }
					 }
				}
		}
	}
	//if any of the girls have empty sessions, they will be added to the reject list, as not all their spaces are filled
	if (empty($studentplaces[$Studentids[$i]][0]) or empty($studentplaces[$Studentids[$i]][1]) 
		or empty($studentplaces[$Studentids[$i]][2]) or empty($studentplaces[$Studentids[$i]][3])){
			array_push($rejectlist, $Studentids[$i]);
		}
  }
  }
  //same exact code as the code above, only it takes into account, different Choices, which I will specify.
 for ($i = 0; $i < sizeof($rejectlist); $i++){
	$girl = $rejectlist[$i];
	//select choice5 and choice6, instead of choice1,2,3,4, as these are there back up questions
	$query = "SELECT Choice5, Choice6 FROM studentchoices WHERE StudentID = $girl;";
	$result = mysqli_query($con, $query);
	$choices1 = mysqli_fetch_array($result);
	$choices[0] = $choices1[0];
	$choices[1] = $choices1[1];
	if (empty($choices) == False){
	for ($j = 0; $j < sizeof($choices); $j++){
				if (findIndex($choices[$j], $session1) != -1){
					$index = findIndex($choices[$j], $session1);
					if (($session1[$index][2] < $session1[$index][1]) and (in2D($rejectlist[$i],$session1) == False)){
					array_push($session1[$index],$rejectlist[$i]);
					$session1[$index][2] = $session1[$index][2] +1;
					 for ($x = 0; $x < 4; $x++){
						 if (empty($studentplaces[$rejectlist[$i]][$x])){
							 array_push($studentplaces[$rejectlist[$i]][$x], $choices[$j]);
							 array_push($studentplaces[$rejectlist[$i]][$x], 1);
							 break;
						 }
					 }
					}
				}
			//session2 now
				if (findIndex($choices[$j], $session2) != -1){
					$index = findIndex($choices[$j], $session2);
					if (($session2[$index][2] < $session2[$index][1]) and (in2D($rejectlist[$i],$session2) == False)){
					array_push($session2[$index],$rejectlist[$i]);
					$session2[$index][2] = $session2[$index][2] +1;
					 for ($x = 0; $x < 4; $x++){
						 if (empty($studentplaces[$rejectlist[$i]][$x])){
							 array_push($studentplaces[$rejectlist[$i]][$x], $choices[$j]);
							 array_push($studentplaces[$rejectlist[$i]][$x], 2);
							 break;
						 }
					 }
				}
				}
			//session3 now
				if (findIndex($choices[$j], $session3) != -1){
					$index = findIndex($choices[$j], $session3);
					if (($session3[$index][2] < $session3[$index][1]) and (in2D($rejectlist[$i],$session3) == False)){
					array_push($session3[$index],$rejectlist[$i]);
					$session3[$index][2] = $session3[$index][2] +1;
					 for ($x = 0; $x < 4; $x++){
						 if (empty($studentplaces[$rejectlist[$i]][$x])){
							 array_push($studentplaces[$rejectlist[$i]][$x], $choices[$j]);
							 array_push($studentplaces[$rejectlist[$i]][$x], 3);
							 break;
						 }
					 }
				}
				}
			//session4 now
				if (findIndex($choices[$j], $session4) != -1){
					$index = findIndex($choices[$j], $session4);
					if (($session4[$index][2] < $session4[$index][1]) and (in2D($rejectlist[$i],$session4) == False)){
					array_push($session4[$index],$rejectlist[$i]);
					$session4[$index][2] = $session4[$index][2] +1;
					 for ($x = 0; $x < 4; $x++){
						 if (empty($studentplaces[$rejectlist[$i]][$x])){
							 array_push($studentplaces[$rejectlist[$i]][$x], $choices[$j]);
							 array_push($studentplaces[$rejectlist[$i]][$x], 4);
							 break;
						 }
					 }
				}
		}
	}
  } 
 }
//writes the 2d list into a csv file
$session_1 = "session1.csv";
$outFile = fopen($session_1, "w") or die("Unable to open file!");
//as it is a 2d array, the list is written by one inner list at a time.
foreach($session1 as $value){
	fputcsv($outFile, $value);
	}
fclose($outFile);

//writes the 2d list into a csv file
$session_2 = "session2.csv";
$outFile = fopen($session_2, "w") or die("Unable to open file!");
//as it is a 2d array, the list is written by one inner list at a time.
foreach($session2 as $value){
	fputcsv($outFile, $value);
	}
fclose($outFile);

//writes the 2d list into a csv file
$session_3 = "session3.csv";
$outFile = fopen($session_3, "w") or die("Unable to open file!");
//as it is a 2d array, the list is written by one inner list at a time.
foreach($session3 as $value){
	fputcsv($outFile, $value);
}
fclose($outFile);

//writes the 2d list into a csv file
$session_4 = "session4.csv";
$outFile = fopen($session_4, "w") or die("Unable to open file!");
//as it is a 2d array, the list is written by one inner list at a time.
foreach($session4 as $value){
	fputcsv($outFile, $value);
	}
fclose($outFile);

//initialises final array, that will be able to downloaded 
$c = 0;
$final = array();
//runs through the dictionary of students and their choices
foreach ($studentplaces as $key => $value) {
	//adds their name and 4 empty spots for their subjects
	array_push($final, array($key));
	array_push($final[$c],"", "" , "" , "" );
	for ($i = 0; $i < 4; $i++){
		//if their subject space isnt empty, then it adds the subject to their specified session space
		if (empty($value[$i][0]) == False){	
			$final[$c][$value[$i][1]] = $value[$i][0];
		}
	}
	$c = $c +1;	
}

//writes the 2d list into a csv file
//adds these column headers to the file - for clarity
array_unshift($final, array("Student ID","Session 1", "Session 2", "Session3", "Session4"));
$finalfile = "final.csv";
$outFile = fopen($finalfile, "w") or die("Unable to open file!");
//as it is a 2d array, the list is written by one inner list at a time.
foreach($final as $value){
	fputcsv($outFile, $value);
	}
fclose($outFile);

//close sql database connection
 mysqli_close($con);
}
//array below includes all the files I intend to include in my zip file
$files = array('session1.csv', 'session2.csv', 'session3.csv', 'session4.csv', 'final.csv');
//the name of the zip file
$zipname = 'Tasterpack.zip';
//uses this php functionality to create the zip file
$zip = new ZipArchive;
$zip->open($zipname, ZipArchive::CREATE);
//runs through array adding each file
foreach ($files as $file) {
  $zip->addFile($file);
}
//closes the file
$zip->close();
?>
<?php
//creates a function that outlines the format 
//of the rows in the table
function print_row(&$item) {
	//html elements necessary
  echo('<tr>');
  //calls the function below here
  array_walk($item, 'print_cell');
  echo('</tr>');
}
//creates a function that outlines the format 
//of the cells in the table
function print_cell(&$item) {
	//html elements necessary
  echo('<td>');
  echo($item);
  echo('</td>');
}
?>
<head>
<style>
body {
  background: lightblue;
}
.content {
  max-width: 1000px;
  margin: auto;
  background: white;
  padding: 10px;
}
</style>
</head>
<body>
<div class = "content">
<table>
  <?php array_walk($final, 'print_row');//calls functions?>
</table>
<br>
<a href="download_done.php" >DOWNLOAD FILES </a>
</br>
<br>
<a href="clearfiles.php" > RESET PROGRAM </a>	
</br>
</div>
</body>
</html>