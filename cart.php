<?php
	session_start();
	require './resource/config.php';
	if (!isset($_SESSION['username']) || $_SESSION['type'] != 'customer') {
		header('location: ./');
	}
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
	<?php $page='cart'; require './template/nav.php'; ?>
	<main>
		<h3>Showing entries of your cart</h3>
				<?php 
				$stmt = $conn->prepare("SELECT c.id as id, c.menuitem_id as menuitem_id, c.quantity as quantity, c.final_cost as final_cost, m.name as menuitem_name, m.description as description, m.isVeg as isVeg, m.gst as gst, r.name as restaurant_name, r.id as restaurant_id, m.cost as cost FROM cart c, menuitem m, restaurant r, customer cu WHERE cu.username = ? AND cu.id = c.customer_id AND c.menuitem_id = m.id AND m.restaurant_id = r.id;");
				$stmt->bind_param("s", $_SESSION['username']);
				$stmt->execute();
				$result = $stmt->get_result();
				if ($result->num_rows == 0) {
					echo "<h3>You don't have any item in your cart</h3>";	
				}
				else {
				$row = $result->fetch_assoc();
				$total_cost = 0;
				$tax_cost = 0;
				echo "<div><h3 style='display: inline-block;' id='restaurant_name'>Restaurant: ".$row['restaurant_name']."</h3><h3 style='display: inline-block;margin-left: 1%;'><a href='./menu.php?id=".$row['restaurant_id']."' style='text-decoration: none; color: blue;'>Continue Ordering</a></h3><button style='float:right;display: inline-block; width:auto;' onclick='checkout()'>Checkout</button><h3 style='float: right; display: inline-block; margin-right: 1%;' id='total_cost'></h3></div>";
				echo "<div class='restaurant-list'>";
				do {
					$tax = $row['final_cost'] * $row['gst'] / 100;
					$tax_cost += $tax;
					$total_cost += $row['final_cost'];

				?>
				<div class="restaurant-item">
					<input type="hidden" class="id" value="<?php echo $row['id']; ?>">
					<!-- <input type="hidden" class="cost" value="<?php echo $row['final_cost']; ?>"> -->
					<p class="r-name"><?php if ($row['isVeg'] == 1){ echo "<span class='veg'></span>"; } else { echo "<span class='non-veg'></span>"; } echo ' '.$row['menuitem_name'];?></p>
					<p class="r-description"><?php echo $row['description']; ?></p>
					<p class="r-cost"><?php echo $row['final_cost']; ?></p>
					
					<form action="./cart_operations.php" class="cart-form" method="post">
						<input type="hidden" name="method" value="edit">
						<input type="hidden" name="name" value="<?php echo $row['menuitem_name']; ?>" class="name">
						<input type="hidden" name="isVeg" value="<?php echo $row['isVeg']; ?>" class="isVeg">
						<input type="hidden" name="description" value="<?php echo $row['description']; ?>" class="description">
						<input type="hidden" name="final_cost" value="<?php echo $row['final_cost']; ?>" class="final_cost">
						<input type="hidden" name="id" value="<?php echo $row['menuitem_id']; ?>">
						<input type="hidden" name="cart_id" value="<?php echo $row['id']; ?>" class="id">
						<input type="hidden" name="cost" value="<?php echo $row['cost']; ?>">
						<input type="number" name="quantity" value="<?php echo $row['quantity']?>" class="quantity" min="1" max="10">
						<button class="addbtn" type="submit">Edit Cart</button>
						<button class="canbtn" type="button" onclick="deleteCart(this)">Remove</button>
					</form>
				</div>
			<?php }while($row = $result->fetch_assoc()); 
				echo "<script>document.getElementById('total_cost').innerHTML = 'Total Cost: '+".$total_cost."+' + '+".$tax_cost."+'(tax)';</script>";
			}?>
			</div>
		<div id="delete_cart_form" class="modal">
		<span onclick="document.getElementById('delete_cart_form').style.display='none'" class="close" title="Close Modal">&times;</span>
			<div class="modal-content">
				<div class="container">
					<div style="display: block;" class="tab-content">
						<h1>Delete Item from Cart?</h1>
						<hr>
						<form method="post" action="./cart_operations.php" class="form-input">
							<input type="hidden" class="id" name="id">
							<input type="hidden" name="method" value="delete">
							<!-- <input type="hidden" class="cost" value="<?php echo $row['final_cost']; ?>"> -->
							<p class="r-name"></p>
							<p class="r-description"></p>
							<p class="r-cost"></p>
							<p class="r-quantity"></p>

							<div class="clearfix">
								<button type="button" onclick="document.getElementById('delete_cart_form').style.display='none'" class="cancelbtn">Cancel</button>
								<button type="submit" class="loginbtn">Yes, Delete</button>
							</div>
						</form>	
					</div>
				</div>
			</div>
		</div>
		<div id="order_form" class="modal">
		<span onclick="document.getElementById('order_form').style.display='none'" class="close" title="Close Modal">&times;</span>
			<div class="modal-content">
				<div class="container">
					<div style="display: block;" class="tab-content">
						<h1>Checkout</h1>
						<hr>
						<form method="post" action="./cart_operations.php" class="form-input">
							<input type="hidden" name="method" value="order">
							<p class="r-name"></p>
							<p class="r-description"></p>
							<label for="comments"><b>Additional Comments</b></label>
							<input type="text" placeholder="Enter Comments (optional)" name="comments" class = "comments">
							<div class="clearfix">
								<button type="button" onclick="document.getElementById('order_form').style.display='none'" class="cancelbtn">Cancel</button>
								<button type="submit" class="loginbtn">Checkout</button>
							</div>
						</form>	
					</div>
				</div>
			</div>
		</div>
	</main>

	<script type="text/javascript">
		function deleteCart(ele) {
			parent = ele.parentElement;
			id = parent.getElementsByClassName('id')[0].value;
			name = parent.getElementsByClassName('name')[0].value;
			description = parent.getElementsByClassName('description')[0].value;
			final_cost = parent.getElementsByClassName('final_cost')[0].value;
			quantity = parent.getElementsByClassName('quantity')[0].value;
			isVeg = parent.getElementsByClassName('isVeg')[0].value;

			form = document.getElementById('delete_cart_form').getElementsByClassName('form-input')[0];
			form.getElementsByClassName('id')[0].value = id;
			form.getElementsByClassName('r-name')[0].innerHTML = "<span class='non-veg'> " + name;
			if (isVeg == 1)
				form.getElementsByClassName('r-name')[0].innerHTML = "<span class='veg'>" + name;
			form.getElementsByClassName('r-description')[0].innerHTML = description;
			form.getElementsByClassName('r-cost')[0].innerHTML = final_cost;
			form.getElementsByClassName('r-quantity')[0].innerHTML = quantity;
			document.getElementById('delete_cart_form').style.display = "block";
		}

		function checkout(){
			form = document.getElementById('order_form').getElementsByClassName('form-input')[0];
			restaurant = document.getElementById('restaurant_name').innerHTML;
			cost = document.getElementById('total_cost').innerHTML;
			console.log(restaurant);
			console.log(cost);
			form.getElementsByClassName('r-name')[0].innerHTML = restaurant;
			form.getElementsByClassName('r-description')[0].innerHTML = cost;
			document.getElementById('order_form').style.display = "block";
		}
	</script>
</body>
</html>