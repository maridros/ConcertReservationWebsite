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
  
  if (isset($_SESSION['concert'])) {
	  unset ($_SESSION['concert']);
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
    <?php  if (isset($_SESSION['username'])) : ?>
		
    	<h2>New Reservation</h2>
    	<p>Choose the concert that you want to make the reservation</p>
		<?php 
		  $query = "SELECT distinct concerts.concert_id, concerts.title, concerts.date, stages.stage FROM concerts, prices, zones, stages WHERE prices.concert_id = concerts.concert_id AND zones.zone_id = prices.zone_id AND stages.stage_id = zones.stage_id ORDER BY concerts.date";
  	      $results = mysqli_query($db, $query);
		  $row = mysqli_fetch_row($results);
          $n = mysqli_num_fields($results); 
		  if ($row) {
		?>
		
		<form method="post" action="select_zone.php">
		<table>
			<tr>
				<th></th>
				<th>Concert</th>
				<th>Date</th>
				<th>Stage</th>
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
			  <input name="concert" type="radio" value="<?= $val?>">
			  <?php
			  }
			  else {
			  ?>
			  <?= $val?>
			  
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
		<button type="submit" class="btn" name="select_concert">Select</button>
		</form>
    <?php 
		  }
	else
		echo "Concerts not found.";
	endif 
	?>
</div>



</body>
</html>
