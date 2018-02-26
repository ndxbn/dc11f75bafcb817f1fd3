<?php
// 情報を入力する、メインページ
require_once 'lib.php';

// check.php から Validation エラーで戻ってきた場合に、メッセージ（複数）を表示するために使用
/** @var string[] $errorMessages */
$errorMessages = $_GET['error_messages'] ?? [];
// 一応、型のValidation しておく
if (!is_array($errorMessages)) {
    $errorMessages = (array)$errorMessages;
}
?>
<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PHP</title>
</head>
<body>
<?php foreach($errorMessages as $message): ?>
<div><?= e($message) ?></div>
<?php endforeach; // errorMessages ?>
<form action="check.php" method="POST">
    <div>
        <label for="id">識別番号: </label> <input type="text" id="id" name="id" placeholder="半角英数">
    </div>
    <div>
        <label for="mail">メールアドレス: </label> <input type="text" id="mail" name="mail">
    </div>
    <div>
        <label for="mail-confirm">メールアドレス（確認用）: </label> <input type="text" id="mail-confirm" name="mail-confirm">
    </div>

    <button type="submit">OK</button>
</form>
</body>
</html>
