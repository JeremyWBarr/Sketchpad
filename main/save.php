<!DOCTYPE html>
<html>
<head>
    <title>Sketchpad</title>
</head>
<body>

<?php
// For debugging:
error_reporting(E_ALL);
ini_set('display_errors', '1');

print("PHP loaded.");

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


print_r($_POST);

// echo 'console.log('.$action.');';

$dbh->beginTransaction();

$statement = $dbh->prepare('insert into sketches(user,title,data) values (:user, :title, :data)');

$statement->execute(array(
    ':user' => $_COOKIE["userLoggedIn"],
	':title' => $_POST['title'],
	':data' => $_POST['data']
));

$dbh->commit();


function errorMessage($message) {
    $jsonarr = array('successful' => false, 'error' => $message);
    printJSON($jsonarr);
}
function printJSON($arr) {
    print json_encode($arr);
}

?>
</body>
</html>