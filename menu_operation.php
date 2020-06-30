<?php
	session_start();
	require './resource/config.php';
	if (isset($_SESSION['username']) && $_SESSION['type'] == 'restaurant') {
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			switch ($_POST['method']) {
				// When adding new item
				case 'insert':
					$restaurant_id = $_POST['res_id'];
					$name = $_POST['name'];
					$description = $_POST['description'];
					$isVeg = 0;
					if (isset($_POST['isVeg']))
						$isVeg = 1;
					$cost = $_POST['cost'];
					$gst = $_POST['gst'];
					$stmt = $conn->prepare("INSERT INTO menuitem VALUES(NULL, ?, ?, ?, ?, ?, ?);");
					$stmt->bind_param("issidd", $restaurant_id, $name, $description, $isVeg, $cost, $gst);
					break;
				// When editing current item
				case 'edit':
					$restaurant_id = $_POST['res_id'];
					$name = $_POST['name'];
					$description = $_POST['description'];
					$isVeg = 0;
					if (isset($_POST['isVeg']))
						$isVeg = 1;
					$cost = $_POST['cost'];
					$gst = $_POST['gst'];
					$id = $_POST['id'];
					$stmt = $conn->prepare("UPDATE menuitem SET name = ?, description = ?, isVeg = ?, cost = ?, gst = ? WHERE id = ?;");
					$stmt->bind_param("ssiddi", $name, $description, $isVeg, $cost, $gst, $id);
					break;
				// When deleting the item
				case 'delete':
					$id = $_POST['id'];
					$stmt = $conn->prepare("DELETE FROM menuitem WHERE id = ?;");
					$stmt->bind_param("i", $id);
					break;
				default :
					header('location: ./menu.php');
					break;
			}
			$stmt->execute() or trigger_error($conn->error, E_USER_ERROR);
			header('location: ./menu.php?info=done');
		}		
	}
?>