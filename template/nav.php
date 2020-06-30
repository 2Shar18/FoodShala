<?php
 $_SESSION['usr'] = 'blank';
?>
<!-- NavBar -->
<header>
    <div class="navbar" id="topNav">
        <a href="/FoodShala/" id="home-tab">Home</a>
        <?php
        if (isset($_SESSION['username'])) {
            if ($_SESSION['type'] == 'restaurant' || $page == 'menu') {
                echo "<a href='/FoodShala/menu.php' id='menu-tab'>Menu</a>";
            }
            echo "<a href='/FoodShala/order.php' id='order-tab'>View orders</a>";
            echo "<a href='/FoodShala/logout.php' class='right'>Logout</a>";
            echo "<a href='/FoodShala/".$_SESSION['type']."' class='right' id='profile-tab'>Profile</a>";
            if ($_SESSION['type'] == 'customer') {
                $stmt = $conn->prepare("SELECT * FROM cart WHERE customer_id = (SELECT id from customer WHERE username = ?)");
                $stmt->bind_param("s", $_SESSION['username']);
                $stmt->execute();
                $res = $stmt->get_result();
                $num = $res->num_rows;
                if($num > 0)
                    echo "<a href='/FoodShala/cart.php' class='right' id='cart-tab'>Cart <span class='ping'>".$num."</span></a>";
                else 
                    echo "<a href='/FoodShala/cart.php' class='right' id='cart-tab'>Cart</a>";
            }
        }
        else {
        ?>
        <a onclick="document.getElementById('register_form').style.display='block'" class='right'>Register</a>
        <a onclick="document.getElementById('login_form').style.display='block'; console.log('Try Username: internshala & pass: 123')" class='right'>Login</a>
        <?php
        }
        ?>
        <a href="javascript:void(0);" class="icon" onclick="myFunction()">
            <i class="fa fa-bars"></i>
        </a>
    </div>

