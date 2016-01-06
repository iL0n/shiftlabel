<?php require('includes/config.php');
if( $user->is_logged_in() ){ header('Location: memberpage.php'); }
if(isset($_POST['submit'])){
	if(strlen($_POST['username']) < 3){
		$error[] = 'Username is too short.';
	} else {
		$stmt = $db->prepare('SELECT username FROM members WHERE username = :username');
		$stmt->execute(array(':username' => $_POST['username']));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		if(!empty($row['username'])){
			$error[] = 'Username provided is already in use.';
		}
	}
	if(strlen($_POST['password']) < 3){
		$error[] = 'Password is too short.';
	}
	if(strlen($_POST['passwordConfirm']) < 3){
		$error[] = 'Confirm password is too short.';
	}
	if($_POST['password'] != $_POST['passwordConfirm']){
		$error[] = 'Passwords do not match.';
	}
	if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
	    $error[] = 'Please enter a valid email address';
	} else {
		$stmt = $db->prepare('SELECT email FROM members WHERE email = :email');
		$stmt->execute(array(':email' => $_POST['email']));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if(!empty($row['email'])){
			$error[] = 'Email provided is already in use.';
		}
	}
	if(!isset($error)){
		$hashedpassword = $user->password_hash($_POST['password'], PASSWORD_BCRYPT);
		$activasion = md5(uniqid(rand(),true));
		try {
			$stmt = $db->prepare('INSERT INTO members (username,password,email,active) VALUES (:username, :password, :email, :active)');
			$stmt->execute(array(
				':username' => $_POST['username'],
				':password' => $hashedpassword,
				':email' => $_POST['email'],
				':active' => $activasion
			));
			$id = $db->lastInsertId('memberID');
			$to = $_POST['email'];
			$subject = "Registration Confirmation";
			$body = "<p>Thank you for registering at Shiftlabel.</p>
			<p>To activate your account, please click on this link: <a href='".DIR."activate.php?x=$id&y=$activasion'>".DIR."activate.php?x=$id&y=$activasion</a></p>
			<p>Regards Site Admin</p>";
			$mail = new Mail();
			$mail->setFrom(SITEEMAIL);
			$mail->addAddress($to);
			$mail->subject($subject);
			$mail->body($body);
			$mail->send();
			header('Location: index.php?action=joined');
			exit;
		} catch(PDOException $e) {
		    $error[] = $e->getMessage();
		}
	}
}
$title = 'Shiftlabel';
?>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="css/style.css">

    <body class="align">
  <div class="site__container">
    <div class="grid__container">
			<?php
				if(isset($error)){
					foreach($error as $error){
						echo '<p class="bg-danger">'.$error.'</p>';
					}
				}
				if(isset($_GET['action']) && $_GET['action'] == 'joined'){
					echo "<h2 class='bg-success'>Registration successful, please check your email to activate your account.</h2>";
				}
				?>
      <form action="" method="post" class="form form--login">
        <div class="form__field">
          <label class="fontawesome-user" for="login__username"><span class="hidden">User name</span></label>
          <input name="username" id="username" type="text" class="form__input" placeholder="User name" required value="<?php if(isset($error)){ echo $_POST['username']; } ?>" tabindex="1">
        </div>
        <div class="form__field">
          <label class="fa fa-envelope"</i><span class="hidden">Email</span></label>
          <input name="email" id="email" type="text" class="form__input" placeholder="email" required value="<?php if(isset($error)){ echo $_POST['email']; } ?>" tabindex="2">
        </div>
        <div class="form__field">
          <label class="fontawesome-lock" for="login__password"><span class="hidden">Password</span></label>
          <input name="password" id="password" type="password" class="form__input" placeholder="Password" required tabindex="3">
        </div>
        <div class="form__field">
          <label class="fontawesome-lock" for="login__password"><span class="hidden">Confirm Password</span></label>
          <input type="password" name="passwordConfirm" id="passwordConfirm" class="form__input" placeholder="Confirm Password" required tabindex="4">
        </div>
        <div class="form__field">
          <input type="submit" name="submit" value="Sign up">
        </div>
      </form>
      <p class="text--center">Already a member? <a href="login.php">Sign in now</a> <span class="fontawesome-arrow-right"></span></p>
    </div>
 </div>
