<?php
/**
 * Created by Luke Adams
 * User: Luke Adams
 * Date: 24/04/2015
 * Time: 13:55
 *
 * Create DB to hold table that will hold tweet data
 */

$servername = 'Your server name';
$username = 'Your server username, for example, mine is root';
$password = 'Your server password';


//create connection
$conn = mysqli_connect($servername,$username,$password);

//check connection
if(!$conn){
    die("Connection failed: ".mysqli_connect_error());
}

//create the DB
$sql = "CREATE DATABASE twitterDB";

if(mysqli_query($conn,$sql)){
    echo "Database created successfully";
}
else{
    echo "Error creating database: ".mysql_error($conn);
}

mysqli_close($conn);

?>