<!-- Registration Form -->
    <div id="register_form" class="modal" >
        <span onclick="document.getElementById('register_form').style.display='none'" class="close" title="Close Modal">&times;</span>
        <div class="modal-content">
            <div class="container">
                <div style="width: fit-content; margin: auto;" class="tab-switch">
                    <span onclick="switchTab(this, 'r_r_switch', 'c_content', 'r_content')" class="tab-item sel" id="c_r_switch">Customer</span>
                    <span onclick="switchTab(this, 'c_r_switch', 'r_content', 'c_content')" class="tab-item" id="r_r_switch">Restaurant</span>
                </div>
                <div id="c_content" style="display: block;" class="tab-content">
                    <h1>Customer Registeration Form</h1>
                    <p>Please fill in this form to create an account at FoodShala.</p>
                    <hr>
                    <form method="post" action="./registration.php" class="form-input">
                        <input type="hidden" name="type" value="customer">

                        <label for="username"><b>Username</b></label>
                        <input type="text" placeholder="Enter Username" name="username" required>

                        <label for="name"><b>Full Name</b></label>
                        <input type="text" placeholder="Enter Full Name" name="name" required>

                        <label for="email"><b>Email</b></label>
                        <input type="email" placeholder="Enter Email" name="email" required>

                        <label for="psw"><b>Password</b></label>
                        <input type="password" placeholder="Enter Password" name="psw" required>

                        <label for="psw-repeat"><b>Repeat Password</b></label>
                        <input type="password" placeholder="Repeat Password" name="psw-repeat" required>

                        <label for="address"><b>Address</b></label>
                        <textarea placeholder="Enter Address" name="address" required></textarea>
       
                        <label for="isVeg"><b>Do you prefer Veg?</b>
                            <input type="checkbox" name="isVeg" style="margin-bottom:15px">
                        </label>

                        <div class="clearfix">
                            <button type="button" onclick="document.getElementById('register_form').style.display='none'" class="cancelbtn">Cancel</button>
                            <button type="submit" class="signupbtn">Sign Up</button>
                        </div>
                    </form>
                </div>
                <div id="r_content" style="display: none;" class="tab-content">
                    <h1>Restaurant Registeration Form</h1>
                    <p>Please fill in this form to create an account at FoodShala.</p>
                    <hr>
                    <form method="post" action="./registration.php" class="form-input">
                        <input type="hidden" name="type" value="restaurant">

                        <label for="username"><b>Username</b></label>
                        <input type="text" placeholder="Enter Username" name="username" required>

                        <label for="name"><b>Restaurant's Name</b></label>
                        <input type="text" placeholder="Enter Restaurant's Name" name="name" required>

                        <label for="psw"><b>Password</b></label>
                        <input type="password" placeholder="Enter Password" name="psw" required>

                        <label for="psw-repeat"><b>Repeat Password</b></label>
                        <input type="password" placeholder="Repeat Password" name="psw-repeat" required>

                        <label for="address"><b>Address</b></label>
                        <textarea placeholder="Enter Address" name="address" required></textarea>
       
                        <label for="cuisine"><b>Cuisines</b></label>
                        <p style="font-size: 9px; margin: 0;">(Enter Comma Separated Cuisines)</p>
                        <textarea placeholder="Enter Cuisines served" name="cuisine" required></textarea>

                        <div class="clearfix">
                            <button type="button" onclick="document.getElementById('register_form').style.display='none'" class="cancelbtn">Cancel</button>
                            <button type="submit" class="signupbtn">Sign Up</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<!-- Login Form -->
    <div id="login_form" class="modal" >
        <span onclick="document.getElementById('login_form').style.display='none'" class="close" title="Close Modal">&times;</span>
        <div class="modal-content">
            <div class="container">
                <div style="width: fit-content; margin: auto;" class="tab-switch">
                    <span onclick="switchTab(this, 'r_l_switch', 'c_content_l', 'r_content_l')" class="tab-item sel" id="c_l_switch">Customer</span>
                    <span onclick="switchTab(this, 'c_l_switch', 'r_content_l', 'c_content_l')" class="tab-item" id="r_l_switch">Restaurant</span>
                </div>
                <div id="c_content_l" style="display: block;" class="tab-content">
                    <h1>Customer Login Form</h1>
                    <p class='r-description'>Secret Note: Have a look at the console!</p>
                    <hr>
                    <form method="post" action="./login.php" class="form-input">
                        <input type="hidden" name="type" value="customer">

                        <label for="username"><b>Username</b></label>
                        <input type="text" placeholder="Enter Username" name="username" required>

                        <label for="psw"><b>Password</b></label>
                        <input type="password" placeholder="Enter Password" name="psw" required>

                        <div class="clearfix">
                            <button type="button" onclick="document.getElementById('login_form').style.display='none'" class="cancelbtn">Cancel</button>
                            <button type="submit" class="loginbtn">Login</button>
                        </div>
                    </form>
                </div>
                <div id="r_content_l" style="display: none;" class="tab-content">
                    <h1>Restaurant Login Form</h1>
                    <hr>
                    <form method="post" action="./login.php" class="form-input">
                        <input type="hidden" name="type" value="restaurant">
    
                        <label for="username"><b>Username</b></label>
                        <input type="text" placeholder="Enter Username" name="username" required>

                        <label for="psw"><b>Password</b></label>
                        <input type="password" placeholder="Enter Password" name="psw" required>

                        <div class="clearfix">
                            <button type="button" onclick="document.getElementById('login_form').style.display='none'" class="cancelbtn">Cancel</button>
                            <button type="submit" class="loginbtn">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
<script type="text/javascript">
    <?php
    // Adding Active to current Page
        switch ($page) {
            case 'home':
    ?>
    document.getElementById('home-tab').className += " active";
    <?php 
            break;
            case 'menu':
                if (isset($_SESSION['type']) && ($_SESSION['type'] == 'restaurant' || $page == 'menu')) {
    ?>
    document.getElementById('menu-tab').className += " active";
    <?php 
                }
            break;
            case 'order':
    ?>
    document.getElementById('order-tab').className += " active";
    <?php 
            break;
            case 'profile':
    ?>
    document.getElementById('profile-tab').className += " active";
    <?php 
            break;
            case 'cart':
    ?>
    document.getElementById('cart-tab').className += " active";
    <?php 
            break;
        }
    ?>
    var r_modal = document.getElementById('register_form');
    var l_modal = document.getElementById('login_form');
    // Form close
    window.onclick = function(event) {
        if (event.target == r_modal) {
            r_modal.style.display = "none";
        }
        if (event.target == l_modal) {
            l_modal.style.display = "none";
        }
    }
    // Convert tab for phone view
    function myFunction() {
        var x = document.getElementById("topNav");
        if (x.className === "navbar") {
            x.className += " responsive";
        } else {
            x.className = "navbar";
        }
    }
    // Swtiching b/w customer and restaurant
    function switchTab(f_btn, o_btn, first, other) {
        document.getElementById(first).style.display = "block";
        f_btn.className = "tab-item sel";
        document.getElementById(other).style.display = "none";
        document.getElementById(o_btn).className = "tab-item";
    }
</script>