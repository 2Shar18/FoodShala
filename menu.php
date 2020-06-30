<?php
	session_start();
	require './resource/config.php';

	if (isset($_GET['id'])) {
		$id = $_GET['id'];
		$stmt = $conn->prepare("SELECT * FROM restaurant WHERE id = ?") or trigger_error($conn->error, E_USER_ERROR);
		$stmt->bind_param("i", $id);
		$stmt->execute() or trigger_error($conn->error, E_USER_ERROR);
		$result = $stmt->get_result();
		if ($result->num_rows > 0){
			$row = $result->fetch_assoc();
			$username = $row['username'];
			$name = $row['name'];
			$self = 0;
			if (isset($_SESSION['username']) && $_SESSION['username'] == $username && $_SESSION['type'] == 'restaurant')
				$self = 1;
		}
		else {
			header('location: ./');
		}
	}
	else {
		if (!isset($_SESSION['username']) || $_SESSION['type'] == 'customer')  {
			header('location: ./');
			exit();
		}
		else {
			$username = $_SESSION['username'];
			$name = $_SESSION['name'];
			$stmt = $conn->prepare("SELECT * FROM restaurant WHERE username = ?") or trigger_error($conn->error, E_USER_ERROR);
			$stmt->bind_param("s", $username);
			$stmt->execute() or trigger_error($conn->error, E_USER_ERROR);
			$result = $stmt->get_result();
			$row = $result->fetch_assoc();
			$id = $row['id'];
			$self = 1;
		}
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
	<?php $page='menu'; require './template/nav.php'; ?>
	<main>
		<h1><?php
			echo $name."'s Menu";
		?></h1>
		<div class="profile" id="details" style="display: block;">
			<?php 
				if ($self) {
			?>
			<button style="width: auto;" onclick="addItem()">Add Items</button>
			<?php } ?>
			<div class="restaurant-list">
				<?php 
				if (isset($_SESSION['username']) && $_SESSION['type'] == 'customer') {
					$stmt = $conn->prepare("SELECT * FROM customer WHERE username = ?");
					$stmt->bind_param("s", $_SESSION['username']);
					$stmt->execute();
					$result = $stmt->get_result();
					$row = $result->fetch_assoc();
				}
				if (isset($row['isVeg']) && ($row['isVeg'] == 1)) {
					$stmt = $conn->prepare("SELECT * FROM menuitem WHERE restaurant_id = ? ORDER BY isVeg DESC");
					echo "<p style='margin: 0;'>Showing Veg items first as per your <a href='./customer/' style='text-decoration: none; color: blue;'>preference</a></p>";
				}
				else {
					$stmt = $conn->prepare("SELECT * FROM menuitem WHERE restaurant_id = ?");
				}
				$stmt->bind_param("i", $id);
				$stmt->execute();
				$result = $stmt->get_result();
				while($row = $result->fetch_assoc()) {
				?>
				<div class="restaurant-item">
					<input type="hidden" class="id" value="<?php echo $row['id']; ?>">
					<input type="hidden" class="name" value="<?php echo $row['name']; ?>">
					<input type="hidden" class="description" value="<?php echo $row['description']; ?>">
					<input type="hidden" class="cost" value="<?php echo $row['cost']; ?>">
					<input type="hidden" class="isVeg" value="<?php echo $row['isVeg']; ?>">
					<input type="hidden" class="gst" value="<?php echo $row['gst']; ?>">
					<p class="r-name"><?php if ($row['isVeg'] == 1){ echo "<span class='veg'></span>"; } else { echo "<span class='non-veg'></span>"; } echo ' '.$row['name']; ?></p>
					<p class="r-description"><?php echo $row['description']; ?></p>
					<p class="r-cost"><?php echo $row['cost']; ?></p>
					<?php 
					if ($self) {
					?>
					<p class="r-menu"><button class="addbtn" onclick="editItem(this)">Edit Item</button><button class= "canbtn" style="float: right;" onclick="deleteItem(this)">Delete Item</button></p>
					<?php
					} else if (isset($_SESSION['username'])){
						if ($_SESSION['type'] == 'customer') {
						$stmt = $conn->prepare("SELECT c.quantity, c.id as id FROM cart c, customer cu WHERE c.menuitem_id = ? and cu.username = ? AND c.customer_id = cu.id");
						$stmt->bind_param("is", $row['id'], $_SESSION['username']);
						$stmt->execute();
						$result1 = $stmt->get_result();
						if ($result1->num_rows > 0) {
							$row1 = $result1->fetch_assoc();
						?>
							<p class="r-menu"><button onclick="selectItem(this)" class="item-select">Edit Cart</button></p>
							<form action="./cart_operations.php" class="cart-form" style="display: none;" method="post">
								<input type="hidden" name="method" value="edit">
								<input type="hidden" name="cost" value="<?php echo $row['cost']; ?>">
								<input type="hidden" name="restaurant_id" value="<?php echo $_GET['id']; ?>">
								<input type="number" name="quantity" value="<?php echo $row1['quantity']; ?>" class="quantity" min="1" max="10">
								<button class="addbtn" type="submit">Update Cart</button>
								<button class="canbtn" type="button" onclick="cancelSelect(this)">Cancel</button>
							<?php
							echo "<input type='hidden' name='id' value='".$row['id']."'>";
							echo "<input type='hidden' name='cart_id' value='".$row1['id']."'>";
							?>
							</form>
					<?php
						} else {
					?>
					<p class="r-menu"><button onclick="selectItem(this)" class="item-select">Order</button></p>
					<form action="./cart_operations.php" class="cart-form" style="display: none;" method="post">
						<input type="hidden" name="method" value="insert">
						<input type="hidden" name="cost" value="<?php echo $row['cost']; ?>">
						<input type="hidden" name="restaurant_id" value="<?php echo $_GET['id']; ?>">
						<input type="number" name="quantity" value="1" class="quantity" min="1" max="10">
						<button class="addbtn" type="submit">Add to Cart</button>
						<button class="canbtn" type="button" onclick="cancelSelect(this)">Cancel</button>
					<?php
					echo "<input type='hidden' name='id' value='".$row['id']."'>";
					?>
					</form>
					<?php
						}
						}
					} else {
					?>
						<button type="submit" onclick="document.getElementById('login_form').style.display='block'">Login to buy</button>
					<?php
					} 
					?>
					
				</div>
			<?php } ?>
			</div>
		</div>
		<div id="delete_cart_form" class="modal">
		<span onclick="document.getElementById('delete_cart_form').style.display='none'" class="close" title="Close Modal">&times;</span>
			<div class="modal-content">
				<div class="container">
					<div style="display: block;" class="tab-content">
						<h1>You have tried to Add Item from different Restaurant</h1>
						<form method="post" action="./cart_operations.php" class="form-input">
							<input type="hidden" name="method" value="delete-all">
							<input type="hidden" name="restaurant_id" value="<?php echo $_GET['id']; ?>">
							<p class="r-name">Do you wish to delete the previous cart Items?</p>

							<div class="clearfix">
								<button type="button" onclick="document.getElementById('delete_cart_form').style.display='none'" class="cancelbtn">Cancel</button>
								<button type="submit" class="loginbtn">Yes, Delete</button>
							</div>
						</form>	
					</div>
				</div>
			</div>
		</div>
		<?php 
		if ($self) { ?>
		<div id="menu_item_form" class="modal">
		<span onclick="document.getElementById('menu_item_form').style.display='none'" class="close" title="Close Modal">&times;</span>
			<div class="modal-content">
				<div class="container">
					<div id="c_content_l" style="display: block;" class="tab-content">
						<h1>Add new Menu Item</h1>
						<hr>
						<form method="post" action="./menu_operation.php" class="form-input">
							<input type="hidden" name="type" value="customer">
							<input type="hidden" name="id" class="id">
							<input type="hidden" name="res_id" value="<?php echo $id;?>">
							<input type="hidden" name="method" class="method" value="insert">

							<label for="name"><b>Dish Name</b></label>
							<input type="text" placeholder="Enter Dish Name" name="name" class = "name" required>

							<label for="description"><b>Description</b></label>
							<textarea placeholder="Enter Description" name="description" class = "description" required></textarea>
							
							<label for="cost"><b>Cost</b></label>
							<input type="number" placeholder="Enter Cost" name="cost" min='1' class = "cost" required>

							<label for="gst"><b>GST%</b></label>
							<input type="number" value="5" name="gst" min='0' class = "gst">

							<label for="isVeg"><b>Is it Veg?</b>
								<input type="checkbox" name="isVeg" class = "isVeg" style="margin-bottom:15px">
							</label>

							<div class="clearfix">
								<button type="button" onclick="document.getElementById('menu_item_form').style.display='none'" class="cancelbtn">Cancel</button>
								<button type="submit" class="loginbtn">Add Item</button>
							</div>
						</form>	
					</div>
				</div>
			</div>
		</div>
		<?php } ?>
	</main>
	<script type="text/javascript">
		<?php 
			if(isset($_GET['error'])) {
				switch ($_GET['error']) {
					case 'new-restaurant':
					?>
					document.getElementById('delete_cart_form').style.display = 'block';
					<?php
						break;
				}
			}

			if(isset($_GET['info'])) {
				switch($_GET['info']) {
					case 'edit-done':
						echo "alert('Cart Updated Successfully');";
						break;
					case 'insert-done':
						echo "alert('Item added to cart Successfully');";
						break;
					case 'delete-all':
						echo "alert('Your cart has been deleted, You can add new Items');";
						break;
				}
			}

		?>
		function addItem() {
			form = document.getElementById('menu_item_form');
			inputs = form.getElementsByTagName('input');
			for (let i = 4; i < inputs.length; i++) {
				inputs[i].value = "";
				inputs[i].checked = false;
			}
			gst = form.getElementsByClassName('gst')[0].value = 5;
			form.getElementsByTagName('textarea')[0].value = "";
			form.getElementsByTagName('h1')[0].innerHTML = "Add new Menu Item";
			form.getElementsByClassName('loginbtn')[0].textContent = 'Add Item';
			form.style.display='block';
		}
		
		function editItem(ele) {
			parent = ele.parentElement.parentElement;
			id = parent.getElementsByClassName('id')[0].value;
			name = parent.getElementsByClassName('name')[0].value;
			description = parent.getElementsByClassName('description')[0].value;
			cost = parent.getElementsByClassName('cost')[0].value;
			isVeg = parent.getElementsByClassName('isVeg')[0].value;
			gst = parent.getElementsByClassName('gst')[0].value;

			form = document.getElementById('menu_item_form').getElementsByClassName('form-input')[0];
			document.getElementById('menu_item_form').getElementsByTagName('h1')[0].innerHTML = "Edit this Menu Item";
			
			form.getElementsByClassName('id')[0].value = id;
			form.getElementsByClassName('method')[0].value = 'edit';
			form.getElementsByClassName('name')[0].value = name;
			form.getElementsByClassName('description')[0].value = description;
			form.getElementsByClassName('cost')[0].value = cost;
			form.getElementsByClassName('gst')[0].value = gst;
			form.getElementsByClassName('loginbtn')[0].textContent = 'Edit Item';
			if (isVeg == 1)
				form.getElementsByClassName('isVeg')[0].checked = true;
			else
				form.getElementsByClassName('isVeg')[0].checked = false;

			document.getElementById('menu_item_form').style.display = "block";
		}

		function deleteItem(ele) {
			parent = ele.parentElement.parentElement;
			id = parent.getElementsByClassName('id')[0].value;
			name = parent.getElementsByClassName('name')[0].value;
			description = parent.getElementsByClassName('description')[0].value;
			cost = parent.getElementsByClassName('cost')[0].value;
			isVeg = parent.getElementsByClassName('isVeg')[0].value;

			form = document.getElementById('menu_item_form').getElementsByClassName('form-input')[0];
			document.getElementById('menu_item_form').getElementsByTagName('h1')[0].innerHTML = "Do you really want to delete this Item?";
			
			form.getElementsByClassName('id')[0].value = id;
			form.getElementsByClassName('method')[0].value = 'delete';
			form.getElementsByClassName('name')[0].value = name;
			form.getElementsByClassName('name')[0].disabled = true;
			form.getElementsByClassName('description')[0].value = description;
			form.getElementsByClassName('description')[0].disabled = true;
			form.getElementsByClassName('cost')[0].value = cost;
			form.getElementsByClassName('cost')[0].disabled = true;
			form.getElementsByClassName('loginbtn')[0].textContent = 'Delete Item';
			if (isVeg == 1)
				form.getElementsByClassName('isVeg')[0].checked = true;
			else
				form.getElementsByClassName('isVeg')[0].checked = false;
			form.getElementsByClassName('isVeg')[0].disabled = true;

			document.getElementById('menu_item_form').style.display = "block";
		}

		function selectItem(ele) {
			parent = ele.parentElement.parentElement;
			grand = parent.parentElement;
			forms = grand.getElementsByTagName('form');
			for(let i = 0; i < forms.length; i++) {
				forms[i].style.display = "none";
			}
			items = grand.getElementsByClassName('r-menu');
			for(let i = 0; i < items.length; i++) {
				items[i].style.display = "block";
			}
			ele = parent.getElementsByClassName('r-menu')[0];
			ele.style.display = "none";
			parent.getElementsByTagName('form')[0].style.display = "block";
		}
		function cancelSelect(ele) {
			parent = ele.parentElement;
			grand = parent.parentElement;
			grand.getElementsByClassName('r-menu')[0].style.display = "block";
			parent.style.display = "none";
		}
	</script>
</body>
</html>