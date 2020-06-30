<?php 
	session_start();
	require './resource/config.php';
	if (isset($_SESSION['username'])) {
		header('location: ./');
	}

	if($_SERVER["REQUEST_METHOD"] == "POST") {
		$type = $_POST['type'];
		// Secret Page
		if ($_POST['username'] == 'internshala' && $_POST['psw'] == '123') {
			$_SESSION['usr'] = $_POST['username'];
			header('location: ./internshala.php');
			exit();
		}
		// Restaurant Login
		if ($type == "restaurant") {
			echo "restaurant";
			$username = $_POST['username'];
			$password = $_POST['psw'];
			
			$stmt = $conn->prepare("SELECT * FROM restaurant WHERE username = ? AND password = ?") or trigger_error($conn->error, E_USER_ERROR);
			$stmt->bind_param("ss", $username, $password);
			if($stmt->execute()) {
				$result = $stmt->get_result();
				$num = (int)$result->num_rows;
				if ($num == 1) {
					$_SESSION['type'] = 'restaurant';
					$_SESSION['username'] = $username;
					$row = $result->fetch_assoc();
					$_SESSION['name'] = $row['name'];
					header('location: ./');
					exit();
				}
				else {
					header('location: ./?error=no-user&form=login&type=r_content_l');
				}
			}
			else {
				header('location: ./?error=no-create1&form=login&type=r_content_l');
			}
		}
		// Customer Login
		else if ($type == "customer") {
			$username = $_POST['username'];
			$password = $_POST['psw'];
			$stmt = $conn->prepare("SELECT * FROM customer WHERE username = ? AND password = ?");
			$stmt->bind_param("ss", $username, $password);
			if($stmt->execute()) {
				$result = $stmt->get_result();
				$num = (int)$result->num_rows;
				if ($num == 1) {
					$_SESSION['type'] = 'customer';
					$_SESSION['username'] = $username;
					$row = $result->fetch_assoc();
					$_SESSION['name'] = $row['name'];
					header('location: ./');
					exit();
				}
				else {
					header('location: ./?error=no-user&form=login&type=c_content_l');
				}
			}
			else {
				header('location: ./?error=no-create1&form=login&type=c_content_l');
			}	
		}
		else {
			header('location: ./?error=no-create1&form=login');
		}
	}
	else {
		header('location: ./?error=no-post&form=login');
	}

?>