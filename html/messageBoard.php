<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dennis-Black 留言板</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f8ff; 
        }
        
        /* body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        } */

        header {
            background-color: #333; /* 主標題背景色 */
            color: #fff;
            text-align: center;
            padding: 2rem 0;
        }

        header h1 {
            font-size: 36px;
        }

        .backTo{
            background-color: rgb(223, 234, 240);
            border-radius: 5px;
        }

        .backTo:hover{
            background-color: rgb(228, 243, 255);
            
        }

        img{
            max-width: 100%;
            display: block;
        }

        main {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff; /* 白色內容背景 */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        article {
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            border: 1px solid #ddd; 
        }

        h2 {
            font-size: 24px;
        }

        footer {
            background-color: #333; /* 頁腳背景色 */
            color: #fff;
            text-align: center;
            padding: 10px 0;
        }

        /* form {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        td {
            padding: 8px;
        }
        input, textarea {
            width: 100%;
            padding: 8px;
            margin: 5px 0 20px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }*/
        .formButton {
            width: 100%;
            background-color: #4CAF50;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .formButton :hover {
            background-color: #45a049; 
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            
        }
        .messageTable {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            display: block;
            overflow-x: auto;
            
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
            color: #333;
        }

        tr:hover {
            background-color: #f5f5f5;
        }
    </style>
     <script>
        function validateForm() {
            var nickname = document.forms["messageForm"]["nickname"].value;
            var message = document.forms["messageForm"]["message"].value;

            if (nickname.trim() === "" || message.trim() === "") {
                alert("所有欄位都不能為空");
                return false;
            }
            return true;
        }
    </script>
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
        <h1>留言板</h1>
    </header>
    <main>
        <button class="backTo" onclick="history.back()">返回到上一頁</button>
        <button class="backTo" onclick="location.href='menu.php'">回到選單</button></br></br>        
        <article>
        <!-- <h2></h2> -->
      <form name="messageForm" method="post" action="messageBoard.php" onsubmit="return validateForm()">
        <table>
            <tr>
                <td>你的暱稱</td>
                <td><input name="nickname" value = <?php echo htmlspecialchars($username);?>></td>
            </tr>
            <tr>
                <td>你的留言</td>
                <td><textarea name="message" style="width: 100%; height: 150px;"></textarea></td>
            </tr>
            <tr>
                <td><button class="formButton" type="button" onclick="this.form.reset();">清空</button></td>
                <td><button class="formButton" type="submit">送出</button></td>
            </tr>
        </table>
      </form>
      <div class="pagination">
        <form action="messageBoard.php" method="get">
            <label for="messagesPerPage">每頁顯示：</label>
            <select name="messagesPerPage" id="messagesPerPage" onchange="this.form.submit()">
                <option value="10" <?php echo (isset($_GET['messagesPerPage']) && $_GET['messagesPerPage'] == 10) ? 'selected' : ''; ?>>10</option>
                <option value="20" <?php echo (isset($_GET['messagesPerPage']) && $_GET['messagesPerPage'] == 20) ? 'selected' : ''; ?>>20</option>
                <option value="50" <?php echo (isset($_GET['messagesPerPage']) && $_GET['messagesPerPage'] == 50) ? 'selected' : ''; ?>>50</option>
                <option value="100" <?php echo (isset($_GET['messagesPerPage']) && $_GET['messagesPerPage'] == 100) ? 'selected' : ''; ?>>100</option>
            </select>
            <hr style="height:2px;border-width:0;color:gray;background-color:gray">
            <span class = "messageTable">
            <?php
                include "database_connection.php";

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $nickname = $_POST["nickname"];
                    $message = $_POST["message"];
                    $current_date = date("Y-m-d");
                    $current_time = date("H:i:s");

                    $stmt = $db->prepare("INSERT INTO messageBoard (date, time, nickname, content) VALUES (:date, :time, :nickname, :message)");
                    
                    $stmt->bindParam(':date', $current_date, PDO::PARAM_STR);
                    $stmt->bindParam(':time', $current_time, PDO::PARAM_STR);
                    $stmt->bindParam(':nickname', $nickname, PDO::PARAM_STR);
                    $stmt->bindParam(':message', $message, PDO::PARAM_STR);

                    if ($stmt->execute()) {
                        echo "<script>alert('留言已成功发布');</script>";
                    } else {
                        echo "Error: " . $stmt->errorInfo()[2];
                    }

                    $stmt->closeCursor(); 
                }


                $perPage = isset($_GET['messagesPerPage']) ? (int)$_GET['messagesPerPage'] : 10;
                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $startAt = ($page - 1) * $perPage;

                $result = $db->query("SELECT COUNT(*) AS total FROM messageBoard");
                $totalRow = $result->fetch(PDO::FETCH_ASSOC);
                $total = $totalRow['total'];
                $totalPages = ceil($total / $perPage);

                $sql = "SELECT * FROM messageBoard ORDER BY date DESC, time DESC LIMIT $startAt, $perPage";
                $result = $db->query($sql);

                if ($result) {
                    if ($result->rowCount() > 0) {
                        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                            echo "<div class='message'>";
                            echo "<div class='message-header'>";
                            echo "<span class='nickname'><b>User:</b>" . htmlspecialchars($row["nickname"]) . "</span><br/>";
                            echo "<span class='datetime'><b>Time:</b>" . htmlspecialchars($row["date"]) . " " . htmlspecialchars($row["time"]) . "</span>";
                            echo "</div>";
                            echo "<div class='message-content'>" . htmlspecialchars($row["content"]) . "</div>";
                            echo "</div><hr/>";
                        }

                        echo "</span><p>第";
                        for ($i = 1; $i <= $totalPages; $i++) {
                            echo "<a href='?page=$i&messagesPerPage=$perPage'>$i</a> ";
                        }
                        echo "頁</p>";
                    } else {
                        echo "沒有留言可顯示";
                    }
                } else {
                    echo "查詢失敗: " . $db->error;
                }

            ?>
            
            
            <h4 style="float: right;">更新時間: Jan. 24 2024</h4>
        </article>
    </main>
    <footer>
        <p>&copy; 2023 黑貓工作室</p>
    </footer>
</body>
</html>