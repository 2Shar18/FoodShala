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
	<!-- <div style="margin-top: 5px;"></div> -->
	<section class="carousel" aria-label="Gallery">
			<ol class="carousel__viewport">
				<li id="carousel__slide1"
						tabindex="0"
						class="carousel__slide">
						<img src="resource/food_1.jpg">
						<div class="centered">Easy Access</div>
					<div class="carousel__snapper">
						<a href="#carousel__slide4"
							 class="carousel__prev">Go to last slide</a>
						<a href="#carousel__slide2"
							 class="carousel__next">Go to next slide</a>
					</div>
				</li>
				<li id="carousel__slide2"
						tabindex="0"
						class="carousel__slide">	
						<img src="resource/food_2.jpg">
						<div class="centered">Fast Delivery</div>
					<div class="carousel__snapper">
						<a href="#carousel__slide1"
							 class="carousel__prev">Go to previous slide</a>
						<a href="#carousel__slide3"
							 class="carousel__next">Go to next slide</a>
					</div>
				</li>
				<li id="carousel__slide3"
						tabindex="0"
						class="carousel__slide">
						<img src="resource/food_3.jpg">
						<div class="centered">Treat Yourself</div>
					<div class="carousel__snapper">
						<a href="#carousel__slide2"
							 class="carousel__prev">Go to previous slide</a>
						<a href="#carousel__slide4"
							 class="carousel__next">Go to next slide</a>
					</div>
				</li>
				<li id="carousel__slide4"
						tabindex="0"
						class="carousel__slide">
						<img src="resource/food_4.jpg">
						<div class="centered">Fulfil your craving</div>
					<div class="carousel__snapper">
						<a href="#carousel__slide3"
							 class="carousel__prev">Go to previous slide</a>
						<a href="#carousel__slide1"
							 class="carousel__next">Go to first slide</a>
					</div>
				</li>
			</ol>
			<aside class="carousel__navigation">
				<ol class="carousel__navigation-list">
					<li class="carousel__navigation-item">
						<a href="#carousel__slide1"
							 class="carousel__navigation-button">Go to slide 1</a>
					</li>
					<li class="carousel__navigation-item">
						<a href="#carousel__slide2"
							 class="carousel__navigation-button">Go to slide 2</a>
					</li>
					<li class="carousel__navigation-item">
						<a href="#carousel__slide3"
							 class="carousel__navigation-button">Go to slide 3</a>
					</li>
					<li class="carousel__navigation-item">
						<a href="#carousel__slide4"
							 class="carousel__navigation-button">Go to slide 4</a>
					</li>
				</ol>
			</aside>
		</section>
	<?php $page='home'; require './template/nav.php'; ?>
	<main>
		<h3>Showing nearby restaurants</h3>
		<div class="restaurant-list">
			<?php
			$stmt = "SELECT * FROM restaurant";
			$result = $conn->query($stmt);
			while($row = $result->fetch_assoc()) {
			?>
			<div class="restaurant-item">
				<p class="r-name"><?php echo "<a href='./menu.php?id=".$row['id']."'>".$row['name']."</a>"; ?></p>
				<p class="r-address"><?php echo $row['address']; ?></p>
				<p class="r-cuisine"><?php echo $row['cuisine']; ?></p>
				<form action="./menu.php">
				<?php
				echo "<input type='hidden' name='id' value='".$row['id']."'>";
				?>
				<p class="r-menu"><button type="submit">View Menu</button></p>
				</form>
			</div>
			<?php 
			}
			?>
		</div>
	</main>

	<script>
		var r_modal = document.getElementById('register_form');
		var l_modal = document.getElementById('login_form');
		<?php
			if (isset($_GET['error'])) {
				switch ($_GET['error']) {
					case 'no-user':
						echo "alert('Username or Password is Invalid');";
						break;
					case 'no-post':
						echo "alert('Improper request, try to fill the form again');";
						break;
					case 'username-exists':
						echo "alert('Username / Email already exists, try logging in!');";
						break;
					case 'food':
						echo "alert('Login here to buy food!');";
						break;
					default:
						echo "alert('Some error occured! Please try again later');";
						break;
				}
				if (isset($_GET['form']))
				switch ($_GET['form']) {
					case 'register':
						echo "r_modal.style.display = 'block';";
						break;
					case 'login':
						echo "l_modal.style.display = 'block';";
						break;
				}
				if (isset($_GET['type'])) {
					echo "document.getElementById('".$_GET['type']."').style.display = 'block';";
					switch ($_GET['type']) {
						case 'r_content':
							echo "document.getElementById('c_content').style.display = 'none';";
							break;
						case 'r_content_l':
							echo "document.getElementById('c_content_l').style.display = 'none';";
							break;
					}
				}
			}
		?>
	</script>
</body>
</html>