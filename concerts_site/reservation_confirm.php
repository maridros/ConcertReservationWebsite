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
  	
	<!-- insert new reservation and unset all reservation's sessions -->
    <?php  if (isset($_SESSION['username'])) : 
		if((isset($_POST['confirm'])) && (isset($_SESSION['seats']))) {
			$num_of_seats = count($_SESSION['seats']);
			if($num_of_seats > 0) {
				for($i=0;$i<$num_of_seats;$i++)
				{
					$seat_num=$_SESSION['seats'][$i];
       
					$insert_reservation_query="INSERT INTO reservations (uname, p_id, seat) VALUES ('".$_SESSION['username']."', ".$_SESSION['p_id'].", ".$seat_num.")"; 
					$insert_reservation_result = $db->query($insert_reservation_query);
				}
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
			header("location: index.php");
		}
		else {
		?>
    	<h2>New Reservation</h2>
    	<p>Do you want complete the following reservation?</p>
		<div>
		<table>
		<tr>
			<th>Concert</th>
			<th>Stage</th>
			<th>Zone</th>
			<th>Price</th>
			<th>Number of tickets</th>
			<th>Selected seats</th>
		</tr>
		<?php
			//select info of new reservation and show them to a table
			$query = "SELECT concerts.title, stages.stage, zones.zone, prices.price FROM concerts, stages, zones, prices WHERE prices.p_id = ".$_SESSION['p_id']." AND concerts.concert_id = prices.concert_id AND zones.zone_id = prices.zone_id AND stages.stage_id = zones.stage_id";
			$results = mysqli_query($db, $query);
			$row = mysqli_fetch_row($results); 
			$n = mysqli_num_fields($results);
			if ($row) {
				?><tr><?php
				for($i = 0; $i < $n; $i++) {
				?>
				<td>
				<?=$row[$i]?>
				<?php if ($i == 3) :
				$total_amount = $row[$i]; //initialize total_amount with the price of the ticket
				?>
				€
				<?php endif?>
				</td>
				<?php
				}
				
			}
		?>
		<?php
		if(!isset($_SESSION['seats'])) {
		if(!empty($_POST['seat']) && array_filter($_POST['seat'])){
			$seats = array();
			$cnt=array();
			$cnt=count($_POST['seat']);
			if($cnt > 0){
				for($i=0;$i<$cnt;$i++)
				{
					array_push($seats, $_POST['seat'][$i]);
				}
			}
			
		$_SESSION['seats'] = $seats;
		
		}
		}
		if(isset($_SESSION['seats'])) {
			$cnt = array();
			$cnt = count($_SESSION['seats']);
			$total_amount = $total_amount * $cnt; //find the total price of all the tickets, to compare it with the amount in card
			if($cnt > 0) {
				?><td><?=$cnt?></td>
				<td>
				<?php
				for($i=0;$i<$cnt;$i++)
				{
					$val= $_SESSION['seats'][$i];
					echo "$val ";
				}
				?></td><?php
			}
		}
		?></tr><?php
	?></table></div>
	<br><br>
	<!-- PRIN TH FORMA IF GIA NA TSEKARW OTI MPOREI KI AN DEN, TOTE EMFANISH SYNDESMOU FORTWSHS KARTAS!!! -->
	<?php
		$query = "SELECT card FROM users WHERE uname = '".$_SESSION['username']."'";
		$results = mysqli_query($db, $query);
		$row = mysqli_fetch_row($results); 
		if ($row) {
			$card_amount = $row[0];
		}
		if($card_amount >= $total_amount) {
	?>
	<!-- edw bazw forma me epistrofh ston eauto ths wste na kanei thn energeia tou insert meta thn epibebaiwsh!!!  -->
		<form method="post" action="reservation_confirm.php">
		<button type="submit" class="btn" name="confirm">Confirm</button>
		</form>
		<?php
		}
		else {
			//MESSAGE TO ADD MONEY IN CARD SO HE CAN CONTINUE RESERVATION
			$_SESSION['reservation_complete'] = 1;
			?>
			<div class="error">
			<p>Your card balance is not enough for this reservation!</p>
			</div>
			<?php
			?>
			
				<!-- <p>Your card balance is not enough for this reservation!</p> -->
				<form method="post" action="card.php"> 
				<button type="submit" class="btn" name="add_for_reservation">Add money to card</button>
				</form>
				<br>
				<br>
				<form method="post" action="index.php"> <!-- check post in index.php show that it deletes reservation's sessions -->
				<button type="submit" class="btn" name="cancel_reservation">Cancel reservation</button>
				</form>
			
			<?php
		}
  
		}
endif 
	?>
</div>



</body>
</html>
