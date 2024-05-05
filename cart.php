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
       /* 基本重置和字體設置 */
        h1, h2, h3, p {
            margin: 0;
            padding: 0;
            font-family: 'Helvetica Neue', Arial, sans-serif;
            color: #333;
        }

        /* 主容器設計，包含背景和陰影 */
        .container_list {
            width: 90%;
            margin: 20px auto;
            background-color: #f4f8ff; /* 淺藍色背景 */
            padding: 20px;
            border-radius: 8px; /* 圓角邊框 */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05); /* 輕微的陰影 */
        }

        /* 排序條樣式 */
        .sort-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .sort-bar select, .sort-bar input[type="text"], .sort-bar input[type="submit"] {
            padding: 10px 15px;
            margin-right: 10px;
            border: 1px solid #d1e3ff; /* 淺藍色邊框 */
            border-radius: 6px; /* 圓角邊框 */
            background-color: #ffffff; /* 白色背景 */
        }

        .sort-bar input[type="submit"] {
            cursor: pointer;
            background-color: #007bff; /* 較深的藍色背景 */
            color: white;
            border: none;
        }

        h3 {
            font-size: 1.5rem;
            color: #0056b3; /* 淺藍色文字 */
            margin-bottom: 20px;
        }

        /* 產品列表樣式 */
        .product-list {
            list-style: none;
            padding: 0;
        }

        .product-list .product {
            display: flex;
            align-items: center;
            border-bottom: 1px solid #e2e5ec; /* 更淺的藍色分隔線 */
            padding: 10px 0;
        }

        .product-list .product img {
            width: 100px; /* 保持圖片為方形 */
            height: 100px;
            object-fit: cover; /* 保證圖片充滿容器 */
            margin-right: 20px;
            border-radius: 5px; /* 圓角 */
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

        /* 分頁樣式 */
        .pagination {
            list-style: none;
            padding: 0;
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .pagination .page-item {
            margin: 0 5px;
        }

        .pagination .page-link {
            display: block;
            padding: 8px 12px;
            background-color: #d1e3ff;
            color: #0056b3;
            border-radius: 4px;
            text-decoration: none;
        }

        .pagination .page-link:hover {
            background-color: #b9d1f8;
        }
        .remove-from-cart {
            padding: 10px 20px;
            font-size: 1rem;
            color: white;
            background: linear-gradient(145deg, #CE0000, #FF2D2D);
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16);
        }

        .remove-from-cart:hover {
            background: linear-gradient(145deg, #FF9797, #FF9797);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.24);
        }

        .remove-from-cart:active {
            background: #0041a8;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.12);
        }

        table {
            width: 100%; /* 表格寬度占滿容器 */
            border-collapse: collapse; /* 邊框合併 */
            background-color: #f0f8ff; /* 淺藍色背景 */
            font-family: Arial, sans-serif; /* 使用Arial或無襯線字體 */
        }

        /* 表頭樣式 */
        th {
            background-color: #e0efff; /* 表頭使用略深的藍色 */
            color: #333; /* 文字顏色為深灰 */
            padding: 10px; /* 內邊距 */
            font-size: 16px; /* 字體大小 */
            border-bottom: 2px solid #ccc; /* 底部有灰色邊框 */
        }

        /* 表格行樣式 */
        td {
            text-align: left; /* 文字居中顯示 */
            padding: 8px; /* 內邊距 */
            font-size: 14px; /* 字體大小 */
        }

        /* 表格行條紋效果 */
        tr:nth-child(odd) {
            background-color: #e6f1ff; /* 淺藍色條紋 */
        }

        /* 按鈕樣式 */
        button {
            background-color: #007bff; /* 按鈕背景色 */
            color: white; /* 按鈕文字顏色 */
            padding: 6px 12px; /* 內邊距 */
            border: none; /* 無邊框 */
            border-radius: 4px; /* 圓角邊框 */
            cursor: pointer; /* 鼠標樣式 */
            transition: background-color 0.3s; /* 過渡效果 */
        }

        /* 鼠標懸停在按鈕上時的效果 */
        button:hover {
            background-color: #0056b3; /* 按鈕深藍色 */
        }

        /* 產品圖片樣式 */
        img {
            width: 100px; /* 圖片寬度 */
            height: auto; /* 高度自動 */
        }

        .product-link {
            text-decoration: none;
            color: inherit; /* Ensures the text doesn't change color */
            display: inline-block; /* Makes the link wrap its content only */
        }

        .product-link img {
            vertical-align: middle; /* Aligns the image nicely with the text */
            margin-right: 10px; /* Adds some space between the image and the text */
        }

        .product-list th, .product-list td {
            padding: 10px; /* Adds padding for table cells */
            border-bottom: 1px solid #ccc; /* Adds a light border to each row */
        }

        .remove-from-cart {
            background-color: #f44336; /* Red background for removal button */
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
        }

        .product-list .total-line {
            font-size: 18px;
            font-weight: bold;
            text-align: right;
            padding-top: 15px;
        }

        .checkout-container {
            text-align: right;
            margin-top: 20px;
        }

        .checkout-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .checkout-button:hover {
            background-color: #45a049;
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

        
        // $recordsPerPage = 20;
        // $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        // $offset = ($page - 1) * $recordsPerPage;

        
        // $sortTime = $_GET['sort-time'] ?? 'newest';
        // $sortPrice = $_GET['sort-price'] ?? 'highest';
        // $sortCategory = $_GET['sort-category'] ?? '';
        // $searchTerm = $_GET['search'] ?? '';

        
        // $sql = "SELECT * FROM product WHERE 1=1";
        $sql = "SELECT * FROM product t1
                JOIN cart t2 ON t1.PID = t2.PID
                WHERE (t2.ID = :ID AND t1.display = 1)";
        // $countSql = "SELECT COUNT(*) FROM product t1
        //             JOIN cart t2 ON t1.PID = t2.PID
        //             WHERE t2.ID = :ID";

        $stmt = $db->prepare($sql);
        $stmt -> bindParam(':ID', $_SESSION['user_id']);
        $stmt->execute();
        
        // $countStmt = $db->prepare($countSql);
        // $countStmt -> bindParam(':ID', $_SESSION['user_id']);
        // $countStmt->execute();
        // $totalRecords = $countStmt->fetchColumn();
        // $numPages = ceil($totalRecords / $recordsPerPage);
    ?>
    <?php
        if (($_SERVER['REQUEST_METHOD'] === "POST")&&($_POST['removeFromCart'])){
            $checkCartExists = $db->prepare("SELECT COUNT(*) FROM cart WHERE (PID = :PID AND ID = :ID)");
            $checkCartExists -> bindParam(':PID', $_POST['removeFromCartPID']);
            $checkCartExists -> bindParam(':ID', $_SESSION['user_id']);
            $checkCartExists -> execute();
            if($checkCartExists->fetchColumn() > 0) { //若找到可刪除的資料
                $stmt = $db->prepare("DELETE FROM cart WHERE (ID = :userID AND PID = :PID)");
                $stmt -> bindParam(':userID', $_SESSION['user_id']);
                $stmt -> bindParam(':PID', $_POST['removeFromCartPID']);
                $stmt->execute();
                echo "<script>alert('已從購物車中移除該項目');</script>";
                echo '<script>window.location.href="cart.php";</script>';
            } else {
                echo "<script>alert('未找到可刪除的內容');</script>";
                echo '<script>window.location.href="cart.php";</script>';
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
                <a href="myOrders.php" class="nav-item nav-link active">我的訂單</a>
                <a href="myAccount.php" class="nav-item nav-link active"><?php echo "歡迎，". $_SESSION['userRealName'];?></a>
            </div>
            <a href="logout.php" class="btn btn-primary rounded-0 py-4 px-lg-5 d-none d-lg-block">登出<i class="fa fa-arrow-right ms-3"></i></a>
        </div>
    </nav>
    <!-- Navbar End -->


    <!-- Header Start -->
    <div class="bg-light rounded h-100 d-flex align-items-center p-5">
    <div class="container_list">
        <h3>我的購物車</h3>
        <table class="product-list">
            <tr><th>名稱</th><th>價格</th><th>上架時間</th><th></th></tr>
            <?php   
                $paymentAmount = 0;
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td><a href='organDetail.php?pid=".$row['PID']."' class='product-link'><img src='data:image/jpeg;base64,".base64_encode($row['image'])."' alt='Product Image'><span class='name'>" . htmlspecialchars($row['pName']) . "</span></a></td>";
                    echo "<td><span class='price'>$" . number_format($row['price'], 2) . "</span></td>";
                    $paymentAmount += $row['price'];
                    echo "<td><span class='upload-date'>" . $row['uploadDate'] . "</span></td>";
                    echo "<td><form action='cart.php' method='post'><input name='removeFromCartPID' value='".$row['PID']."' type='hidden'><button type='submit' name='removeFromCart' value='true' class='remove-from-cart'>從我的購物車移除</button></form></td>";
                    echo "</tr>";
                }
                echo "<tr><td colspan='4' class='total-line'>總金額: $" . number_format($paymentAmount, 2) . "</td></tr>";
            ?>
        </table>
        <form action="checkout.php">
            <?php if($stmt->rowCount() >= 1)  echo "<button type='submit' class='checkout-button'>前往結帳</button>";?>
        </form>
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