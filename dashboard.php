<?php
session_start();
if (!isset($_SESSION['SESSION_LOGGED_IN'])) {
	header("Location: index.php");
	die();
}
?>

<!DOCTYPE html>
<html>

<head>
	<title>SecureAuth | Dashboard</title>

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet"
		integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">

	<link rel="stylesheet" type="text/css" href="css/styles.css">

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

</head>

<body>
	<div class="container"
		style="display:flex;flex-direction:column;align-items:center;justify-content:center;height:400px;width:32;">
		<?php
		include 'config.php';

		$query = mysqli_query($conn, "SELECT * FROM users WHERE email='{$_SESSION['SESSION_EMAIL']}'");

		if (mysqli_num_rows($query) == 1) {
			$row = mysqli_fetch_assoc($query);
			$account_verified = $row['is_verified'];

			echo "<p style='font-size:24px;font-weight:600;letter-spacing:2px;'>Welcome {$row['name']}</p>";
			if ($account_verified == "1") {
				echo "<div class='alert alert-info' style='display:flex;flex-direction:column;justify-content:center;align-items:center;'><p>Your Account has been verified</p><p>Your can now access this feature</p></div>";
			} else {
				echo "<div class='alert alert-danger' style='display:flex;flex-direction:column;justify-content:center;align-items:center;'><p>Your Account has not been verified</p><p>Verify your account to access this feature</p></div>";
			}
			echo "<p>Destroy sesion and logout? <a href='logout.php'>Logout</a></p>";
		}
		?>
	</div>
</body>

</html>