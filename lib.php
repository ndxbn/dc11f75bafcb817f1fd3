<?php

/** アプリケーション内共通で使用する、秘密鍵的なもの。 salt として使うので、漏洩すると脆弱性になる。 */
const SECRET = 'secret_string';

/**
 * よく使用される htmlspecialchars
 *
 * @param string $str
 * @return string
 */
function e(string $str): string
{
    return htmlspecialchars($str, ENT_QUOTES);
}

/**
 * 入力値から、ハッシュ値を生成する。
 *
 * @param array $inputs
 * @return string
 */
function inputsHash(array $inputs): string
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
function validateHash(array $inputs, string $hash): bool
{
    $expectHash = inputsHash($inputs);
    // 単に `===` を使うと、タイミング攻撃が通る。
    return hash_equals($expectHash, $hash);
}


function redirectTo(string $path)
{
    header("Location: ${path}");
}
