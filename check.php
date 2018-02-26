<?php
// フォームの入力値の確認をするページ
require_once 'lib.php';

try {
    ['id' => $id, 'mail' => $mail, 'mail-confirm' => $mailConfirm] = $inputs = getInputs();
} catch (TypeError $e) {
    header('HTTP/1.1  400 Bad Request');
    echo '不正なアクセスです。';
    return ;
}

// validation
/** @var string[] $errorMessages */
$errorMessages = [];
if (empty($id)) {
    $errorMessages[] = '識別番号は空にできません。なにか入力してください。';
}
if (empty($mail)) { // sanitize なので、空になる
    $errorMessages[] = 'メールアドレスが空か、使えない文字が含まれてます。正直にメールアドレス入れてください。';
}
if ($mail !== $mailConfirm) {
    $errorMessages[] = "メールアドレスが、再入力したやつと一致してません。 ${mail} と ${mailConfirm} でした。";
}

if (kickback($errorMessages)) {
    return;
}

$hash = inputsHash($inputs);
?>
<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>入力値の確認</title>
</head>
<body>
<div>この情報で登録しますが、よろしいですか？</div>
<div>
    <ul>
        <li>識別番号: <?= e($id) ?></li>
        <li>メールアドレス: <?= e($mail) ?></li>
    </ul>
</div>

<form action="store.php" method="post">
    <input type="hidden" name="id" value="<?= e($id) ?>">
    <input type="hidden" name="mail" value="<?= e($mail) ?>">
    <input type="hidden" name="mail-confirm" value="<?= e($mailConfirm) ?>">
    <input type="hidden" name="x-hash" value="<?= e($hash) ?>">
    <button type="submit">良いので、保存する</button>
</form>
</body>
</html>
