<html>
<head>
<style>
body {
  background: lightblue;
}
.content {
  max-width: 800px;
  margin: auto;
  background: white;
  padding: 10px;
}
</style>
</head>
<body>
<div class = "content">
<h1> Fill in Subjects </h1>
<p style="padding: 0 7em 2em 0;border-width: 2px; border-color: blue;
 border-style:solid; width:fit-content; width:-webkit-fit-content; width:-moz-fit-content;">
 You may choose to upload a csv file with your subjects or enter them manually below.</p>
<p id  = "test" ></p>
<div id='list'>
</div>
<form method="POST" action='interface2.php'>
<input type='text' id='input' />
<input type='button' value='add subject' id = "add" />
<input type='text' id='input2' />
<input type='button' value='remove subject' id='remove' />
<input type = 'hidden' name = 'myList' value = subjectcsv id = 'myList' />
<input type='submit' value='Submit' id = "subjectsub" name = "complete" />
</form>

<p style = "font-size: 20px">Or you may upload a csv file with the subjects.</p>

<p style="padding: 0 7em 2em 0;border-width: 2px; border-color: blue;
 border-style:solid; width:fit-content; width:-webkit-fit-content; width:-moz-fit-content;">
 Choose a file from your computer to upload. Then Click upload csv file.</p>
<br>
<form method="post" action='handlesubjects.php' enctype = "multipart/form-data">
    Select csv file to upload:
    <input type="file" name="file" id="file">
    <input type="submit" value="Upload csv file" name="submit">
</form>
</br>
<script type="text/javascript">
//initianilising variables
var subjectli = [];
var subjectcsv = "";
//function called remove which removes subject
document.getElementById("remove").onclick = function(){
	//finds index of the item to remove and removes it from list
	document.getElementById("list").innerHTML = "";
	var toRemove = document.getElementById("input2").value;
	var index = subjectli.indexOf(toRemove);
	if (index > -1) {
       subjectli.splice(index, 1);
    }
	//resets the list displayed the altered list with removed element
  var sList = "";

  for (var I = 0; I < subjectli.length; I++){
       sList = "<li>" + subjectli[I] + "</li>";
       document.getElementById("list").innerHTML += sList;
  }
  document.getElementById("input2").value = ""; // clear the value	
  var subjectcsv = "";
  for (var I = 0; I < subjectli.length; I++){
       
	   subjectcsv = subjectcsv + subjectli[I] + ",";
  }  
	//after all the new elements in the str have been appended separated by commas
	//removes tha last comma and 
	subjectcsv = subjectcsv.slice(0, -1);
	document.getElementById("myList").value = subjectcsv;
}
//adds all the elements in the way it was done above
document.getElementById("add").onclick = function() {

  var text = document.getElementById("input").value; 
  //following lines make sure empty elements are not added to the list!
  if (text === ""){
  }
  else{
	subjectli.push(text);
	var li = document.createElement("li");
	li.textContent = text;
	document.getElementById("list").appendChild(li);
  }

  document.getElementById("input").value = ""; // clear the value
  var subjectcsv = "";
  for (var I = 0; I < subjectli.length; I++)
  {
	   subjectcsv = subjectcsv + subjectli[I] + ",";
  } 
		
	subjectcsv = subjectcsv.slice(0, -1);
  document.getElementById("myList").value = subjectcsv;

}

</script>

If you have already completed this section, please click the link below:
 <a href="staffinterface2.php" >Next </a>
</div>
</body>
</html>



 















