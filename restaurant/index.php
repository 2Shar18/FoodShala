<?php
	session_start();
	require '../resource/config.php';
	// Allowing only authorized restaurants
	if (!isset($_SESSION['username'])) {
		header('location: ../');
		exit();
	}
	else if ($_SESSION['type'] == 'restaurant'){
		$username = $_SESSION['username'];
		$name = $_SESSION['name'];
	}
	else {
		header('location: ../customer/');
		exit();	
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="../resource/style.css">
</head>
</head>
<body>
	<a href="../">Home</a>
	<?php 
		$stmt = $conn->prepare("SELECT * FROM restaurant WHERE username = ?");
		$stmt->bind_param("s", $username);
		if($stmt->execute()) {
			$result = $stmt->get_result();
			$row = $result->fetch_assoc();
		}
		else {
			header('location: ../');
		}
			
	?>
	<!-- Adding Navbar -->
	<?php $page='profile'; require '../template/nav.php'; ?>
	<main>
		<h1><?php
			echo "Welcome ".$name;
		?></h1>
		<div class="profile" id="details" style="display: block;">
			<br>
			<h3>Here are your details</h3>
			<!-- Editing Details -->
			<button style="width: auto;" onclick="editDetails(this)">Edit details</button>
			<form method="post" action="../edit.php" class="form-input">
				<input type="hidden" name="type" value="restaurant">
				<input type="hidden" name="id" value="<?php echo $row['id'];?>">
				<table>
				<tr>
					<td><label for="name"><b>Full Name</b></label></td>
					<td><input type="text" placeholder="Enter Full Name" name="name" required disabled value="<?php echo $row['name']; ?>"></td>
				</tr>
				<tr>
					<td><label for="address"><b>Address</b></label></td>
					<td><textarea placeholder="Enter Address" name="address" required disabled><?php echo $row['address']; ?></textarea></td>
				</tr>
				<tr>
					<td><label for="cuisine"><b>Cuisine</b><br><p style="font-size: 9px; margin: 0;">(Enter Comma Separated Cuisines)</p></label></td>
					<td><textarea placeholder="Enter Cuisines served" name="cuisine" required disabled><?php echo $row['cuisine']; ?></textarea></td>
				</tr>
				</table>
				<br>
				<button style="width: auto; display: none;" type="submit" id="save-changes">Save Changes</button>
			</form>
			<button style="width: auto; display: none;" class="cancelbtn" id="cancel-changes" onclick="cancel()">Cancel</button>
		</div>
	</main>
	<script type="text/javascript">
		function editDetails(e){
			e.style.display = "none";
			document.getElementById('save-changes').style.display = "block";
			document.getElementById('cancel-changes').style.display = "block";
			var inputs = document.getElementsByTagName('input');
			for(let index = 0; index < inputs.length; ++index) {
				inputs[index].disabled = false;
			}
			document.getElementsByTagName('textarea')[0].disabled = false;
		}

		function cancel(){
			location.reload();
		}
		<?php 
			if(isset($_GET['info'])) {
				echo "alert('Your profile has been successfully created!');";
			}
		?>
	</script>
</body>
</html>