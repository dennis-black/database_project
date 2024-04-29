<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>丹尼斯的保鮮盒</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500&family=Roboto:wght@500;700;900&display=swap" rel="stylesheet"> 

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    <?php
		if (!isset($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

		include "database_connection.php";

		if ($_SERVER['REQUEST_METHOD'] === "POST"){

			if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) { //檢查cfrs令牌
				die("CSRF token validation failed");
			}

			$userRealName = $_POST['userRealName']?? '';
            $email = $_POST['email'] ?? '';
            $phoneNumber = $_POST['phoneNumber']?? '';
            $bloodType = $_POST['bloodType']?? '';
            $birthday = $_POST['birthday']?? '';
            $username = $_POST['username']?? '';
			$password = $_POST['password'] ?? '';
			$confirmPassword = $_POST['confirmPassword'] ?? '';
			
			$errors = '';
            if (empty($userRealName)) {
				$errors .= "使用者姓名不得為空\\n";
			} else if (strlen($userRealName) < 2 || strlen($userRealName) > 20) {
				$errors .= "使用者姓名的長度必須至少2個字元且少於20個字元\\n";
			}
			
			if (empty($email)) {
				$errors .= "電子郵箱不得為空\\n";
			} else if (strlen($email) < 4 || strlen($email) > 50) {
				$errors .= "電子郵箱的長度必須至少4個字元且少於50個字元\\n";
			}

            if (empty($phoneNumber)) {
				$errors .= "手機號碼不得為空\\n";
			} else if (strlen($phoneNumber) != 10) {
				$errors .= "手機號碼的長度必須等於10個字元\\n";
			}

            if ($bloodType == "你的血型")
				$errors .= "未選擇血型，請選擇血型\\n";

            if (empty($birthday)) { //證實生日的格式
                $errors .= "生日錯誤，你不可能在今天或或是未來出生\\n";
            } 

            if (empty($username)) {
				$errors .= "使用者名稱不得為空\\n";
			} else if (strlen($username) < 4 || strlen($username) > 20) {
				$errors .= "使用者ID的長度必須至少4個字元且少於20個字元\\n";
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

            //echo "<script>alert('+$role+'\n'+$userRealName+'\n'+$email+'\n'+$phoneNumber+'\n'+$bloodType+'\n'+$birthday+'\n'+$username +'\n'+ $password+');</script>";
			
			if (!empty($errors)) echo "<script>alert('$errors');</script>";
			else {
				if (!empty($password)&&(strlen($password)>=4)&&(strlen($password)<=50)) {
					$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    // echo "<script>
					// 		alert('密碼加密');
					// 	</script>";
				}

				try {
					$stmt = $db->prepare("INSERT INTO users (role, userRealName, email, phoneNumber, bloodType, birthday, username, password) VALUES (:role, :userRealName, :email, :phoneNumber, :bloodType, :birthday, :username, :password)");
					$role = 'user';
					$stmt->bindParam(':role', $role);
                    $stmt->bindParam(':userRealName', $userRealName);
                    $stmt->bindParam(':email', $email);
					$stmt->bindParam(':phoneNumber', $phoneNumber);
					$stmt->bindParam(':bloodType', $bloodType);
                    $stmt->bindParam(':birthday', $birthday);
                    $stmt->bindParam(':username', $username);
					$stmt->bindParam(':password', $hashedPassword);
					$stmt->execute();
				
					echo "<script>
							alert('使用者註冊成功');
							setTimeout(function() {
								window.location.href = 'login.php';
							}, 0);
						</script>";

				} catch (PDOException $e) {
					echo "資料庫錯誤: " . $e->getMessage();
				}
			}
		}
	?>
</head>

<body>
    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-grow text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->


    <!-- Topbar Start -->
    <div class="container-fluid bg-light p-0 wow fadeIn" data-wow-delay="0.1s">
        <div class="row gx-0 d-none d-lg-flex">
            <div class="col-lg-7 px-5 text-start">
                <div class="h-100 d-inline-flex align-items-center py-3 me-4">
                    <small class="fa fa-map-marker-alt text-primary me-2"></small>
                    <small>阿拉伯聯合大公國阿布達比國際金融中心-科里弗蘭診所</small>
                </div>
                <div class="h-100 d-inline-flex align-items-center py-3">
                    <small class="far fa-clock text-primary me-2"></small>
                    <small>Mon - Fri : 09.00 AM - 09.00 PM</small>
                </div>
            </div>
            <div class="col-lg-5 px-5 text-end">
                <div class="h-100 d-inline-flex align-items-center py-3 me-4">
                    <small class="fa fa-phone-alt text-primary me-2"></small>
                    <small>+012 345 6789</small>
                </div>
            </div>
        </div>
    </div>
    <!-- Topbar End -->


    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg bg-white navbar-light sticky-top p-0 wow fadeIn" data-wow-delay="0.1s">
        <a href="index.php" class="navbar-brand d-flex align-items-center px-4 px-lg-5">
            <h1 class="m-0 text-primary"><i class="far fa-hospital me-3"></i>丹尼斯的保鮮盒</h1>
        </a>
        <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto p-4 p-lg-0">
                <a href="index.php" class="nav-item nav-link active">首頁/關於</a>
                <!-- <a href="aboutUs" class="nav-item nav-link">關於我們</a> -->
            </div>
            <a href="login.php" class="btn btn-primary rounded-0 py-4 px-lg-5 d-none d-lg-block">登入/註冊<i class="fa fa-arrow-right ms-3"></i></a>
        </div>
    </nav>
    <!-- Navbar End -->


    <!-- Header Start -->
    <div class="container-fluid header bg-primary p-0 mb-5">
        
    </div>
    <!-- Header End -->

    <div>
    <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.5s">
    <!-- <h1 class="mb-4">註冊新帳號</h1> -->
    <div class="bg-light rounded h-100 d-flex align-items-center p-5">
        <form action="register.php" method="post" autocomplete="off">
            <div class="row g-3">
                <h1 class="mb-4">註冊新帳號</h1>
                <div class="col-12 col-sm-6">
                    <input type="text" name="userRealName" class="form-control border-0" placeholder="你的姓名" style="height: 55px;">
                </div>
                <div class="col-12 col-sm-6">
                    <input type="email" name="email" class="form-control border-0" placeholder="你的 Email" style="height: 55px;">
                </div>
                <div class="col-12 col-sm-6">
                    <input type="text" name="phoneNumber" class="form-control border-0" placeholder="你的手機" style="height: 55px;">
                </div>
                <div class="col-12 col-sm-6">
                    <select name="bloodType" class="form-select border-0" style="height: 55px;">
                        <option selected>你的血型</option>
                        <option value="A">A型</option>
                        <option value="B">B型</option>
                        <option value="AB">AB型</option>
                        <option value="O">O型</option>
                        <option value="Rh+">Rh陽性</option>
                        <option value="Rh-">Rh陰性</option>
                        <option value="other">其他特殊血型系統(你的血型無法被常用血型系統描述)</option>
                    </select>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="date" id="date" data-target-input="nearest">
                        <input type="text" class="form-control border-0 datetimepicker-input" name="birthday"
                            placeholder="你的生日" data-target="#date" data-toggle="datetimepicker" style="height: 55px;">
                    </div>
                </div>
                <div class="col-12 col-sm-6">
                    <input type="text" name="username" class="form-control border-0" placeholder="使用者名稱" style="height: 55px;">
                </div>
                <div class="col-12 col-sm-6">
                    <input type="password" name="password" class="form-control border-0" placeholder="請輸入密碼" style="height: 55px;">
                </div>
                <div class="col-12 col-sm-6">
                    <input type="password" name="confirmPassword" class="form-control border-0" placeholder="再次確認密碼" style="height: 55px;">
                </div>
                <div class="col-12">
                    <button class="btn btn-primary w-100 py-3" type="submit">註冊帳號</button>
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                </div>
            </div>
        </form>
        </div>
        </div>
    </div>




    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-light footer mt-5 pt-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-3 col-md-6">
                    <h5 class="text-light mb-4">Address</h5>
                    <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>阿拉伯聯合大公國阿布達比國際金融中心-科里弗蘭診所</p>
                    <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>+012 345 67890</p>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="copyright">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        &copy; <a class="border-bottom" href="#">丹尼斯的保鮮盒</a>, All Right Reserved.
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <!--/*** This template is free as long as you keep the footer author’s credit link/attribution link/backlink. If you'd like to use the template without the footer author’s credit link/attribution link/backlink, you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". Thank you for your support. ***/-->
                        Designed By <a class="border-bottom" href="https://htmlcodex.com">HTML Codex</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square rounded-circle back-to-top"><i class="bi bi-arrow-up"></i></a>


    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>