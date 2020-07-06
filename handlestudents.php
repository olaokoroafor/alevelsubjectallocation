<html>
<head>
<style>
body {
  background: lightblue;
}
.content {
  max-width: 500px;
  margin: auto;
  background: white;
  padding: 10px;
}
</style>
</head>
<div class = "content">
<body>
<h4>If you have a problem, please email: JacksonAE@cheltladiescollege.org </h4>
<?php
//puts students.csv into a 2d array called studentTable
if (($handle = fopen("students.csv", "r")) !== FALSE) {
	while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
		$studentTable[] = $data;
	}		fclose($handle);
}
// creates connection with mysql table, confusing stuff
if ($_POST) {
  $con = mysqli_connect("localhost","root","", "mysql");

  if (!$con)
  {
    die('Could not connect: ' . mysqli_connect_error());
  }
  //require_once "config.php";
//opens completed.csv file and appends username of girl who's done survey
$completedf = fopen("completed.csv","r");
$completedli = fgetcsv($completedf);
fclose($completedf);
//places all posted data in variables
  $choice1 = $_POST['choice1'];
  $choice2 = $_POST['choice2'];
  $choice3 = $_POST['choice3'];
  $choice4 = $_POST['choice4'];
  $choice5 = $_POST['choice5'];
  $choice6 = $_POST['choice6'];
//creates array for choices, to see if there are any duplicates
$unique = array();
//appends the choices above to the list
array_push($unique, $choice1, $choice2, $choice3, $choice4, $choice5, $choice6);
//this if statement asks if the student is already in the completed list
//meaning she has already completed the survey
if (in_array($_POST['userN'], $completedli) ){
	//this notifies the student that the username theyve picked is already in use
	//makes sure form isnt submitted twice
	echo "You have not successfully submitted your survey! This username has already been used.";
}
//this if statement removes all the duplicates from the unique array and compares
//the length to the length of the original array
//if it is smaller it means that the original array had duplicates
elseif (count(array_unique($unique))<count($unique) ){
	//notifies the student of their issue
	echo "You can only choose a subject once, please try again with valid subject choices!";
}
else{
array_push($completedli, $_POST['userN']);
$completedf = fopen('completed.csv', 'w');
fputcsv($completedf, $completedli);
fclose($completedf);
//gets the id of the student with chosen username
$studentID = "";
for ($I = 1; $I < sizeof($studentTable); $I++){
       if ($studentTable[$I][1] == $_POST['userN']){
			$studentID = $studentTable[$I][0];
  } 
}

  //
  //$studentID = mysql_real_escape_string($studentID);
  //removes all special characters so that it won't affect the sql statements
  $choice1 = mysqli_real_escape_string($con,$choice1);
  $choice2 = mysqli_real_escape_string($con,$choice2);
  $choice3 = mysqli_real_escape_string($con,$choice3);
  $choice4 = mysqli_real_escape_string($con,$choice4);
  $choice5 = mysqli_real_escape_string($con,$choice5);
  $choice6 = mysqli_real_escape_string($con,$choice6);
//query places girls choices into studentchoices table usin sql statement
  $query = "
  INSERT INTO studentchoices (StudentID, Choice1, Choice2, Choice3,
       Choice4, Choice5, Choice6) VALUES ($studentID, '$choice1',
      '$choice2', '$choice3', '$choice4',
        '$choice5', '$choice6');";
//disconnects from database
   if (mysqli_query($con, $query)) {
      echo "You have successfully submitted you survey!";
            }


  mysqli_close($con);
}
}
?>
<br>
<a href = "studentI.php"> TRY AGAIN IF UNSUCCESSFUL</a>
</br>
</div>
</body>
</html>