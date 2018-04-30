<?php
// For debugging:
error_reporting(E_ALL);
ini_set('display_errors', '1');

// print("PHP loaded.");

// DB connection info.
$dbms       = 'mysql';
$host       = 'localhost';
$database   = 'jbarr_db1';
$dsn        = "$dbms:dbname=$database;host=$host";
// TODO Change this to your mysql username/password.
$user       = 'jbarr';
$password   = 'knowtheropes';
	
// See if there is an 'action' request.
// if(!isset($_POST['action'])){
//     errorMessage("No action given :(");
// }

// Open connection to database.
try {
    $dbh = new PDO($dsn, $user, $password);
    // Tell PDO to throw exceptions on errors.
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    errorMessage('Connection failed: ' . $e->getMessage());
}


// print_r($_GET['id']);
if($_GET['id'] == "-2"){
    $statement = $dbh->prepare('select id from sketches where user = :user');

    $statement->execute(array(
        ':user' => $_GET['c']
    ));

    $res = $statement->fetchAll();

    $jsonarr = array();

    foreach ($res as $value) {
        // print_r($value['id']);
        array_push($jsonarr, intval($value['id']));
    }

    print_r(json_encode($jsonarr));

} else if($_GET['id'] == "-1") {
    $statement = $dbh->query('select * from sketches');
    $res = count($statement->fetchAll());
    print_r($res);
} else {
    if($_GET['c'] == "title") {
        $statement = $dbh->prepare('select title from sketches where id = :id');

        $statement->execute(array(
        	':id' => $_GET['id']
        ));

        $res = $statement->fetchAll()[0]['title'];
    } else if($_GET['c'] == "user"){
        $statement = $dbh->prepare('select user from sketches where id = :id');

        $statement->execute(array(
            ':id' => $_GET['id']
        ));

        $res = $statement->fetchAll()[0]['user'];
    } else if($_GET['c'] == "data"){
        $statement = $dbh->prepare('select data from sketches where id = :id');

        $statement->execute(array(
            ':id' => $_GET['id']
        ));

        $res = $statement->fetchAll()[0]['data'];
    }
    print_r($res);
}


function errorMessage($message) {
    $jsonarr = array('successful' => false, 'error' => $message);
    printJSON($jsonarr);
}
function printJSON($arr) {
    print json_encode($arr);
}

?>