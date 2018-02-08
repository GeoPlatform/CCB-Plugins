<?php
// This file uses the file_get_contents() php function to load in the url passed
// to it via the request.open() method. It grabs it through the $_POST['url']
// method, after string saniation of course.
if (isset($_GET['url'])){
 echo file_get_contents('http://' . SanitizeString($_GET['url']));
}

function SanitizeString($var){
 $var = strip_tags($var);
 $var = htmlentities($var);
 return stripslashes($var);
}
?>
