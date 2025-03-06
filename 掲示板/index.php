<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet" type="text/css">
    <title>掲示板</title>
</head>
<body>
    <h1 class="Ka">掲示板</h1>
    

    <div class="toukou">
        <form action="" method="post">
            <h3>名前: <input type="text" name="name"></h3>
            <h3>タイトル: <input type="text" name="title"></h3>
            <div class="content-container">
                <h3 class="inline-Na">内容:</h3>
                <h3 class="inline">
                <textarea name="content" class="textbox"></textarea></h3>
                <!--<h3>内容: <input type="text" name="content" class="textbox"></h3>-->
            </div>
            <input type="submit" value="送信" class="sousin">
        </form>
    </div>
    <?php
    // データベース接続
    $dsn = 'mysql:dbname=border;host=localhost;charset=utf8';
    $user = 'root';
    $password = '';
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // POSTリクエスト
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // フォームの取得
        $name = isset($_POST['name']) && $_POST['name'] !== '' ? $_POST['name'] : '名無し';
        $title = isset($_POST['title']) ? $_POST['title'] : '';
        $content = isset($_POST['content']) ? $_POST['content'] : '';

        // 空白確認
        if ($title === '' || $content === '') {
            echo '<p id="message">タイトルと内容を入力してください。</p>';
        } else {
            // データベースにデータを送る
            $sql = 'INSERT INTO board (name, title, content) VALUES (?, ?, ?)';
            $stmt = $dbh->prepare($sql);
            $stmt->execute([$name, $title, $content]);

            print '<p id="message">投稿が成功しました！</p>';
        }
    }

    // 表示
    $sql = 'SELECT * FROM board WHERE 1';
    $stmt = $dbh->prepare($sql);
    $stmt->execute();

    while ($rec = $stmt->fetch(PDO::FETCH_ASSOC)) {
        print '<div class="etu">';
        print '<h3 class="naiyou">名前: ' . htmlspecialchars($rec['name'], ENT_QUOTES, 'UTF-8') . '</h3>';
        print '<h3 class="naiyou">タイトル: ' . htmlspecialchars($rec['title'], ENT_QUOTES, 'UTF-8') . '</h3>';
        print '<p>内容: ' . htmlspecialchars($rec['content'], ENT_QUOTES, 'UTF-8') . '</p>';
        print '<p>投稿時間: ' . htmlspecialchars($rec['save_time'], ENT_QUOTES, 'UTF-8') . '</p>';
        print '</div>';
    }

    $dbh = null;
    ?>
</body>
<script>
setTimeout(function() {
    var message = document.getElementById('message');
    if (message) {
        message.style.display = 'none';
    }
}, 3000); //3秒
</script>
<footer></footer>
</html>
