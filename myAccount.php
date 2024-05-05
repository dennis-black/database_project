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
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f9ff; /* Light blue background */
            color: #333;
            margin: 0;
            /* padding: 20px; */
        }

        /* Styling for the container */
        .bg-light {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        /* Table styling */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #dee2e6;
            text-align: left;
        }

        /* Form control styling */
        .form-control {
            width: 100%;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #cdddee; /* Light blue border */
            box-sizing: border-box;
        }

        /* Button styling */
        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #0056b3;
        }

        /* Disabled button styling */
        button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }

        /* Select element styling */
        select.form-control {
            appearance: none;
            background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20width%3D%2212%22%20height%3D%227%22%20viewBox%3D%220%200%2012%207%22%20fill%3D%22none%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%3E%3Cpath%20d%3D%22M1%201L6%206L11%201%22%20stroke%3D%22black%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22/%3E%3C/svg%3E');
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-color: #fff;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .form-control, button {
                width: 100%;
            }
        }
    </style>
    <?php
        if (!isset($_SESSION['username'])) {
            echo "<script>alert('偵測到未登入'); window.location.href = 'login.php';</script>";
            exit(); 
        }

        include "database_connection.php";
        
        try {
            $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->bindParam(':username', $_SESSION['username']);
            $stmt->execute();
        
            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                $role = $user['role'];
                $userRealName = $user['userRealName'];
                $email = $user['email'];
                $phoneNumber = $user['phoneNumber'];
                $bloodType = $user['bloodType'];
                $birthday = $user['birthday'];
                $username = $user['username'];

                if($_SESSION['role'] != "user"){ //非一般權限使用者不處理生日格式字串
                    $formattedBirthday = $birthday;
                } else {
                    $birthdayParts = explode("/", $birthday);
                    $formattedBirthday = $birthdayParts[2] . "-" . $birthdayParts[0] . "-" . $birthdayParts[1];
                }

            } else {
                //echo "No user found with that username.";
            }
        } catch (PDOException $e) {
            die("Database error: " . $e->getMessage());
        }
    ?>
    <?php
        if (($_SERVER['REQUEST_METHOD'] === "POST")&&(isset($_POST['update']))){ //update stands for the field name
            include "database_connection.php";
            $fieldToUpdate = $_POST['update'];
            $updateValue = $_POST[$fieldToUpdate]?? '';
            // echo "<script>alert('".$fieldToUpdate.$updateValue."');</script>";

            if ($fieldToUpdate === 'password') { //處理更改密碼需要加密的部分
                if (($_POST['password'] === $_POST['confirmPassword']) && (strlen($_POST['password']) >= 4 && strlen($_POST['password']) <= 50)) {
                    $updateValue = password_hash($_POST['password'], PASSWORD_DEFAULT);
                } else {
                    echo "<script>alert('密碼與確認密碼不相同或是密碼長度低於4個字元或高於50個字元'); window.history.back();</script>";
                    exit();
                }
            }

            try { //更新資料庫
                $stmt = $db->prepare("UPDATE users SET `$fieldToUpdate` = :updateValue WHERE id = :id");
                $stmt->bindParam(':updateValue', $updateValue);
                $stmt->bindParam(':id', $_SESSION['user_id']);
                $stmt->execute();
        
                if ($stmt->rowCount() > 0) {
                    echo "<script>alert('更新成功'); window.location.href = 'myAccount.php';</script>";
                    if($fieldToUpdate == "userRealName") $_SESSION['userRealName'] = $updateValue; 
                } else {
                    echo "<script>alert('無變更導致的未更新'); window.history.back();</script>";
                }
            } catch (PDOException $e) {
                die("Database error during update: " . $e->getMessage());
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
        <a href="organs.php" class="navbar-brand d-flex align-items-center px-4 px-lg-5">
            <h1 class="m-0 text-primary"><i class="far fa-hospital me-3"></i>丹尼斯的保鮮盒</h1>
        </a>
        <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto p-4 p-lg-0">
                <?php
                    if($_SESSION['role'] == "admin"){
                        echo '<a href="manageAccounts.php" class="nav-item nav-link active">管理使用者</a>
                        <a href="manageShelf.php" class="nav-item nav-link active">管理貨架</a>
                        <a href="manageOrders.php" class="nav-item nav-link active">管理訂單</a>
                        <a href="myAccount.php" class="nav-item nav-link active">';
                         echo "歡迎，". $_SESSION['userRealName'] ."</a>";
                    }
                ?>
                <?php
                    if($_SESSION['role'] == "user"){
                        echo '<a href="organs.php" class="nav-item nav-link active">前往保鮮盒</a>
                        <a href="listOrgans.php" class="nav-item nav-link active">我的上架列表</a>
                        <a href="cart.php" class="nav-item nav-link active">我的購物車</a>
                        <a href="myAccount.php" class="nav-item nav-link active">';
                        echo "歡迎，". $_SESSION['userRealName'] ."</a>";
                    }
                
                ?>
            </div>
            <a href="logout.php" class="btn btn-primary rounded-0 py-4 px-lg-5 d-none d-lg-block">登出<i class="fa fa-arrow-right ms-3"></i></a>
        </div>
    </nav>
    <!-- Navbar End -->


    <!-- Header Start -->
    <div class="container-fluid header bg-primary p-0 mb-5">
        
    </div>
    <!-- Header End -->

    <div class="bg-light rounded h-100 d-flex align-items-center p-5">
        <table>
            <tr>
                <th>身分組</th>
                <td><input type="text" class="form-control border-0" value="<?php echo $role;?>" readonly></td>
                <td></td>
            </tr>
            <tr>
                <form action="myAccount.php" method="post" autocomplete="off">
                    <th>使用者姓名</th>
                    <td><input type="text" name="userRealName" class="form-control border-0" value="<?php echo $userRealName;?>" ></td>
                    <td><button type="submit" name="update" value="userRealName">更改姓名</button></td>
                </form>
            </tr>
            <tr>
                <form action="myAccount.php" method="post" autocomplete="off">
                    <th>Email</th>
                    <td><input type="email" name="email"  class="form-control border-0" value="<?php echo $email;?>" ></td>
                    <td><button type="submit" name="update" value="email">更改Email</button></td>
                </form>
            </tr>
            <tr>
                <form action="myAccount.php" method="post" autocomplete="off">
                    <th>手機號碼</th>
                    <td><input type="tel" name="phoneNumber" class="form-control border-0" value="<?php echo $phoneNumber;?>" ></td>
                    <td><button type="submit" name="update" value="phoneNumber">更改手機號碼</button></td>
                </form>
            </tr>
            <tr>
                <form action="myAccount.php" method="post" autocomplete="off">
                    <th>血型</th>
                    <td> 
                        <select name="bloodType" class="form-control border-0" >
                            <option value="A" <?php if($bloodType=="A") echo "selected"?>>A型</option>
                            <option value="B" <?php if($bloodType=="B") echo "selected"?>>B型</option>
                            <option value="AB" <?php if($bloodType=="AB") echo "selected"?>>AB型</option>
                            <option value="O" <?php if($bloodType=="O") echo "selected"?>>O型</option>
                            <option value="Rh+" <?php if($bloodType=="Rh+") echo "selected"?>>Rh陽性</option>
                            <option value="Rh-" <?php if($bloodType=="Rh-") echo "selected"?>>Rh陰性</option>
                            <option value="other" <?php if($bloodType=="other") echo "selected"?>>其他特殊血型系統(你的血型無法被常用血型系統描述)</option>
                        </select>
                    </td>
                    <td><button type="submit" name="update" value="bloodType">更改血型</button></td>
                </form>
            </tr>
            <tr>
                <th>生日</th>
                <td><input type="date" name="birthday" class="form-control border-0" value="<?php echo $formattedBirthday;?>" readonly></td>
                <td><button disabled>更改生日</button></td>
            </tr>
            <tr>
                    <th>使用者名稱</th>
                    <td><input type="text" name="username" class="form-control border-0" value="<?php echo $username;?>" readonly></td>
                    <td><button name="update" value="username" disabled>更改</button></td>
            </tr>
            <form action="myAccount.php" method="post" autocomplete="off">
            <tr>
                <th>密碼</th>
                <td><input type="password" name="password" class="form-control border-0"></td>
                <td rowspan="2" ><button type="submit" name="update" value="password">更改</button></td>
            </tr>
            <tr>
                <th>再次確認密碼</th>
                <td><input type="password" name="confirmPassword" class="form-control border-0"></td>
            </tr>
            </form>
        </table>
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