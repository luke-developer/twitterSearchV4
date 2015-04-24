<html>

<head>
    <title>Twitter Keyword Searcher</title>
    <meta charset="utf-8"/>

</head>

<body>

<!-- Create a basic form, the user can select keyword, number of results to return and the type of result -->

<form action="" method="post">

    <label>Search Keyword: </label><input type="text" name="keyword" placeholder="eg, hello world" required>
    <br><br>
    <label>Enter # of results to return: </label><input type="text" name="count" placeholder="eg, 50" required>
    <br><br>
    <label>Choose result type: </label>
    <br>
    <input type="radio" name="option" value="Mixed"><label>Mixed</label>
    <input type="radio" name="option" value="recent"><label>Recent</label>
    <input type="radio" name="option" value="popular"><label>Popular</label>
    <br><br>
    <input type="submit" name="usubmit" value="Get Tweets">


</form>

<?php
/**
 * Created by Luke Adams
 * User: Luke Adams
 * Date: 24/04/2015
 * Time: 13:45
 * Version 1.0
 */

//allows us access to stuff in these files - very important!
require "twitteroauth/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;

//define your twitter dev details. Access via https://apps.twitter.com/
define('CONSUMER_KEY', 'YOUR CONSUMER KEY');
define('ÇONSUMER_SECRET', 'YOUR CONSUMER SECRET');
define('ACCESS_TOKEN', 'YOUR ACCESS TOKEN');
define('ACCESS_TOKEN_SECRET', 'YOUR ACCESS TOKEN SECRET');


//This is the function that will get the tweets
function search(array $query)
{

    $connection = new TwitterOAuth(CONSUMER_KEY, ÇONSUMER_SECRET, ACCESS_TOKEN, ACCESS_TOKEN_SECRET);
    //the 'search/tweets' can be changed to other options, check out the twitter rest api for more information
    return $connection->get('search/tweets', $query);

}

//This function can take multiple parameters depending on what we're looking for
$query = array(
    "q" => @strip_tags($_POST['keyword']), "count" => @strip_tags($_POST['count']), "result_type" => @$_POST['option']
);

//assign our search result to a variable
$results = search($query);


foreach (@$results->statuses as $result) {

    //these variables contain information collected from the results XML file
    $twitterUser = $result->user->screen_name;
    $twitterStatus = $result->text;

    //enter the above values into the db
    if (isset($_POST['usubmit'])) {

        //db variables
        $servername = 'YOUR SERVER NAME, MINE IS LOCALHOST';
        $username = 'YOUR USERNAME, MINE IS ROOT';
        $password = 'YOUR SERVER PASSWORD';
        $dbname = 'twitterDB';

        //connect to db
        $conn = new mysqli($servername, $username, $password, $dbname);
        if (!$conn) {
            die("Connnection to database failed " . $conn->connect_error);
        }

        //check if table exists
        $val = $conn->query("SELECT 1 FROM twittertable");
        if ($val !== false) {

            //statement to insert our twitter variables into our table
            $sql = "INSERT INTO twittertable(ID,twitname,tweet) VALUES(NULL,'$twitterUser','$twitterStatus')";
            //execute the above sql statement
            $conn->query($sql);

            //lets us know each time a records is inserted successfully
            echo "data entry success <br>";

        } //create table if it does not exist yet
        else {

            $sql = 'CREATE TABLE twittertable(
              ID int NOT NULL AUTO_INCREMENT,
              twitname VARCHAR (50) NOT NULL,
              tweet VARCHAR (255),
              PRIMARY KEY (ID)
              )';

            //initiate table and check if any errors
            if ($conn->query($sql) === TRUE) {
                echo "table created <br><br>";
                $sql = "INSERT INTO twittertable(ID,twitname,tweet) VALUES(NULL,'$twitterUser','$twitterStatus')";
                $conn->query($sql);

                echo "data entry success <br>";
            } else {
                echo "Error creating table " . $conn->error . "<br><br>";
            }

        }

    }
}

?>

</body>

</html>