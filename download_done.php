<?php
// output headers so that the file is downloaded rather than displayed
//name of the file in the server docs
$zipname = 'Tasterpack.zip';
//identify what should be done with file and forces download
header('Content-Type: application/zip');
header('Content-disposition: attachment; filename='.$zipname);
header('Content-Length: ' . filesize($zipname));
readfile($zipname);
?>	