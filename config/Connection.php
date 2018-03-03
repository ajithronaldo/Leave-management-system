<?php
   $database = 'hackathon';
   $host = 'localhost';
   $user = 'root';
   $pass = '';

   $dbh = new PDO("mysql:dbname={$database};host={$host};port={3306}", $user, $pass);

   $con = mysqli_connect($host,$user,$pass,$database) or die("Connection is unsuccessfull");

   if(!$dbh || !$con){
      echo "unable to connect to database";
   }
   
?>

