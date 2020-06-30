<?php 
	session_start();
	require './resource/config.php';
	if (isset($_SESSION['username'])) {
		header('location: ./');
	}

	if($_SERVER["REQUEST_METHOD"] == "POST") {
		$type = $_POST['type'];
		// Restaurant Registration
		if ($type == "restaurant") {
			$username = $_POST['username'];
			$name = $_POST['name'];
			$password = $_POST['psw'];
			$address = $_POST['address'];
			$cuisine = $_POST['cuisine'];

			$stmt = $conn->prepare("SELECT * FROM restaurant WHERE username = ?");
			$stmt->bind_param("s", $username);
			if($stmt->execute()) {
				$stmt->store_result();
				$num = (int)$stmt->num_rows;
				if ($num == 1) {
					header('location: ./?error=username-exists&form=register&type=r_content');
					exit();
				}
				$stmt = $conn->prepare("INSERT INTO restaurant VALUES(NULL, ?, ?, ?, ?, ?)");
				$stmt->bind_param("sssss", $username, $password, $name, $address, $cuisine);
				if($stmt->execute()) {
					$_SESSION['type'] = 'restaurant';
					$_SESSION['username'] = $username;
					$_SESSION['name'] = $name;
					header('location: ./restaurant/?info=new-user');
				}
				else {
					header('location: ./?error=no-create&form=register&type=r_content');
				}
			}
			else {
				header('location: ./?error=no-create&form=register&type=r_content');
			}
		}
		// Customer Registration
		else if ($type == "customer") {
			$username = $_POST['username'];
			$name = $_POST['name'];
			$email = $_POST['email'];
			$password = $_POST['psw'];
			$address = $_POST['address'];
			$isVeg = 0;
			if (isset($_POST['isVeg']))
				$isVeg = 1;

			$stmt = $conn->prepare("SELECT * FROM customer WHERE username = ? OR email = ?");
			$stmt->bind_param("ss", $username, $email);
			if($stmt->execute()) {
				$stmt->store_result();
				$num = (int)$stmt->num_rows;
				if ($num == 1) {
					header('location: ./?error=username-exists&form=register&type=c_content');
					exit();
				}
				$stmt = $conn->prepare("INSERT INTO customer VALUES(NULL, ?, ?, ?, ?, ?, ?)");
				$stmt->bind_param("sssssi", $username, $password, $name, $email, $address, $isVeg);
				if($stmt->execute()) {
					$_SESSION['type'] = 'customer';
					$_SESSION['username'] = $username;
					$_SESSION['name'] = $name;
					header('location: ./customer/?info=new-user');
				}
				else {
					header('location: ./?error=no-create&form=register&type=c_content');
				}
			}
			else {
				header('location: ./?error=no-create1&form=register&type=c_content');
			}	
		}
		else {
			header('location: ./?error=no-create1&form=register');
		}
	}
	else {
		header('location: ./?error=no-post&form=register');
	}

?>