<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>黑貓工作室-登入</title>
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
    if ($_SERVER['REQUEST_METHOD'] === "POST"){
        include "database_connection.php";
        $username = $_POST['username']?? '';
        $password = $_POST['password']?? '';

        $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id']; 
            $_SESSION['username'] = $user['username']; 
    
            header('Location: menu.php'); 
            exit;
        } else {
            // 登录失败，显示错误信息
            echo "<script>alert('使用者名稱或密碼錯誤');</script>";
        }
    }
    ?>
</head>
<body>
    <header>
    <nav>
        <a href="https://dennis-black.github.io/website/index.html"><img class="logo" src="./assets/blackcatstudio_banner.png"></a>
        <ul>
        <li><a href="index.html">回首頁</a></li>
        <li><a href="index.html" style="color: #99c2ff; font-weight: bolder;">登入</a></li>
        </ul>
    </nav>
    </header>
    <section id="home">
    <h1>黑貓工作室 </h1>
    <h2>Black Cat Studio</h2>
    </section>

    <section id="intro">
    <h2>使用者登入</h2>
        <form action="login.php" method="post" autocomplete="off">
        <table>
            <tr>
            <td><p>使用者ID</p></td>
            <td><input type="text" name="username" placeholder="輸入使用者ID"></td>
            </tr>
            <tr>
            <td><p>密碼</p></td>
            <td><input type="password" name="password" placeholder="輸入密碼"></td>
            </tr>
            <tr>
            <td><input type="submit" name="login-btn" value="登入"></td>
            <!-- <td><a href="#">忘記密碼？</a></td> -->
            </tr>
            <tr>
            <td colspan = "2"><a href = "register.php">沒有帳號嗎？註冊一個吧</a></td>
            </tr>
        </table>
        </form>        
    </section>
    <footer>
    <p>&copy; 2023 黑貓工作室</p>
    </footer>
</body>
</html>
