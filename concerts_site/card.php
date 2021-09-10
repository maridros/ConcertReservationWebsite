<?php 
  session_start(); 
  header('Content-Type: text/html; charset=utf-8');

  if (!isset($_SESSION['username'])) {
  	$_SESSION['msg'] = "You must log in first";
  	header('location: login.php');
  }
  if (isset($_GET['logout'])) {
  	session_destroy();
  	unset($_SESSION['username']);
  	header("location: login.php");
  }
  // connect to the database
  $db = mysqli_connect("localhost", "root", "", "concerts_db");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Home</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="index.css">
</head>
<body>
<header>
            <h1>My Account</h1>
</header>

<div class="topnav">
  <a href="index.php?logout='1'">Logout</a>
  <a href="reserve.php">New Reservation</a>
  <a href="index.php">My Reservations</a>
  <a href="card.php">My Card</a>
</div>

<div class="content">
  	
	<!-- Show card amount and button to add amount -->
    <?php 
	if (isset($_SESSION['username'])) : 
		//adding amount to card
		if(isset($_POST['add_money'])) {
			if(($_POST['quantity']) > 0) {
				$update_balance_query="UPDATE users SET card = (card + ".$_POST['quantity'].") WHERE uname = '".$_SESSION['username']."'";
				$update_balance_result = $db->query($update_balance_query);
			}
		}
		//select amount of user's card and show it with  textfield and add button
		$query = "SELECT card FROM users WHERE uname = '".$_SESSION['username']."'";
		$results = mysqli_query($db, $query);
		$row = mysqli_fetch_row($results); 
		if ($row) {
			$card_amount = $row[0];
		}
	?>
	<p>Card Balance: <?= $card_amount?>€</p>
	<form method="post" action="card.php">
		<p>Deposit:</p>
		<input type="number" name="quantity" step="0.01" value="0">€
		<button type="submit" name="add_money">Add</button>
	</form>
	<?php
	
	//if user came hear because of a reservation which could not be completed because money are not enough
	if((isset($_POST['add_for_reservation'])) || (isset($_SESSION['add_for_reservation']))) {
		$_SESSION['add_for_reservation'] = 1;
		?>
		<br>
		<br>
		<form method="post" action="reservation_confirm.php">
		<button type="submit" class="btn" name="Continue with reservation">Continue with my reservation</button>
		</form>
		<?php
	}
	else {
		if (isset($_SESSION['p_id'])) {
			unset ($_SESSION['p_id']);
		}
		if (isset($_SESSION['concert'])) {
			unset ($_SESSION['concert']);
		}
		if (isset($_SESSION['seats'])) {
			unset ($_SESSION['seats']);
		}
	}
		
endif 
	?>
</div>



</body>
</html>
