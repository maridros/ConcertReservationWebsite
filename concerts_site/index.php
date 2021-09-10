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
  
  if(isset($_SESSION['block_counter'])) {
	  unset($_SESSION['block_counter']);
  }
  
  if (isset($_SESSION['p_id'])) {
	unset ($_SESSION['p_id']);
  }
  if (isset($_SESSION['concert'])) {
	unset ($_SESSION['concert']);
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
  	
	<!-- wellcome user -->
    <?php  if (isset($_SESSION['username'])) : ?>
		<?php if (isset($_SESSION['success'])) : ?>
			<div class ="error success">
				<h3>
				Welcome <?php echo $_SESSION['username']; ?>!!!
				<?php 
					echo $_SESSION['success']."."; 
					unset($_SESSION['success']);
				?>
				</h3>
			</div>
		<?php endif ?>
		
    	<h2>My Reservations</h2>
    	
		<?php 
		  //select and show reservations of this user to a table and cancellation button
		  $query = "SELECT reservations.r_id, concerts.title, concerts.date, stages.stage, zones.zone, reservations.seat, prices.price FROM reservations, concerts, stages, zones, prices WHERE reservations.uname = '".$_SESSION['username']."' AND prices.p_id = reservations.p_id AND concerts.concert_id = prices.concert_id AND zones.zone_id = prices.zone_id AND stages.stage_id = zones.stage_id";
  	      $results = mysqli_query($db, $query);
		  $row = mysqli_fetch_row($results);
          $n = mysqli_num_fields($results); 
		  if ($row) {
		?>
		<form method="post" action="reservations_cancel.php">
		<table>
			<tr>
				<th></th>
				<th>Reservation Code</th>
				<th>Concert</th>
				<th>Concert Date</th>
				<th>Stage</th>
				<th>Zone</th>
				<th>Seat</th>
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
			  if ($i == 0) : ?>
			  <td><input name="reservation[]" type="checkbox" value="<?= $val?>"></td>
			  <?php
			  endif
			  ?>
			  <td>
			  <?= $val?>
			  
			  <?php if ($i == 6) :?>
			  €
			  <?php endif?>
			  </td> 
			  <?php
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
		<button type="submit" class="btn" name="Cancellation">Cancellation</button>
		</form>
    <?php 
		  }
	else
		echo "Reservations not found.";
	endif 
	?>
</div>



</body>
</html>
