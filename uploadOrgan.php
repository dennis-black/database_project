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
        form {
            background-color: white; /* 表單背景設為白色 */
            padding: 20px;
            border-radius: 8px; /* 圓角邊框 */
            box-shadow: 0 0 10px rgba(0,0,0,0.1); /* 輕微陰影效果 */
            width: 100%; /* 表單寬度 */
            max-width: 500px; /* 最大寬度為500px */
        }

        h1 {
            color: #333; /* 深灰色標題 */
            text-align: center; /* 標題文字置中 */
        }

        label {
            margin-top: 10px; /* 每個標籤上方留白 */
            display: block; /* 確保每個元素佔滿一整行 */
            color: #666; /* 文字顏色 */
            font-size: 16px; /* 字體大小 */
        }

        input[type="text"],
        textarea,
        input[type="file"] {
            width: calc(100% - 22px); /* 輸入框寬度為容器寬度減去邊框 */
            padding: 10px; /* 內邊距 */
            margin-top: 5px; /* 上邊距 */
            border: 1px solid #ddd; /* 邊框顏色 */
            border-radius: 4px; /* 圓角邊框 */
        }

        textarea {
            height: 100px; /* 文本域高度 */
            resize: vertical; /* 允許垂直調整大小 */
        }

        input[type="submit"] {
            background-color: #0056b3; /* 提交按鈕背景色 */
            color: white; /* 文字顏色 */
            padding: 10px 20px; /* 內邊距 */
            border: none; /* 無邊框 */
            border-radius: 4px; /* 圓角邊框 */
            cursor: pointer; /* 滑鼠指針變為手型 */
            display: block; /* 確保占滿整行 */
            width: 100%; /* 寬度 */
            margin-top: 20px; /* 上邊距 */
        }

        input[type="submit"]:hover {
            background-color: #004494; /* 鼠標懸停時的背景色 */
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

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            include "database_connection.php";
            
            $pName = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $type = $_POST['type'];
            $image = $_FILES['image'];
            $todayDate = date('Y-m-d H:i:s');
            
            if (empty($pName) || empty($price) || $type == "" || $image['error'] !== UPLOAD_ERR_OK) {
                echo "<script>alert('請填寫所有欄位並選擇一個圖片');</script>";
            } else {
                $check = getimagesize($image["tmp_name"]);
                if ($check !== false) {
                    $imageContent = file_get_contents($image["tmp_name"]); 

                    if ($imageContent !== false) {
                        $sql = "INSERT INTO product (pName, ownerID, description, price, type, image, uploadDate) VALUES (?, ?, ?, ?, ?, ?, ?)";
                        $stmt = $db->prepare($sql);
                        $stmt->bindParam(1, $pName);
                        $stmt->bindParam(2, $_SESSION['user_id']);
                        $stmt->bindParam(3, $description);
                        $stmt->bindParam(4, $price);
                        $stmt->bindParam(5, $type);
                        $stmt->bindParam(6, $imageContent, PDO::PARAM_LOB);
                        $stmt->bindParam(7, $todayDate);

                        if ($stmt->execute()) {
                            echo "<script>alert('物品已成功上傳');</script>";
                        } else {
                            echo "<script>alert('物品上傳失敗：" . $stmt->errorInfo()[2] . "');</script>";
                        }
                    } else {
                        echo "<script>alert('圖片讀取失敗');</script>";
                    }
                } else {
                    echo "<script>alert('所上傳文件非有效的圖片');</script>";
                }
            }
            // $db->close();
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
            <a href="organs.php" class="nav-item nav-link active">前往前往保鮮盒</a>
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
        <form action="uploadOrgan.php" method="POST" enctype="multipart/form-data">
            <h3>上傳你的物品</h3>
            <label for="name">輸入名稱:</label>
            <input type="text" name="name" id="name" required><br><br>

            <label for="description">請完成200字以內的敘述:</label>
            <textarea name="description" id="description"></textarea><br><br>

            <label for="price">設定價格:</label>
            <input type="text" name="price" id="price" required><br><br>

            <label for="price">分類:</label>
            <select name="type" class="form-control border-0" >
                <option value="" selected>請選擇</option>
                <option value="組織">組織</option>
                <option value="器官">器官</option>
            </select>

            <label for="image">上傳圖片:</label>
            <input type="file" name="image" id="image" required><br><br>

            <input type="submit" value="上傳物品">
        </form>
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