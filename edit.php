<?php 
	session_start();
	require './resource/config.php';
	if (!(isset($_SESSION['username']))) {
		header('location: ./');
	}

	if($_SERVER["REQUEST_METHOD"] == "POST") {
		$type = $_POST['type'];
		if ($type == "restaurant") {
			$id = $_POST['id'];
			$name = $_POST['name'];
			$address = $_POST['address'];
			$cuisine = $_POST['cuisine'];

			$stmt = $conn->prepare("SELECT * FROM restaurant WHERE id = ?");
			$stmt->bind_param("i", $id);
			if($stmt->execute()) {
				$stmt->store_result();
				$num = (int)$stmt->num_rows;
				echo $num;
				if ($num == 0) {
					header('location: ./?error=username-exists');
					exit();
				}
				$stmt = $conn->prepare("UPDATE restaurant SET name = ?, address = ?, cuisine = ? WHERE id = ?;");
				$stmt->bind_param("sssi", $name, $address, $cuisine, $id);
				if($stmt->execute()) {
					header('location: ./restaurant/?info=edit-done');
				}
				else {
					header('location: ./?error=no-create');
				}
			}
			else {
				header('location: ./?error=no-create');
			}
		}
		else if ($type == "customer") {
			$id = $_POST['id'];
			$name = $_POST['name'];
			$email = $_POST['email'];
			$address = $_POST['address'];
			$isVeg = 0;
			if (isset($_POST['isVeg']))
				$isVeg = 1;

			$stmt = $conn->prepare("SELECT * FROM customer WHERE id = ?");
			$stmt->bind_param("i", $id);
			if($stmt->execute()) {
				$stmt->store_result();
				$num = (int)$stmt->num_rows;
				if ($num == 0) {
					header('location: ./?error=username-exists');
					exit();
				}
				$stmt = $conn->prepare("UPDATE customer SET name = ?, email = ?, address = ?, isVeg = ? WHERE id = ?;");
				$stmt->bind_param("sssii", $name, $email, $address, $isVeg, $id);
				if($stmt->execute()) {
					header('location: ./customer/?info=edit-done');
				}
				else {
					header('location: ./?error=no-create');
				}
			}
			else {
				header('location: ./?error=no-create1');
			}	
		}
		else {
			header('location: ./?error=no-create1');
		}
	}
	else {
		header('location: ./?error=no-post');
	}

?>