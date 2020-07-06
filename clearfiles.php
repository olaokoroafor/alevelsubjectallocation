<?php
  $con = mysqli_connect("localhost","root","", "mysql");

  if (!$con)
  {
    die('Could not connect: ' . mysqli_connect_error());
  }

//query places girls choices into studentchoices table usin sql statement
 $query = "DELETE FROM studentchoices;";
//disconnects from database
   if (mysqli_query($con, $query)) {
	   echo "data has been reset";
            }


  mysqli_close($con);
$newc = array("test","test");
$completedf = fopen('completed.csv', 'w');
fputcsv($completedf, $newc);
fclose($completedf);
 ?>