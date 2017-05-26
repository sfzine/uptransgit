<?session_start();?>
<?

//Fatal error: Cannot use isset() on the result of a function call
//(you can use "null !== func()" instead)
//in C:\AutoSet9\public_html\board2\loggedin.php on line 2
// If no session value is present, redirect the user:
// Also validate the HTTP_USER_AGENT!
//session_start();
if (!isset($_SESSION['agent']) OR ($_SESSION['agent'] != md5($_SERVER['HTTP_USER_AGENT']) )) {

	// Need the functions:
	require ('includes/login_functions.inc.php');
	redirect_user();

}

// Set the page title and include the HTML header:
$page_title = 'Logged In!';
include ('includes/header.html');

// Print a customized message:
echo "<h1>Logged In!</h1>
<p>You are now logged in, {$_SESSION['first_name']}!</p>
<p><a href=\"logout.php\">Logout</a></p>";

include ('includes/footer.html');
?>
