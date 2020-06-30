<?php
	session_start();
	require './resource/config.php';
?>
<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="./resource/style.css">
</head>
</head>
<body>
	<!-- Adding Navbar -->
	<?php $page='order'; require './template/nav.php'; ?>
	<main>
		<h3>Showing Orders</h3>
		<?php
			// Order list for customer
			if ($_SESSION['type'] == 'customer') {
				$stmt = $conn->prepare('SELECT ol.id as id, r.name as restaurant_name, ol.date, ol.quantity, ol.final_cost, ol.comments FROM orderlist ol, restaurant r, customer c WHERE c.username = ? AND c.id = ol.customer_id AND ol.restaurant_id = r.id ORDER BY ol.id DESC;');
				$stmt->bind_param('s', $_SESSION['username']);
				$stmt->execute() or trigger_error($conn->error, E_USER_ERROR);
				$result = $stmt->get_result();
				if ($result->num_rows == 0) {
					echo "<h3>You don't have any order history</h3>";
				}
				else {
					echo "<div class='restaurant-list'>";
					while ($row = $result->fetch_assoc()) {
		?>
		<!-- Individual Orders -->
		<div class="order-item">
			<table width="100%" style="text-align: center;">
			<tr>
				<td><p class="o-id"><?php echo $row['id']; ?></p></td>
				<td><p class="o-restaurant"><?php echo $row['restaurant_name']; ?></p></td>
				<td><p class="o-date"><?php echo $row['date']; ?></p></td>
			</tr>
			<tr>
				<td><p class="o-quantity"><?php echo $row['quantity']; ?></p></td>
				<td><p class="o-cost"><?php echo $row['final_cost']; ?></p></td>
				<td><p class="o-comments"><?php if ($row['comments'] == '') { echo " -----"; } else { echo $row['comments']; }; ?></p></td>
			</tr>
			<tr>
				<td></td>
				<td><?php 
					echo "<button onclick=orderDetails(".$row['id'].") class='addbtn'>View Details</button>";
					?>
					</td>
			</tr>
			</table>
			
		</div>
		<!-- List of Order items in each order -->
		<div id="<?php echo $row['id']; ?>" class="modal">
		<span onclick="closeDetail(<?php echo $row['id'];?>)" class="close" title="Close Modal">&times;</span>
			<div class="modal-content">
				<div class="container">
					<div style="display: block;" class="tab-content">
						<h1>Order Details</h1>
						<?php
							$stmt = $conn->prepare("SELECT m.name, m.isVeg, m.description, oi.quantity, oi.final_cost as cost FROM menuitem m, orderitem oi WHERE oi.order_id = ? AND oi.menuitem_id = m.id;");
							$stmt->bind_param("i", $row['id']);
							$stmt->execute();
							$result1 = $stmt->get_result();
							$total_cost = 0;
							while($row1 = $result1->fetch_assoc()) {
								$total_cost += $row1['cost'];
						?>
						<div class="order-item">
							<p class="r-name"><?php if ($row1['isVeg'] == 1){ echo "<span class='veg'></span>"; } else { echo "<span class='non-veg'></span>"; } echo ' '.$row1['name']; ?></p>
							<p class="r-description"><?php echo $row1['description']; ?></p>
							<p class="r-quantity"><?php echo $row1['quantity']; ?></p>
							<p class="r-cost"><?php echo $row1['cost']; ?></p>
						</div>
						<?php } 
							$tax = $row['final_cost'] - $total_cost;
						?>
						<div class="order-item">
							<p class='r-description'><b>Total Cost: &ensp;<?php echo $total_cost; ?></b></p>
							<p class='r-description'><b></b></p>
							<p class='r-description'><b>Tax: &emsp;&emsp;&emsp;+<?php echo $tax; ?></b></p>
							<p class='r-description'><b></b></p>
							<p class='r-description'><b>Final Cost: &nbsp;<?php echo $row['final_cost']; ?></b></p>
						</div>
						<button type="button" onclick="closeDetail(<?php echo $row['id'];?>)" class="canbtn">Close</button>
					</div>
				</div>
			</div>
		</div>
		<?php
					}
					echo "</div>";
				}
			}
			// Order list for restaurant
			else if ($_SESSION['type'] == 'restaurant') {
				$stmt = $conn->prepare('SELECT ol.id as id, c.name as customer_name, ol.date, ol.quantity, ol.final_cost, ol.comments FROM orderlist ol, restaurant r, customer c WHERE r.username = ? AND r.id = ol.restaurant_id AND ol.customer_id = c.id ORDER BY ol.id DESC;');
				$stmt->bind_param('s', $_SESSION['username']);
				$stmt->execute() or trigger_error($conn->error, E_USER_ERROR);
				$result = $stmt->get_result();
				if ($result->num_rows == 0) {
					echo "<h3>You don't have any order history</h3>";
				}
				else {
					echo "<div class='restaurant-list'>";
					while ($row = $result->fetch_assoc()) {
		?>
		<!-- Individual Orders -->
		<div class="order-item">
			<table width="100%" style="text-align: center;">
			<tr>
				<td><p class="o-id"><?php echo $row['id']; ?></p></td>
				<td><p class="o-customer"><?php echo $row['customer_name']; ?></p></td>
				<td><p class="o-date"><?php echo $row['date']; ?></p></td>
			</tr>
			<tr>
				<td><p class="o-quantity"><?php echo $row['quantity']; ?></p></td>
				<td><p class="o-cost"><?php echo $row['final_cost']; ?></p></td>
				<td><p class="o-comments"><?php if ($row['comments'] == '') { echo " -----"; } else { echo $row['comments']; }; ?></p></td>
			</tr>
			<tr>
				<td></td>
				<td><?php 
					echo "<button onclick=orderDetails(".$row['id'].") class='addbtn'>View Details</button>";
					?>
					</td>
			</tr>
			</table>
			
		</div>
		<!-- List of Order items in each order -->
		<div id="<?php echo $row['id']; ?>" class="modal">
		<span onclick="closeDetail(<?php echo $row['id'];?>)" class="close" title="Close Modal">&times;</span>
			<div class="modal-content">
				<div class="container">
					<div style="display: block;" class="tab-content">
						<h1>Order Details</h1>
						<?php
							$stmt = $conn->prepare("SELECT m.name, m.isVeg, m.description, oi.quantity, oi.final_cost as cost FROM menuitem m, orderitem oi WHERE oi.order_id = ? AND oi.menuitem_id = m.id;");
							$stmt->bind_param("i", $row['id']);
							$stmt->execute();
							$result1 = $stmt->get_result();
							$total_cost = 0;
							while($row1 = $result1->fetch_assoc()) {
								$total_cost += $row1['cost'];
						?>
						<div class="order-item" style="width: 90%;">
							<p class="r-name"><?php echo $row1['name'].' '; if ($row1['isVeg'] == 1){ echo "<span class='veg'></span>"; } else { echo "<span class='non-veg'></span>"; }?></p>
							<p class="r-description"><?php echo $row1['description']; ?></p>
							<p class="r-quantity"><?php echo $row1['quantity']; ?></p>
							<p class="r-cost"><?php echo $row1['cost']; ?></p>
						</div>
						<?php } 
							$tax = $row['final_cost'] - $total_cost;
						?>
						<div class="order-item" style="width: 90%;">
							<p class='r-description'><b>Total Cost: &ensp;<?php echo $total_cost; ?></b></p>
							<p class='r-description'><b></b></p>
							<p class='r-description'><b>Tax: &emsp;&emsp;&emsp;+<?php echo $tax; ?></b></p>
							<p class='r-description'><b></b></p>
							<p class='r-description'><b>Final Cost: &nbsp;<?php echo $row['final_cost']; ?></b></p>
						</div>
						<button type="button" onclick="closeDetail(<?php echo $row['id'];?>)" class="canbtn">Close</button>
					</div>
				</div>
			</div>
		</div>
		<?php
					}
					echo "</div>";
				}
			}
		?>
	</main>
	<script type="text/javascript">
		function orderDetails(id) {
			document.getElementById(id).style.display = 'block';
		}
		function closeDetail(id) {
			document.getElementById(id).style.display = 'none';	
		}
	</script>
</body>