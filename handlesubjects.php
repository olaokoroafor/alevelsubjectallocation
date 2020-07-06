<?php
//outlines the name I would like to give the file
$storagename = "subject.csv";
//if a file has been submitted
if(isset($_POST["submit"])) {
	//this puts the file given by the user into a file named subject csv
	//this file is automatically put into the same folder as the rest of documents
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $storagename)) {
        echo "The file ". basename( $_FILES["file"]["name"]). " has been uploaded.";
    } else {
		//tells the user when things do not go as planned
        echo "Sorry, there was an error uploading your file.";
    }
}
?>
<?php
//puts the students.csv file into a 2d array
	$studentTable = array();
	//runs through file appending each line
	if (($handle = fopen("students.csv", "r")) !== FALSE) {
		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
			$studentTable[] = $data;
		}		fclose($handle);
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
$message .= '<p style="color:#080;font-size:18px;">
Please fill out this survey to pick your Sixth form taster sessions! 
"link" - this is a tester email there is no actual link .......</p>';
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
<div class = "content">
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