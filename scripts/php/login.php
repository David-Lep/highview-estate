<?php
	ob_start();
	class login {
		function __construct(){
			if(!empty($_POST['login'])){
				$this->checkForm();
			} else {
				$this->form();
			}
		}

		private function testHash(){
			$options = ['cost' => 11];
			return password_hash($_POST['logpass'], PASSWORD_BCRYPT, $options);
		}

		private function checkForm(){
			if(isset($_POST['logname'], $_POST['logpass']) && !empty($_POST['logname']) && !empty($_POST['logpass'])){ 
				$dbc = new dbc();
				$conn = $dbc->connect();

				$sth = $conn->prepare('SELECT * FROM users WHERE uname = :name');
				$sth->bindParam(':name', $_POST['logname']);
				$sth->execute();
				$row = $sth->fetch();

				if(password_verify($_POST['logpass'], $row['upass'])){ //Password is correct
					$_SESSION['uid'] = $row['id'];
					$_SESSION['user'] = $row['uname'];
					$_SESSION['uac'] = $row['uac'];
					$_SESSION['loggedin'] = true; //User is logged in
					header('location: admin.php'); //I need this for...reasons...
				} else {
					echo "Please enter a valid username and password";
					$this->form();
				}
				$conn = null;
			} else {
				echo "Please enter a valid username and password";
				$this->form();
			}
		}

		private function hash(){
			$options = ['cost' => 11];
			return password_hash($_POST['logpass'], PASSWORD_BCRYPT, $options);
		}

		private function form(){
			?>
			<form id="login" name="login" method="post">
				<label>Name:</label><input type="text" name="logname" autofocus>
				<label>Password:</label><input type="password" name="logpass">
				<input type="submit" name="login" value="Log in">
			</form>
			<?php
		}
	}


?>
