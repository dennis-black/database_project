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
        h1, h2, h3, p {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        .container_list {
            width: 90%;
            margin: 20px auto;
            background-color: #fff;
            padding: 15px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .sort-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .sort-bar select, .sort-bar input[type="text"], .sort-bar input[type="submit"] {
            padding: 8px 10px;
            margin-right: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .sort-bar input[type="submit"] {
            cursor: pointer;
            background-color: #007BFF;
            color: white;
            border: none;
        }

        .product-list {
            list-style: none;
        }

        .product-list .product {
            display: flex;
            align-items: center;
            border-bottom: 1px solid #eee;
            padding: 10px 0;
        }

        .product-list .product:last-child {
            border-bottom: none;
        }

        .product-list .product img {
            width: 100px; /* 固定图片大小 */
            height: 100px; /* 固定图片大小 */
            object-fit: cover; /* 保证图片比例正确 */
            margin-right: 20px;
            border-radius: 5px;
        }

        .product-list .product-info {
            flex-grow: 1;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .product-list .product-info .name,
        .product-list .product-info .price,
        .product-list .product-info .upload-date {
            margin: 0 10px;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #eef2f5;
            margin: 0;
            /* padding: 20px; */
            color: #333;
        }

        .product-container {
            background-color: #ffffff;
            border: 1px solid #cdddee;
            border-radius: 12px;
            padding: 20px;
            margin-top: 20px;
            display: flex;
            align-items: flex-start;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            max-width: 1000px;
            margin-left: auto;
            margin-right: auto;
        }

        .product-container img {
            width: 50%;
            height: auto;
            border-radius: 8px;
            margin-right: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .product-details {
            display: flex;
            flex-direction: column;
            width: 50%;
        }

        .product-container h1 {
            color: #007BFF;
            font-size: 28px;
            margin-bottom: 0.5em;
        }

        .price {
            font-size: 22px;
            color: #008800;
            font-weight: bold;
        }

        .description {
            font-size: 16px;
            color: #555;
            margin-top: 10px;
            line-height: 1.6;
        }

        .date-added {
            font-size: 14px;
            color: #666;
            margin-top: 20px;
            margin-bottom: 20px;
            font-style: italic;
        }

        input[type="submit"] {
            background-color: #0056b3;
            color: white;
            border: none;
            padding: 12px 24px;
            font-size: 18px;
            cursor: pointer;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #003d80;
        }


    </style>
    <?php
        if (!isset($_SESSION['username'])) {
            echo "<script>alert('偵測到未登入'); window.location.href = 'login.php';</script>";
            exit(); 
        } else if ($_SESSION['role'] != "user") {
            echo "<script>alert('管理員無權訪問'); window.history.back();</script>";
            exit();
        }

        include "database_connection.php";

        $stmt = $db -> prepare("SELECT * FROM `product` WHERE `PID` = :PID");
        $stmt -> bindParam(':PID', $_GET['pid']);
        $stmt -> execute();
        $info = $stmt -> fetch(PDO::FETCH_ASSOC);

        $personWhoOwn = $db -> prepare("SELECT * FROM `users` WHERE `ID` = :ID");
        $personWhoOwn -> bindParam(':ID', $info['ownerID']);
        $personWhoOwn -> execute();
        $personInfo = $personWhoOwn -> fetch(PDO::FETCH_ASSOC);
    ?>
    <?php
            if ($_SERVER['REQUEST_METHOD'] === "POST"){
                $checkCartExists = $db->prepare("SELECT COUNT(*) FROM cart WHERE PID = :PID AND ID = :ID");
                $checkCartExists -> bindParam(':PID', $_POST['addToCartPID']);
                $checkCartExists -> bindParam(':ID', $_SESSION['user_id']);
                $checkCartExists -> execute();
                if($checkCartExists->fetchColumn() > 0) {
                    ob_end_flush();
                    echo "<script>alert('該物品先前已加入我的購物車');</script>";
                    echo '<script>window.location.href="organs.php";</script>';
                } else {
                    $stmt = $db->prepare("INSERT INTO `cart`(`ID`, `PID`) VALUES (:userID, :PID)");
                    $stmt -> bindParam(':userID', $_SESSION['user_id']);
                    $stmt -> bindParam(':PID', $_POST['addToCartPID']);
                    $stmt->execute();
                    ob_end_flush();
                    echo "<script>alert('已加入我的購物車');</script>";
                    echo '<script>window.location.href="organs.php";</script>';
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
                <a href="organs.php" class="nav-item nav-link active">前往保鮮盒</a>
                <a href="listOrgans.php" class="nav-item nav-link active">我的上架列表</a>
                <a href="cart.php" class="nav-item nav-link active">我的購物車</a>
                <a href="myAccount.php" class="nav-item nav-link active"><?php echo "歡迎，". $_SESSION['userRealName'];?></a>
                <!-- <a href="aboutUs" class="nav-item nav-link">關於我們</a> -->
            </div>
            <a href="logout.php" class="btn btn-primary rounded-0 py-4 px-lg-5 d-none d-lg-block">登出<i class="fa fa-arrow-right ms-3"></i></a>
        </div>
    </nav>
    <!-- Navbar End -->


    <!-- Header Start -->
    <div class="bg-light rounded h-100 d-flex align-items-center p-5">
        <div class="product-container">
            <img src="data:image/jpeg;base64, <?php echo base64_encode($info['image']); ?>" alt="Product Image">
            <div class="product-details">
                <h1><?php echo $info['pName']; ?></h1>
                <p class="price">$<?php echo $info['price']; ?></p>
                <p class="description"><?php echo $info['description']; ?></p>
                <p class="date-added">上架日期: <?php echo $info['uploadDate']; ?></p>
                <p>該人類身體零件</br>製造於(擁有者生日)：<?php echo $personInfo['birthday'];?> ,之前流著 <?php echo $personInfo['bloodType'];?> 型血</p>
                <form action="organDetail.php" method="post">
                    <input type="hidden" name="addToCartPID" value="<?php echo $info['PID']; ?>">
                    <input type="submit" value="加入購物車">
                </form>
            </div>
        </div>
    </div>
    <!-- Header End -->




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