<?php
require_once 'lib.php';

try {
    ['id' => $id, 'mail' => $mail, 'mail-confirm' => $mailConfirm] = $inputs = getInputs();
} catch (TypeError $e) {
    header('HTTP/1.1  400 Bad Request');
    echo '不正なアクセスです。';
    return ;
}

$hashString = $_POST['x-hash'] ?? null;
// validation
/** @var string[] $errorMessages */
$errorMessages = [];
// 入力されてきたデータが、確認画面で改ざんされていないかの確認
// CSRF 対策も（副次的に）できてる（はず）
if (($hashString === null) // 直接このページにアクセスされたら、こっちにかかる
    || (!validateHash($inputs, $hashString))
) {
    $errorMessages[] = json_encode([$hashString, inputsHash($inputs)]);
    $errorMessages[] = '確認画面で入力値が変更されたっぽいです。クラッキングやめてください＞＜';
}
// check.php で validation が通ったものじゃない場合は、x-hash が合わないはずなので、すでに弾かれてるはず。
// なので、check.php でやったような validation はここでは不要。

if(kickback($errorMessages)) {
    return ;
}

file_put_contents("storage/${id}.txt", $mail);
?>
<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>登録しました</title>
</head>
<body>
登録しました。<a href="index.php">戻る</a>
</body>
</html>
