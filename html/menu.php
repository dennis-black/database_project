<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>黑貓工作室-選單</title>
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

        if (!isset($_SESSION['username'])) {
            echo "<script>alert('偵測到未登入'); window.location.href = 'login.php';</script>";
            exit(); 
        }
        $username = $_SESSION['username'];
    ?>

</head>
<body>
    <header>
    <nav>
        <a href=""><img class="logo" src="./assets/blackcatstudio_banner.png"></a>
        <ul>
        <li><a href="menu.php" style="color: #99c2ff; font-weight: bolder;">主選單</a></li>
        <li><a href="logout.php">登出</a></li>
        </ul>
    </nav>
    </header>
    <section id="home">
    <h1>你好, <?php echo htmlspecialchars($username); ?>，很高興見到你</h1>
    </section>

    <section id="intro">
    <h2>選單</h2>
    <ul>
        <li><a href="foodSelector.php">食物選擇器</a></li>
        <li><a href="messageBoard.php">留言板</a></li>
        <li><a href="">表單</a></li>
    </ul>

    </section>
    <footer>
    <p>&copy; 2023 黑貓工作室</p>
    </footer>
</body>
</html>
