<?php
require_once('includes/config.php');
if( $user->is_logged_in() ){ header('Location: index.php'); }
if(isset($_POST['submit'])){
	$username = $_POST['username'];
	$password = $_POST['password'];
	if($user->login($username,$password)){
		$_SESSION['username'] = $username;
		header('Location: memberpage.php');
		exit;
	} else {
		$error[] = 'Wrong username or password or your account has not been activated.';
	}
}
$title = 'Login to Shiftlabel';
?>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="css/style.css">
        <?php
				if(isset($error)){
					foreach($error as $error){
						echo '<p class="bg-danger">'.$error.'</p>';
					}
				}
				if(isset($_GET['action'])){
					switch ($_GET['action']) {
						case 'active':
							echo "<h2 class='bg-success'>Your account is now active you may now log in.</h2>";
							break;
						case 'reset':
							echo "<h2 class='bg-success'>Please check your inbox for a reset link.</h2>";
							break;
						case 'resetAccount':
							echo "<h2 class='bg-success'>Password changed, you may now login.</h2>";
							break;
					}
				}
				?>
    <body class="align">
  <div class="site__container">
    <div class="grid__container">
      <form action="" method="post" class="form form--login">
        <div class="form__field">
          <label class="fontawesome-user" for="login__username"><span class="hidden">Username</span></label>
          <input type="text" name="username" id="username" class="form__input" placeholder="Username" required value="<?php if(isset($error)){ echo $_POST['username']; } ?>" tabindex="1">
        </div>
        <div class="form__field">
          <label class="fontawesome-lock" for="login__password"><span class="hidden">Password</span></label>
          <input type="password" name="password" id="password" class="form__input" placeholder="Password" required tabindex="3">
        </div>
        <div class="form__field">
          <input type="submit" name="submit" value="Sing in">
        </div>
      </form>
      <p class="text--center">Not a member ? <a href="index.php">         Sign up now</a> <span class="fontawesome-arrow-right"></span></p>
      <p class="text--center">Forgot your password ? <a href="reset.php">Cick here</a> <span class="fontawesome-arrow-right"></span></p>
    </div>
 </div>
