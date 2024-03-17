<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dennis-Black 工具箱</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f8ff; /* 淺藍色背景 */
        }

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
            border: 1px solid #ddd; /* 文章邊框 */
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
    </style>
</head>
<body>
    <header>
        <h1>食物選擇器</h1>
        <!-- <h2>for Windows</h2> -->
    </header>
    <main>
        <button class="backTo" onclick="history.back()">返回到上一頁</button>
        <button class="backTo" onclick="location.href='../../../index.html'">回到首頁</button></br></br>        
        <article>
            <h2>隨機抽樣要吃啥</h2>
            <p>這是一個可自行輸入的食物選擇器，以換行作為每一筆數據的分隔</p>
            <div>
                <button onclick="clearTextarea()">清空輸入欄</button>
                <button onclick="defaultAllTemplate()">載入所有預設模板</button>
            </div></br>
            <div>預設食物模板：</br></br>
                <button onclick="stapleFoodTemplate()">主食</button>
                <button onclick="dessertTemplate()">甜點</button>
                <button onclick="snackTemplate()">小吃</button>
                <button onclick="beverageTemplate()">飲料</button>
            </div></br>
            <div>使用者自訂模板：</br></br>
                <?php
                
                ?>
            </div></br>
            <textarea id="foodList" style="height: 200px; width: 100%;"></textarea>
                <br>
                <button onclick="randomSelect()">隨機抽樣</button>
                <button onclick="">儲存為使用使用者自訂模板</button>
                <h1 class="output" onclick="randTimes()"></h1>
            <h4 style="float: right;">更新時間: Sept. 30 2023</h4>
        </article>
        <script>
            async function randomSelect() { //抽出一個隨機結果
                var textareaContent = document.getElementById('foodList').value;
                var dataArray = textareaContent.split('\n').filter(Boolean);

                var outputElement = document.querySelector('.output');

                var randNum = Math.floor(Math.random() * 20);
                var delay = 30; // 每次抽樣間隔時間（毫秒）

                for (var i = 0; i < randNum; i++) {
                    await new Promise(resolve => setTimeout(resolve, delay));
                    var randomIndex = Math.floor(Math.random() * dataArray.length);
                    outputElement.textContent = '隨機抽樣的食物是：' + dataArray[randomIndex];
                }
            }

            function stapleFoodTemplate(){
                var textareaContent = document.getElementById('foodList');
                if (textareaContent.value != '') textareaContent.value = textareaContent.value + "\n";
                textareaContent.value = textareaContent.value + "滷肉飯\n炒飯\n三杯雞飯\n雞肉飯\n丼飯\n肉燥飯\n咖哩飯\n義大利麵\n乾拌麵\n炸排骨飯\n餛飩\n燉飯\n披薩\n滷味\n火鍋\n飯糰\n牛排\n雞排\n豬排\n拌飯\n拉麵\n湯麵\n陽春麵\n雞肉炒飯\n鍋貼\n乾煸牛腩炒飯\n培根炒飯\n豬腳麵線\n牛肉麵";
            }

            function dessertTemplate(){
                var textareaContent = document.getElementById('foodList');
                if (textareaContent.value != '') textareaContent.value = textareaContent.value + "\n";
                textareaContent.value = textareaContent.value + "蛋糕\n曲奇餅\n布朗尼\n馬卡龍\n乳酪蛋糕\n巧克力慕斯\n法式可頌\n奶油泡芙\n千層蛋糕\n提拉米蘇\n鮮果塔\n冰淇淋\n奶酪蛋糕\n奶油蛋糕捲\n巧克力\n糖餅\n布丁";
            }

            function snackTemplate(){
                var textareaContent = document.getElementById('foodList');
                if (textareaContent.value != '') textareaContent.value = textareaContent.value + "\n";
                textareaContent.value = textareaContent.value + "漢堡\n薯條\n鹽酥雞\n炸雞\n薯餅\n蛋餅\n小熱狗\n雞塊\n蔥抓餅\n燒賣\n魚丸\n沙威瑪\n三明治\n沙拉\n茶葉蛋\n粽子\n竹筍糕\n手捲壽司\n水煎包";
            }

            function beverageTemplate(){
                var textareaContent = document.getElementById('foodList');
                if (textareaContent.value != '') textareaContent.value = textareaContent.value + "\n";
                textareaContent.value = textareaContent.value + "咖啡\n紅茶\n綠茶\n烏龍茶\n奶茶\n珍珠奶茶\n綠奶茶\n水果茶\n果汁\n柳橙汁\n蘋果汁\n鳳梨汁\n西瓜汁\n椰子水\n豆漿\n可樂\n氣泡水\n麥片\n奶昔";
            }

            function defaultAllTemplate(){
                stapleFoodTemplate();
                dessertTemplate();
                snackTemplate();
                beverageTemplate();
            }

            function clearTextarea(){
                var textareaContent = document.getElementById('foodList');
                textareaContent.value = "";
            }
        
        </script>
    </main>
    <footer>
        <p>&copy; 2023 黑貓工作室</p>
    </footer>
</body>
</html>