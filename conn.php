<?php
$conn=mysqli_connect("localhost","root","","school");
if(!$conn){
    die("Connection failed: " . mysqli_connect_error());
}