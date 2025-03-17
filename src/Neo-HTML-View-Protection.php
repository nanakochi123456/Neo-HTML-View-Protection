<?php
/**
 * Plugin Name: Neo HTML View Protection
 * Description: view-source: でもHTMLをわずか数文字にする
 * Version: 0.1
 * Author: Nano Yozakura
 * License: GPL2
 */

// 10秒のクッキーを発行してエンコードされたURLを保存
function pgv_set_cookie_and_redirect() {
    // 現在のURLを取得
    $current_url = $_SERVER['REQUEST_URI'];

    // URLをBase64エンコード
    $encoded_url = base64_encode($current_url);

    // JavaScriptでエンコードされたURLをデコードしてリダイレクトするスクリプトを挿入
    echo '
    <script>
        document.cookie="encoded_url=' . $encoded_url . ';max-age=10;path=/";
        var encodedUrl="' . $encoded_url . '",decodedUrl=atob(encodedUrl);
        window.location.href=decodedUrl;
    </script>';
    exit;
}

// クッキーからエンコードされたURLを取得し、リダイレクトする
function pgv_redirect_from_cookie() {
    if (isset($_COOKIE['encoded_url'])) {
        // クッキーにエンコードされたURLがある場合、デコードしてリダイレクト
        $encoded_url = $_COOKIE['encoded_url'];
        $decoded_url = base64_decode($encoded_url);

        setcookie("encoded_url", "", time() - 3600, "/"); 

        // 現在のページとリダイレクト先が異なる場合にリダイレクト
        if ($_SERVER['REQUEST_URI'] !== $decoded_url) {
            header("Location: $decoded_url");
            exit;
        }
    }
}

// クッキーがあるか確認してリダイレクトする
function pgv_check_and_redirect() {
    if(!is_user_logged_in()) {
        // クッキーがセットされている場合、リダイレクト処理を実行
        if (isset($_COOKIE['encoded_url'])) {
            pgv_redirect_from_cookie();
        } else {
            // クッキーがない場合、10秒のクッキーを発行してリダイレクト
            pgv_set_cookie_and_redirect();
        }
    }
}

// 高い優先度でリダイレクト処理を追加（template_redirectフックを使用）
add_action('template_redirect', 'pgv_check_and_redirect', 1);
