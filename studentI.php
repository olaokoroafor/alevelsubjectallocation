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
<?php
//student interface
$file = fopen("subject.csv","r");
$subjects = fgetcsv($file);
fclose($file);
//puts the csv file provided into a 2d array
$studentTable = array();
if (($handle = fopen("students.csv", "r")) !== FALSE) {
	while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
		$studentTable[] = $data;
	}		fclose($handle);
}
//creates list that will store list of girls emails
$usernames = array();
//runs through studentTable and appends emails to toEmail. 
for ($I = 1; $I < sizeof($studentTable); $I++){
       array_push($usernames, $studentTable[$I][1]);
  }  

?>
<h2> Select your subjects! </h2>
<p style= "font-family:verdana;color:red;padding: 0 7em 2em 0;border-width: 2px; border-color: blue;
 border-style:solid; width:fit-content; width:-webkit-fit-content; width:-moz-fit-content;">
 You may not choose the same subject twice. You may not submit this form twice.</p>
<form method="POST" action='handlestudents.php'>
<p style= "font-family:verdana;"> Select your name </p>
<select required="required" id='userN' name='userN'>
        <option value="">Choose one</option>
        <?php
        // Iterating through the usernames array to display them
        foreach($usernames as $item){
        ?>
        <option value="<?php echo $item; ?>"><?php echo $item; ?></option>
        <?php
        }
        ?>
</select>
<p style= "font-family:verdana;"> Select your 1st choice subject </p>
	<select required="required" id='choice1' name='choice1'>
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
<p style= "font-family:verdana;"> Select your 2nd choice subject </p>
	<select required="required" id='choice2' name='choice2'>
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
<p style= "font-family:verdana;"> Select your 3rd choice subject </p>
	<select required="required" id='choice3' name='choice3'>
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
<p style= "font-family:verdana;"> Select your 4th choice subject </p>
	<select required="required" id='choice4' name='choice4'>
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
<p style= "font-family:verdana;"> Select your 5th choice subject </p>
	<select required="required" id='choice5' name='choice5' >
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
<p style= "font-family:verdana;"> Select your 6th choice subject (none is an option) </p>
	<select required="required" id='choice6' name='choice6'>
		<option value="" >Choose a subject</option>
        <?php
        // Iterating through the subjects array
        foreach($subjects as $item){
        ?>
        <option value="<?php echo $item; ?>"><?php echo $item; ?></option>
        <?php
        }
        ?>
		<option value = ""> None </option>
	</select>
<input type="submit" value="Submit">
</form>
</div>
</body>
</html>