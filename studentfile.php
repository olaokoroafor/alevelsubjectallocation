<html>
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
<body>
<div class = "content">
<?php
//outlines the name I would like to give the file
$storagename = "students.csv";
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
<a href="handlesubjects.php" >Go back </a>
</div>
</body>
</html>