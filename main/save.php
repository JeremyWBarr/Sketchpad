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
if(!isset($_POST['action'])){
    errorMessage("No action given :(");
}

// Open connection to database.
try {
    $dbh = new PDO($dsn, $user, $password);
    // Tell PDO to throw exceptions on errors.
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    errorMessage('Connection failed: ' . $e->getMessage());
}

// Process the action; call the corresponding function to process it.
$action = $_POST['action'];
print($action);

echo 'console.log('.$action.');';

$dbh->beginTransaction();

$statement = $dbh->prepare('insert into sketchs(uid,title,data) values (:uid, :title, :data)');

$statement->execute(array(
	':uid' => 1,
	':title' => "test",
	':data' => "test"
));

$dbh->commit();

?>
</body>
</html>