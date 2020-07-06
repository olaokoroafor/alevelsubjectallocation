<?php
$completedf = fopen("completed.csv","r");
$completedli = fgetcsv($completedf);
fclose($completedf);

//puts the csv file provided into a 2d array
$studentTable = array();
if (($handle = fopen("students.csv", "r")) !== FALSE) {
	while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
		$studentTable[] = $data;
	}		fclose($handle);
}
//creates list that will store list of girls emails
$usernames = array();
$nresponses = array();
//runs through studentTable and appends emails to toEmail. 
for ($I = 1; $I < sizeof($studentTable); $I++){
       array_push($usernames, $studentTable[$I][1]);
  }  
for ($I = 0; $I < sizeof($usernames); $I++){
       if (in_array($usernames[$I],$completedli) == False){
		array_push($nresponses, $usernames[$I]);
  } 
		else{
			
	}
}
//student interface subject list
$file = fopen("subject.csv","r");
$subjects = fgetcsv($file);
fclose($file);
?>
<?php
//runs through what happens when submit button is clicked.
if(isset($_POST['SubmitButton'])){
  $con = mysqli_connect("localhost","root","", "mysql");

  if (!$con)
  {
    die('Could not connect: ' . mysqli_connect_error());
  }
//places all posted data in variables
  $studentID = $_POST['StudentID'];
  $choice = $_POST['choice'];
  $subject = $_POST['subject'];

  //$studentID = mysql_real_escape_string($studentID);
  //removes all special characters so that it won't affect the sql statements
  $studentID = mysqli_real_escape_string($con,$studentID);
  $choice = mysqli_real_escape_string($con,$choice);
  $subject = mysqli_real_escape_string($con,$subject);

//query places girls choices into studentchoices table usin sql statement
 $query = "
  UPDATE studentchoices 
  SET  $choice  = '$subject'
  WHERE StudentID = $studentID;";
//disconnects from database
   if (mysqli_query($con, $query)) {
      echo "Query changed!";
            }


  mysqli_close($con);

}
 ?>
<html>
<head>
<style>
table, th, td {
  border: 1px solid black;
}
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
<h1 style="text-align:center"> RESPONSES </h1>
</head>
<body>
<div class = "content">
<?php 
//connect to mysql server with host,username,password
//if connection fails stop further execution and show mysql error
$connection=mysqli_connect('localhost','root','', 'mysql') or die(mysqli_connect_error());
//select a database for given connection
//if database selection  fails stop further execution and show mysql error
//mysqli_select_db('mysql',$connection) or die(mysqli_error());
 
//execute a mysql query to retrieve all the users from users table
//if  query  fails stop further execution and show mysql error
$query=mysqli_query($connection,"SELECT * FROM studentchoices") or die(mysqli_connect_error());
 
//if we get any results we show them in table data
if(mysqli_num_rows($query)>0):
 
?>
<table>
  <tr>
    <td align="center">Id</td>
    <td align="center">First Choice</td>
    <td align="center">Second Choice</td>
    <td align="center">Third Choice</td>
    <td align="center">Fourth Choice</td>
    <td align="center">Fifth Choice</td>
    <td align="center">Sixth Choice</td>
  </tr>
    <?php 
  // looping 
  while($row=mysqli_fetch_object($query)):?>
  <tr>
    <td align="center"><?php echo $row->StudentID; ?></td>
    <td align="center"><?php echo $row->Choice1;?></td>
    <td align="center"><?php echo $row->Choice2; ?></td>
    <td align="center"><?php echo $row->Choice3; ?></td>
    <td align="center"><?php echo $row->Choice4; ?></td>
    <td align="center"><?php echo $row->Choice5;?></td>
    <td align="center"><?php echo $row->Choice6; ?></td>
  </tr>
  <?php endwhile;?>
</table>
<?php 
// no result show 
else: ?>
<h3>No Results found.</h3>
<?php endif; ?>
</table>
<h3> AWAITING RESPONSES </h3
<ul>
         <?php
        // Iterating through the product array
        foreach($nresponses as $item){
        ?>
        <li> <?php echo $item; ?> </li>
        <?php
        }
        ?>
</ul>
<h4> Edit The Table</h4>
<form action="#" method="post">
<p> Id of the person you want to edit </p>
<input type="number" name="StudentID"> <br>
<p> Which choice would you like to edit (Choice1, Choice2 etc) </p>
<select id = "choice" name = "choice" >
<option value="Choice1"> Choice 1 </option>
<option value="Choice2"> Choice 2 </option>
<option value="Choice3"> Choice 3 </option>
<option value="Choice4"> Choice 4 </option>
<option value="Choice5"> Choice 5 </option>
<option value="Choice6"> Choice 6 </option>
</select>
<p> What subject would you like to change this to (Must be exact)</p>
	<select  id='subject' name='subject'>
		<option value="" >Choose a subject</option>
        <?php   
        // Iterating through the subjects array
        foreach($subjects as $item){
        ?>
        <option value="<?php echo $item; ?>"><?php echo $item; ?></option>
        <?php
        }
        ?>
	</select>
<input type="submit" name="SubmitButton">
</form>

<br>
<a href="staffinterface2.php" >Previous Page </a>
</br>

<br>
<a href="sortgirls.php" >SORT THE GIRLS! </a>	
</br>
</div>	
</body>
</html>
