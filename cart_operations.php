<?php
	session_start();
	require './resource/config.php';
	if (isset($_SESSION['username']) && $_SESSION['type'] == 'customer') {
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			switch ($_POST['method']) {
				// When adding product to cart
				case 'insert':
					$quantity = $_POST['quantity'];
					$menuitem_id = $_POST['id'];
					$cost = $_POST['cost'];
					$username = $_SESSION['username'];

					$stmt = $conn->prepare("SELECT m.restaurant_id as restaurant_id FROM cart c, customer cu, menuitem m WHERE cu.username = ? AND cu.id = c.customer_id AND c.menuitem_id = m.id");
					$stmt->bind_param("s", $username);
					$stmt->execute() or trigger_error($conn->error, E_USER_ERROR);
					$result = $stmt->get_result();
					if ($result->num_rows > 0){
						$row = $result->fetch_assoc();
						$restaurant_id = $row['restaurant_id'];

						$stmt = $conn->prepare("SELECT * FROM menuitem WHERE restaurant_id = ? AND id = ?");
						$stmt->bind_param(ii, $restaurant_id, $menuitem_id);
						$stmt->execute() or trigger_error($conn->error, E_USER_ERROR);
						$result = $stmt->get_result();
						if ($result->num_rows == 0) {
							header("location: ./menu.php?id=".$_POST['restaurant_id']."&error=new-restaurant");	
							exit();
						}
					}
					$stmt = $conn->prepare('SELECT * FROM customer WHERE username = ?');
					$stmt->bind_param("s", $username);
					$stmt->execute() or trigger_error($conn->error, E_USER_ERROR);
					$result = $stmt->get_result();
					$row = $result->fetch_assoc();
					$customer_id = $row['id'];

					$total_cost = $cost * $quantity;
					$stmt->bind_param("s", $username);
					$stmt->execute() or trigger_error($conn->error, E_USER_ERROR);
					$result = $stmt->get_result();
					$stmt = $conn->prepare("INSERT INTO cart VALUES(NULL, ?, ?, ?, ?);");
					$stmt->bind_param("iiid", $customer_id, $menuitem_id, $quantity, $total_cost);
					$stmt->execute() or trigger_error($conn->error, E_USER_ERROR);
					header("location: ./menu.php?id=".$_POST['restaurant_id']."&info=insert-done");
					break;
				// When Editing existing items
				case 'edit':
					$quantity = $_POST['quantity'];
					$menuitem_id = $_POST['id'];
					$id = $_POST['cart_id'];
					$cost = $_POST['cost'];
					$username = $_SESSION['username'];
					
					$stmt = $conn->prepare("SELECT * FROM cart c, customer cu WHERE c.id = ? AND cu.username = ? AND c.customer_id = cu.id");
					$stmt->bind_param("is", $id, $username);
					$stmt->execute() or trigger_error($conn->error, E_USER_ERROR);
					$result = $stmt->get_result();
					if ($result->num_rows > 0) {
						$total_cost = $cost * $quantity;
						$stmt = $conn->prepare("UPDATE cart SET quantity = ?, final_cost = ? WHERE id = ?;");
						$stmt->bind_param("idi", $quantity, $total_cost, $id);
						$stmt->execute() or trigger_error($conn->error, E_USER_ERROR);
						// redirecting to menu or cart
						if (isset($_POST['restaurant_id']))
							header("location: ./menu.php?id=".$_POST['restaurant_id']."&info=edit-done");
						else
							header("location: ./cart.php");
					}
					break;
				// Deleting item from cart
				case 'delete':
					$id = $_POST['id'];
					$stmt = $conn->prepare("DELETE FROM cart WHERE id = ?;");
					$stmt->bind_param("i", $id);
					$stmt->execute() or trigger_error($conn->error, E_USER_ERROR);
					header("location: ./cart.php");
					break;
				// Sending cart to order
				case 'order':
					$comments = $_POST['comments'] or '';
					$stmt = $conn->prepare("SELECT c.id as cart_id, cu.id as customer_id, c.menuitem_id, c.quantity, c.final_cost, m.gst as gst, r.id as restaurant_id FROM cart c, customer cu, restaurant r, menuitem m WHERE c.customer_id = cu.id AND cu.username = ? AND c.menuitem_id = m.id AND m.restaurant_id = r.id;");
					$stmt->bind_param("s", $_SESSION['username']);
					$stmt->execute();
					$result = $stmt->get_result();
					if ($result->num_rows == 0) {
						header('location: ./cart.php');
						exit();
					}
					$row = $result->fetch_assoc();
					$restaurant_id = $row['restaurant_id'];
					$customer_id = $row['customer_id'];
					$stmt = $conn->prepare("INSERT INTO orderlist VALUES(NULL, ?, ?, NULL, NULL, NOW(), ?);");
					$stmt->bind_param("iis", $restaurant_id, $customer_id, $comments);
					$stmt->execute() or trigger_error($conn->error, E_USER_ERROR);
					$order_id = $conn->insert_id;
					$total_quantity = 0;
					$total_cost = 0;
					do {
						$total_quantity += $row['quantity'];
						$actual_cost = ($row['final_cost'] * $row['gst'] / 100) + $row['final_cost'];
						$total_cost += $actual_cost;
						$stmt = $conn->prepare("INSERT INTO orderitem VALUES(NULL, ?, ?, ?, ?);");
						$stmt->bind_param("iiid", $order_id, $row['menuitem_id'], $row['quantity'], $row['final_cost']);
						$stmt->execute() or trigger_error($conn->error, E_USER_ERROR);

						$stmt = $conn->prepare("DELETE FROM cart WHERE id = ?;");
						$stmt->bind_param("i", $row['cart_id']);
						$stmt->execute();

					}while ($row = $result->fetch_assoc());

					$stmt = $conn->prepare("UPDATE orderlist SET quantity = ?, final_cost = ? WHERE id = ?");
					$stmt->bind_param("idi", $total_quantity, $total_cost, $order_id);
					$stmt->execute() or trigger_error($conn->error, E_USER_ERROR);
					header("location: ./order.php");
					break;
				case 'delete-all':
					$stmt = $conn->prepare("DELETE FROM cart WHERE customer_id = (SELECT id FROM customer WHERE username = ?);");
					$stmt->bind_param("s", $_SESSION['username']);
					$stmt->execute() or trigger_error($conn->error, E_USER_ERROR);
					header("location: ./menu.php?info=delete-all&id=".$_POST['restaurant_id']);
					break;
				default :
					header('location: ./');
					break;
			}
		}		
	}
?>