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
?>
<?php
if(isset($_POST['SubmitButton'])){
	$to_email = array();
	$email = "";
	for ($I = 0; $I < sizeof($nresponses); $I++){
		$email = $nresponses[I]+"cheltladiescollege.org";
		array_push($to_email, $email);}

$email_subject = "Reminder to fill out this survey"; // email subject line
$from = 'JacksonAE@cheltladiescollege.org';
 
// To send HTML mail, the Content-type header must be set
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
 
// Create email headers
$headers .= 'From: '.$from."\r\n".
    'Reply-To: '.$from."\r\n" .
    'X-Mailer: PHP/' . phpversion();
 
// Compose a simple HTML email message
$message = '<html><body>';
$message .= '<h3> SURVEY! </h3>';
$message .= '<p style="color:#080;font-size:18px;">Please fill out this survey to pick your Sixth form taster sessions! "link" - this is a tester email there is no actual link ......., If you receive this its from Ola, please send me a screenshot!</p>';
$message .= '</body></html>';
mail($email_to, $email_subject, $message, $headers);
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
<body>
<div class = "content">
<?php 
//connect to mysql server with host,username,password
$connection=mysqli_connect('localhost','root','', 'mysql') or die(mysqli_connect_error());
 
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
<form action="#" method="post">
<br>
<input type="submit" name="SubmitButton" value = "Send Reminder">
</br>
</form>

</br>
<a href="staffinterface3.php" >NEXT PAGE </a>
</br>
</div>
</body>
</html>

