<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="styles.css">
	<title>黑貓工作室-註冊</title>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<style>
		body {
			font-family: Arial, sans-serif;
			margin: 0;
			padding: 0;
		}

		.logo {
			height: 40px;
			margin: 7px;
		}

		header {
			background-color: #333;
			color: #fff;
			padding: 1rem;
		}

		nav ul {
			float: right;
			list-style: none;
			display: flex;
		}

		nav li {
			margin-right: 1rem;
		}

		/* hover 導航欄效果 */
		li a:hover{ 
			background-color: #fff;
			border-radius: 4px;
			color: #736f6f;
		}

		nav a {
			text-decoration: none;
			color: #fff;
			font-size: 16px;
		}

		section {
			padding: 2rem;
		}

		#home {
			background-color: #f0f0f0;
		}

		#portfolio .portfolio-item {
			margin-bottom: 2rem;
		}

		footer {
			/* position: ;
			bottom: 0;
			width: 100%; */
			/* left: 0; */
			padding: 1rem;
			text-align: center;
			background-color: #333;
			color: #fff;
		}

		@media screen and (max-width: 600px) {
			header {
				background-color: #333;
				color: #fff;
				padding-bottom: 60px;
				/* height: 50px; */
			}
			nav a{
				text-decoration: none;
				color: #fff;
				font-size: 14px;
			}
		}

	</style>
	<?php
		session_start();

		if (!isset($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

		include "database_connection.php";

		if ($_SERVER['REQUEST_METHOD'] === "POST"){

			if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) { //檢查cfrs令牌
				die("CSRF token validation failed");
			}

			$username = $_POST['username']?? '';
			$email = $_POST['email'] ?? '';
			$password = $_POST['password'] ?? '';
			$confirmPassword = $_POST['confirmPassword'] ?? '';
			
			$errors = '';
			if (empty($username)) {
				$errors .= "使用者ID不得為空\\n";
			} else if (strlen($username) < 4 || strlen($username) > 50) {
				$errors .= "使用者ID的長度必須至少4個字元且少於50個字元\\n";
			}
			
			if (empty($email)) {
				$errors .= "電子郵箱不得為空\\n";
			} else if (strlen($email) < 4 || strlen($email) > 50) {
				$errors .= "電子郵箱的長度必須至少4個字元且少於50個字元\\n";
			}
			
			if (empty($password)) {
				$errors .= "你的密碼不得為空\\n";
			} else if (strlen($password) < 4 || strlen($password) > 50) {
				$errors .= "密碼的長度必須至少4個字元且少於50個字元\\n";
			}
			
			if (empty($confirmPassword)) {
				$errors .= "再次輸入密碼不得為空\\n";
			} else if ($password != $confirmPassword) {
				$errors .= "你的密碼與再次確認密碼不同，請確保他們是相同的\\n";
			} else if (strlen($confirmPassword) < 4 || strlen($confirmPassword) > 50) {
				$errors .= "確認密碼的長度必須至少4個字元且少於50個字元\\n";
			}

			if(empty($errors)){
				$checkUser = $db->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
				$checkUser -> bindParam(':username', $username);
				$checkUser -> execute();

				if($checkUser->fetchColumn() > 0) $errors.= "使用者名稱已經被註冊\\n";

				$checkEmail = $db->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
				$checkEmail -> bindParam(':email', $email);
				$checkEmail -> execute();

				if($checkEmail->fetchColumn() > 0) $errors.= "電子郵箱已經被註冊\\n";
			}
			
			if (!empty($errors)) echo "<script>alert('$errors');</script>";
			else {
				if (!empty($password)&&(strlen($password)>=4)&&(strlen($password)<=50)) {
					$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
				}

				try {
					$stmt = $db->prepare("INSERT INTO users (role, username, email, password) VALUES (:role, :username, :email, :password)");
					$role = 'user';
					$stmt->bindParam(':role', $role);
					$stmt->bindParam(':username', $username);
					$stmt->bindParam(':email', $email);
					$stmt->bindParam(':password', $hashedPassword);
					$stmt->execute();
				
					echo "<script>
							alert('使用者註冊成功');
							setTimeout(function() {
								window.location.href = 'login.php';
							}, 3000);
						</script>";
				} catch (PDOException $e) {
					echo "数据库错误: " . $e->getMessage();
				}
			}
	
		}
	?>
</head>
<body>
	<header>
	<nav>
		<a href=""><img class="logo" src="./assets/blackcatstudio_banner.png"></a>
		<ul>
			<li><a href="index.html">回首頁</a></li>
			<li><a href="register.php" style="color: #99c2ff; font-weight: bolder;">註冊</a></li>
		</ul>
	</nav>
	</header>
	<section id="home">
		<h1>黑貓工作室 </h1>
		<h2>Black Cat Studio</h2>
	</section>

	<section id="intro">
		<h2>使用者註冊</h2>
		<form action="register.php" method="post" autocomplete="off">
			<table>
				<tr>
					<td><p>使用者ID</p></td>
					<td><input type="text" name="username" placeholder="輸入你的ID" maxlength="50"></td>
				</tr>
				<tr>
					<td><p>電子郵箱</p></td>
					<td><input type="email" name="email" placeholder="...@..." maxlength="50"></td>
				</tr>
				<tr>
					<td><p>你的密碼</p></td>
					<td><input type="password" name="password" placeholder="輸入你的密碼" maxlength="50"></td>
				</tr>
					<td><p>再次輸入密碼</p></td>
					<td><input type="password" name="confirmPassword" placeholder="再次確認密碼" maxlength="50"></td>
				</tr>
				<tr>
					<td colspan="2"><p><b>上述任一欄位字元數至少要4個字元且不得超過50個字元</b></p></td>
				</tr>
				<tr>
					<td><input type="submit" name="registerBtn" value="註冊帳號"></td>
					<td><input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>"></td>
				</tr>
			</table>
		</form>
	</section>
	<footer>
	<p>&copy; 2023 黑貓工作室</p>
	</footer>
</body>
</html>
