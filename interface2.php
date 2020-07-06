<html>
<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
//this is where what is posted from the last page is handled
//The only thing posted was the string of comma delimited subjects created
if (isset($_POST['complete'])) {
	//this variable stores an array, as exlodes the csv file past over
	$subjectA = explode(",",$_POST['myList']);
	//save the list for future use.
	$fp = fopen('subject.csv', 'w');
	fputcsv($fp, $subjectA);

	fclose($fp);
	//puts the csv file provided into a 2d array
	$studentTable = array();
	if (($handle = fopen("students.csv", "r")) !== FALSE) {
		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
			$studentTable[] = $data;
		}		fclose($handle);
}
}
//creates list that will store list of girls emails
$toEmail = array();
//runs through studentTable and appends emails to toEmail. 
for ($I = 1; $I < sizeof($studentTable); $I++){
       array_push($toEmail, ($studentTable[$I][1]."@cheltladiescollege.org"));
  }  

$email_to = implode(',', $toEmail); // your email address
$email_subject = "Fill out this survey"; // email subject line
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


?>
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
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

    <div class="content">
        <h1>Hi, <b>Mrs Jackson</b>.</h1>
<form method="post" action='studentfile.php' enctype = "multipart/form-data">
    Select csv file to upload:
    <input type="file" name="file" id="file">
    <input type="submit" value="Upload csv file" name="submit">
</form>
        <a href="logout.php" class="btn btn-danger">Sign Out </a>
	<p>  </p>	
		<a href="staffinterface2.php" >NEXT PAGE </a>
		
</div>	
</body>
</html>