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
  
  if (isset($_SESSION['p_id'])) {
	unset ($_SESSION['p_id']);
  }
  
  if (isset($_SESSION['seats'])) {
	unset ($_SESSION['seats']);
  }
  if(isset($_SESSION['add_for_reservation'])) {
	unset($_SESSION['add_for_reservation']);
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
  	
    <?php  if (isset($_SESSION['username'])) : 
		if(isset($_POST['concert']) || (isset($_SESSION['concert']))) {?>
    	<h2>New Reservation</h2>
    	<p>Choose zone:</p>
		<?php
		  if(isset($_POST['concert'])) {
			$_SESSION['concert'] = $_POST['concert'];
		  }
		  $query = "SELECT prices.p_id, zones.zone, prices.price FROM prices, zones WHERE prices.concert_id = ".$_SESSION['concert']." AND zones.zone_id = prices.zone_id";
  	      $results = mysqli_query($db, $query);
		  $row = mysqli_fetch_row($results);
          $n = mysqli_num_fields($results); 
		  if ($row) {
		?>
		
		<form method="post" action="select_seats.php">
		<table>
			<tr>
				<th></th>
				<th>Zone</th>
				<th>Price</th>
			</tr>
			<?php
		  while ($row) { // Όσο η $row είναι διάφορη του NULL
		    // Εκτύπωση της τρέχουσας εγγραφής
			?>
			<tr>
			<?php
		    for ($i = 0; $i < $n; $i++) { 
		      $val = $row[$i]; 
			  ?><td><?php
			  if ($i == 0) {?>
			  <input name="p_id" type="radio" value="<?= $val?>">
			  <?php
			  }
			  else {
			  ?>
			  <?= $val?>
			  <?php if ($i == 2) :?>
			  €
			  <?php endif?> 
			  </td> 
			  <?php
			  }
		    } 
			?>
			</tr>
			<?php
			// Λήψη της επόμενης εγγραφής
			$row = mysqli_fetch_row($results);
		  } 		  
		?>
		</table>
		<br>
		<br>
		<button type="submit" class="btn" name="select_zone">Select</button>
		</form>
    <?php
		  }
		  }
		  else
			  header("location: reserve.php");
	endif 
	?>
</div>



</body>
</html>
