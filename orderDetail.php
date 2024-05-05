<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>丹尼斯的保鮮盒-管理員介面</title>
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
        // 處理越權查看以及錯誤登入
        if (!isset($_SESSION['username'])) {
            echo "<script>alert('偵測到未登入'); window.location.href = 'login.php';</script>";
            exit();
        } else if ($_SESSION['role'] != "admin") {
            echo "<script>alert('無權訪問，請重新登入'); window.location.href = 'logout.php';</script>";
            exit();
        }
        
        // 處理管理員調出使用者清單
        include "database_connection.php";

        $sql = "SELECT o.OID, o.ID, o.Date, o.payAmount, od.PIDlist, p.paymentAmount, p.payMethod, u.username, u.ID
                FROM orders o
                JOIN orderDetail od ON o.OID = od.OID
                JOIN payment p ON o.OID = p.OID
                JOIN users u ON o.ID = u.ID
                WHERE o.OID = :OID";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':OID', $_GET['oid']);
        $stmt->execute();
        $orderDetails = $stmt->fetch(PDO::FETCH_ASSOC);

        $pidArray = explode(',', $orderDetails['PIDlist']);$placeholders = implode(',', array_fill(0, count($pidArray), '?'));
        $sql = "SELECT * FROM product WHERE PID IN ($placeholders)";
        $stmt2 = $db->prepare($sql);
        $stmt2->execute($pidArray);
        $products = $stmt2->fetchAll(PDO::FETCH_ASSOC);

    ?>

    <style>
div.bg-light {
    background-color: #e6e9f0; /* 容器的淺灰藍色背景 */
    border-radius: 8px; /* 圓角 */
    box-shadow: 0 2px 5px rgba(0,0,0,0.1); /* 陰影效果 */
    display: flex;
    flex-direction: column; /* 垂直排列內容 */
    align-items: left; /* 中心對齊子元素 */
    padding: 20px; /* 內邊距增加些許空間 */
}

/* 表格基本風格 */
table {
    width: 100%; /* 表格寬度全滿 */
    border-collapse: collapse; /* 邊框合併 */
    margin-top: 20px; /* 表格上邊距 */
    margin-bottom: 20px; /* 表格下邊距 */
}

/* 表格頭部風格 */
table thead th {
    background-color: #0080FF; /* 標題的海軍藍背景顏色 */
    color: #ffffff; /* 標題字體顏色 */
    padding: 10px; /* 標題內間距 */
    border: 1px solid #002d4f; /* 邊框顏色更深的海軍藍 */
}

/* 表格行和單元格風格 */
table tbody tr td {
    padding: 8px; /* 單元格內間距 */
    border: 1px solid #b6c5d6; /* 單元格邊框顏色為淺海軍藍 */
    text-align: left; /* 文字置中 */
}

/* 當鼠標懸停在行上時的樣式 */
table tbody tr:hover {
    background-color: #aac4e5; /* 懸停時的背景顏色為淺海軍藍 */
}
    </style>
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
    <!-- Topbar End -->


    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg bg-white navbar-light sticky-top p-0 wow fadeIn" data-wow-delay="0.1s">
        <a href="manageAccounts.php" class="navbar-brand d-flex align-items-center px-4 px-lg-5">
            <h1 class="m-0 text-primary"><i class="far fa-hospital me-3"></i>丹尼斯的保鮮盒</h1>
        </a>
        <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto p-4 p-lg-0">
                <a href="manageAccounts.php" class="nav-item nav-link active">管理使用者</a>
                <a href="manageShelf.php" class="nav-item nav-link active">管理貨架</a>
                <a href="manageOrders.php" class="nav-item nav-link active">管理訂單</a>
                <a href="myAccount.php" class="nav-item nav-link active"><?php echo "歡迎，". $_SESSION['userRealName'];?></a>
                <!-- <a href="aboutUs" class="nav-item nav-link">關於我們</a> -->
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
        <table class="order-table">
            <thead>
                <tr>
                    <th>訂單OID</th>
                    <th>使用者名稱</th>
                    <th>日期</th>
                    <th>訂單金額</th>
                    <th>支付金額</th>
                    <th>支付方式</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo $orderDetails['OID'];?></td>
                    <td><?php echo $orderDetails['username'];?></td>
                    <td><?php echo $orderDetails['Date'];?></td>
                    <td><?php echo $orderDetails['payAmount'];?></td>
                    <td><?php echo $orderDetails['paymentAmount'];?></td>
                    <td><?php if($orderDetails['payMethod']=="creditCard") echo "信用卡";?></td>
                </tr>
                <!-- 可以添加更多行來展示其他訂單 -->
            </tbody>
        </table></br>
        <table class="item-table">
            <thead>
                <tr>
                    <th>物品名稱</th>
                    <th>物品敘述</th>
                    <th>價格</th>
                    <th>上傳日期</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    foreach ($products as $product) {
                        echo "<tr><td>". htmlspecialchars($product['pName']) ."</td><td>". htmlspecialchars($product['description']) ."</td><td>". htmlspecialchars($product['price']) ."</td><td>". htmlspecialchars($product['uploadDate']) ."</td></tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>

    <!-- <div class="bg-light rounded h-100 d-flex align-items-center p-5">
    </div> -->

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
    <script>
        function confirmDelete() {
            return confirm('操作不可逆，請再次確認是否要執行');
        }
    </script>
</body>

</html>