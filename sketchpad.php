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

// print("PHP loaded.");
if(array_key_exists("userLoggedIn", $_COOKIE)) {
	print($_COOKIE["userLoggedIn"]);
}

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

if($action == "signin") {
	if(!hasPostParams(['uname', 'pass']))
		errorMessage('Missing required parameters!');
	signIn($dbh, $_POST['uname'], $_POST['pass']);
} else if($action == "signup") {
	if(!hasPostParams(['uname', 'pass']))
		errorMessage('Missing required parameters!');
	signUp($dbh, $_POST['uname'], $_POST['pass']);
} else if($action == "signout") {
	signOut($dbh);
}

function signUp($dbh, $username, $password) {

	$dbh->beginTransaction();

	try{

		// Check for Conflicts
		$statement  = $dbh->prepare('select id from users where username = :username');

		$statement->execute(array(
			':username' => $username
		));

		// No conflicts were found.
		if(count($statement->fetchAll()) == 0) {

			//Create user
			$statement = $dbh->prepare('insert into users(username,password) values (:username, :password)');

			$statement->execute(array(
				':username' => $username,
				':password' => $password
			));

			$dbh->commit();
			
			setcookie("userLoggedIn", $username, time()+3600*4);

			echo '<script type="text/javascript">',
     			 'login();',
     			 '</script>'
			;

		// Conflicts were found.
		} else {
			errorMessage("User already exists!");
			$dbh->rollBack();
		}

	} catch(PDOException $e) {
		errorMessage($e);
		$dbh->rollBack();
	}
}

function signIn($dbh, $username, $password) {

	$dbh->beginTransaction();

	try {

		$statement = $dbh->prepare('select id from users where username = :username and password = :password');

		$statement->execute(array(
			':username' => $username,
			':password' => $password
		));

		// login successful.
		if(count($statement->fetchAll()) > 0) {

			// Cookie exists.
			if(array_key_exists("userLoggedIn", $_COOKIE)) {

				// Same user.
				if($_COOKIE["userLoggedIn"] == $username) {
					errorMessage("User already signed in!");
					$dbh->rollBack();
					return;
				// Different user.
				} else {
					signOut($dbh);
					setcookie("userLoggedIn", $username, time()+3600*4);
					echo '<script type="text/javascript">',
     					 'login();',
     					 '</script>'
					;
				}
			// Cookie does not exist.
			} else {
				setcookie("userLoggedIn", $username, time()+3600*4);
				echo '<script type="text/javascript">',
     				 'login();',
     				 '</script>'
				;
			}

		// login not successful.
		} else {
			errorMessage("Login not successful!");
			$dbh->rollBack();
			return;
		}
	} catch(PDOException $e) {
		errorMessage($e);
		$dbh->rollBack();
	}
}

function signOut($dbh) {

	// Logged in.
	if(array_key_exists("userLoggedIn", $_COOKIE)) {

		// Get rid of cookie.
		unset($_COOKIE["userLoggedIn"]);
		setcookie("userLoggedIn", '', time()-3600);

		echo '<script type="text/javascript">',
     		 'logout();',
     		 '</script>'
		;
	// Not logged in.
	} else {
		errorMessage("Not logged in!");
	}
}

function hasPostParams($params){
    foreach($params as $param)
        if(!isset($_POST[$param]))
            return false;
    return true;
}

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