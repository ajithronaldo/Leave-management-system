<!DOCTYPE html>
<html>

<head>
    <title>Leave Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <meta name="keywords" content="Kgkite Leave" />
    <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css" media="all">
    <link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
    <link href="//fonts.googleapis.com/css?family=Raleway:400,500,600,700,800,900" rel="stylesheet">
</head>

<body>
    <div class="signupform">
        <h1>Leave Management System - KiTE</h1>
        <div class="container">
            <div class="agile_info">
                <div class="w3l_form">
                    <img src="images/login-banner.png">
                </div>
                <div class="w3_info">
                    <h2>Log in with your credentials !!</h2>
                    <p>
                    	<?php 
	                        $errors = array(
	                            1=>"Invalid user name or password, Try again",
	                            2=>"Please login to access this area",
                                3=>"You Login access has been Removed"
	                        );

	                        $error_id = isset($_GET['err']) ? (int)$_GET['err'] : 0;

	                        if ($error_id == 1) {
                                echo '<p class="text_danger">'.$errors[$error_id].'</p>';
                            }elseif ($error_id == 2) {
                                echo '<p class="text_danger">'.$errors[$error_id].'</p>';
                            }elseif ($error_id == 3) {
                                echo '<p class="text_danger">'.$errors[$error_id].'</p>';
                            }
	                    ?>  
                    </p>
                    <form action="Controllers/authenticate.php" method="post">
                        <div class="input-group">
                            <span><i class="fa fa-user" aria-hidden="true"></i></span>
                            <input type="text" name="username" placeholder="Username" required autofocus>
                        </div>
                        <div class="input-group">
                            <span><i class="fa fa-unlock" aria-hidden="true"><span>
							</i><input type="Password" name="password" placeholder="Password" required>
						</div>
							<input type="checkbox" value="remember-me" /> <h4>Keep me signed in  </h4>        
							<button class="btn btn-danger btn-block" type="submit">Sign In</button >                     
					</form>
			    </div>
			<div class="clear"></div>
			</div>
			
		</div>
		<div class="footer">

 <p>&copy; Project 2K18. All Rights Reserved | Design by Batch 10</p>
 </div>
	</div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
	</body>
</html>