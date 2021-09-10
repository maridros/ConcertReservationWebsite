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
		if(isset($_POST['p_id']) || (isset($_SESSION['p_id']))) {?>
    	<h2>New Reservation</h2>
    	<p>Choose one or more seats that you want to reserve:</p>
		<?php 
		  if(isset($_POST['p_id'])) {
			$_SESSION['p_id'] = $_POST['p_id'];
		  }
		  $query = "SELECT seat FROM reservations WHERE p_id = ".$_SESSION['p_id'];
  	      $results = mysqli_query($db, $query);
		  $row = mysqli_fetch_row($results);
		  $filled_seats = array();
		  
		  while($row) {
			  array_push($filled_seats, $row[0]);
			  // Λήψη της επόμενης εγγραφής
			  $row = mysqli_fetch_row($results);
		  }
		  
		  
		  
		  //find number of seats
		  $query = "SELECT zones.seats FROM zones, prices WHERE prices.p_id = ".$_SESSION['p_id']." AND zones.zone_id = prices.zone_id";
  	      $results = mysqli_query($db, $query);
		  $row = mysqli_fetch_row($results);
		  if($row) {
			  $total_seats = $row[0];
		  }
		  $counter = 0;
		  
		  ?>
		  <form method="post" action="reservation_confirm.php">
		  <table>
			<?php
			while($counter < $total_seats) {
				?><tr><?php
				for ($i = 0; (($i < 10) && ($counter < $total_seats)); $i++) {
					$counter++;
					if (in_array($counter, $filled_seats))
					{
						//seats reserved
						?>
						<td style="border: 1px solid black; background-color: #ff9999;">Seat <?= $counter?></td>
						<?php
					}
					else
					{
						//seats not reserved
						?>
						<td style="border: 1px solid black; background-color: #b3c6ff;"><input name="seat[]" type="checkbox" value="<?= $counter?>">Seat <?= $counter?></td>
						<?php
					}
				}
				?></tr><?php
			}
		  ?>
		  </table>
		  <br>
		  <br>
		  <button type="submit" class="btn" name="select_seats">Continue</button>
		  </form>
		  <?php
		  }
		  else
			  header("location: select_zone.php");
	endif 
	?>
</div>



</body>
</html>
