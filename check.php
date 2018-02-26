<?php
// フォームの入力値の確認をするページ
require_once 'lib.php';

// POST されてきたものを、Validation して、変数に展開する
/** POST されてくる値と、それらの Validation ルール */
const POST_ARGS = [
    'id' => FILTER_DEFAULT,
    'mail' => FILTER_SANITIZE_EMAIL,
    'mail-confirm' => FILTER_SANITIZE_EMAIL
];
// 型チェックまではここでされる
['id' => $id, 'mail' => $mail, 'mail-confirm' => $mailConfirm] = $inputs =
    filter_input_array(INPUT_POST, POST_ARGS, false);

// validation
/** @var string[] $errorMessages */
$errorMessages = [];
if (empty($id)) {
    $errorMessages[] = '識別番号は空にできません。なにか入力してください。';
}
// sanitize なので、空になる
if (empty($mail)) {
    $errorMessages[] = 'メールアドレスが空か、使えない文字が含まれてます。正直にメールアドレス入れてください。';
}
if ($mail !== $mailConfirm) {
    $errorMessages[] = "メールアドレスが、再入力したやつと一致してません。 ${mail} と ${mailConfirm} でした。";
}

if ([] !== $errorMessages) {
    $query = http_build_query(['error_messages' => $errorMessages]);
    redirectTo("/index.php?${query}");
    return ;
}
