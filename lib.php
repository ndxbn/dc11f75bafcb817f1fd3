<?php

/** アプリケーション内共通で使用する、秘密鍵的なもの。 salt として使うので、漏洩すると脆弱性になる。 */
const SECRET = 'secret_string';

/**
 * よく使用される htmlspecialchars
 *
 * @param string $str
 * @return string
 */
function e($str): string
{
    return htmlspecialchars($str, ENT_QUOTES);
}

/**
 * POST されてきたデータを、型チェックして展開した配列を返す。
 *
 * check.php と store.php でコピペが発生したので、関数に切り出した。
 *
 * @return array
 * @throws TypeError POST じゃなかった場合に、こいつが TypeError を返す
 */
function getInputs(): array
{

    /** POST されてくる値と、それらの Validation ルール */
    $inputsArgs = [
        'id' => FILTER_DEFAULT,
        'mail' => FILTER_SANITIZE_EMAIL, // FILTER_VALIDATE_EMAIL だと、RFC に準拠していない某ドコモとかのメアドが死ぬので。
        'mail-confirm' => FILTER_SANITIZE_EMAIL
    ];

    return filter_input_array(INPUT_POST, $inputsArgs, false);
}

/**
 * 入力値から、ハッシュ値を生成する。
 *
 * @param array $inputs
 * @return string
 */
function inputsHash($inputs): string
{
    $inputsWithSecret = $inputs;
    $inputsWithSecret [] = SECRET;

    return hash('SHA256', implode('', $inputsWithSecret));
}

/**
 * 入力された値とそのハッシュ値を、タイミング攻撃セーフな方法で検証する。
 *
 * Session が使用できないので、フォームの改竄を検出するのに使用する。
 *
 * @param array $inputs
 * @param string $hash
 * @return bool
 */
function validateHash($inputs, $hash): bool
{
    $expectHash = inputsHash($inputs);
    // 単に `===` を使うと、タイミング攻撃が通る。
    return hash_equals($expectHash, $hash);
}

function redirectTo(string $path): void
{
    header("Location: ${path}");
}

/**
 * @param string[] $errorMessages
 * @return bool if true, redirected.
 */
function kickback($errorMessages): bool
{
    // なにかしらの入力値が invalid
    if ([] !== $errorMessages) {
        $query = http_build_query(['error_messages' => $errorMessages]);
        redirectTo("/index.php?${query}");
        return true;
    }
    return false;
}
