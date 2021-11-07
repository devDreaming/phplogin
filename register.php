<?php 
require_once 'connection.php'; 

session_start(); // creates a global $_SESSION

if(isset($_SESSION['user'])) {
    header("location: welcome.php");
}


if(isset($_REQUEST['register_btn'])) {
    $select_stmt = $db->prepare("SELECT email FROM users WHERE email = 'bla@bla.com");
    $select_stmt->execute();
    $row = $select_stmt->fetch(PDO::FETCH_ASSOC);
    echo "<pre>";
        print_r($row);
    echo "</pre>";

    echo "<pre>";
        print_r($_REQUEST);
    echo "</pre>";

    // Get data from form and sanitize
    $name = filter_var($_REQUEST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_REQUEST['email'], FILTER_SANITIZE_EMAIL);
    $password = strip_tags($_REQUEST['password']);

    // Check form data for proper values
    if(empty($name)) {
        $errorMsg[0][] = 'Name required';
    }
    if(empty($email)) {
        $errorMsg[1][] = 'Email required';
    }
    if(empty($password)) {
        $errorMsg[2][] = 'Password required';
    }
    if(strlen($password) < 6) {
        $errorMsg[2][] = 'Password must be at least 6 characters';
    }

    if(empty($errorMsg)) {

        try{
            $select_stmt = $db->prepare("SELECT name,email FROM users WHERE email = :email");
            $select_stmt->execute([':email' => $email]);
            $row = $select_stmt->fetch(PDO::FETCH_ASSOC);

            if(isset($row['email']) == $email) {
                $errorMsg[1][] = "Email address already in use. Please log in or use a different email";
            }
        }
        catch(PDOEXCEPTION $e) {
            $pdoError = $e->getMessage();
        }

    }
    
}

?>


<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
	<title>Register</title>
</head>
<body>
	<div class="container">
		
		<form action="register.php" method="post">
			<div class="mb-3">

                <?php
                    if(isset($errorMsg[0])) {
                        foreach($errorMsg[0] as $nameError) {
                            echo "<p class='small text-danger'>".$nameError."</p>";
                        }
                    }
                ?>

				<label for="name" class="form-label">Name</label>
				<input type="text" name="name" class="form-control" placeholder="Jane Doe">
			</div>
			<div class="mb-3">

                <?php
                    if(isset($errorMsg[1])) {
                        foreach($errorMsg[1] as $emailError) {
                            echo "<p class='small text-danger'>".$emailError."</p>";
                        }
                    }
                ?>

				<label for="email" class="form-label">Email address</label>
				<input type="email" name="email" class="form-control" placeholder="jane@doe.com">
			</div>
			<div class="mb-3">

                <?php
                    if(isset($errorMsg[2])) {
                        foreach($errorMsg[2] as $passwordError) {
                            echo "<p class='small text-danger'>".$passwordError."</p>";
                        }
                    }
                ?>

				<label for="password" class="form-label">Password</label>
				<input type="password" name="password" class="form-control" placeholder="">
				
			</div>
			<button type="submit" name="register_btn" class="btn btn-primary">Register Account</button>
		</form>
		Already Have an Account? <a class="register" href="index.php">Login Instead</a>
	</div>
</body>
</html>