<?php
/**
 * Plugin Name: Neo HTML View Protection
 * Description: view-source: でもHTMLをわずか数文字にする
 * Version: 0.2
 * Author: Nano Yozakura
 * License: GPL2
 */

// 10秒のクッキーを発行してエンコードされたURLを保存
function pgv_set_cookie_and_redirect() {
    // 現在のURLを取得
    $current_url = $_SERVER['REQUEST_URI'];

    // URLをBase64エンコード
    $neo_encoded_url = base64_encode($current_url);

    // titleを取得
    $title = wp_title('|', false, 'right') . get_bloginfo('name');

    // 言語を取得
    $lang = get_bloginfo('language');

    // URLを取得
    $scheme = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $request_uri = $_SERVER['REQUEST_URI'];
    $current_url = $scheme . '://' . $host . $request_uri;

    // JavaScriptでエンコードされたURLをデコードしてリダイレクトするスクリプトを挿入
    $html = '<!doctype html><html lang="' . $lang . '"><head><meta charset="UTF-8">';
    $html .= '<script>var neUrl="' . $neo_encoded_url . '";document.cookie="neo_encoded_url="+neUrl+";max-age=10;path=/";window.location.href=atob(neUrl);</script>';
    $html .= '<title>' . $title . '</title>';

    $meta_description = get_post_meta(get_the_ID(), 'meta_description', true);
    $meta_keywords = get_post_meta(get_the_ID(), 'meta_keywords', true);
    if($meta_description !== "") {
        $html .= '<meta name="description" content="' . $meta_description . '">';
    }
    if($meta_keywords !== "") {
        $html .= '<meta name="keywords" content="' . $meta_keywords . '">';
    }
    $image_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
    if($image_url) {
        $html .= '<meta property="og:image" content="' . $image_url . '">';
    }

    $html .= '<meta property="og:type" content="website">';
    $html .= '<meta property="og:description" content="' . $meta_description . '">';
    $html .= '<meta property="og:title" content="' . $title . '">';
    $html .= '<meta property="og:url" content="' . $current_url . '">';
    $html .= '<meta property="og:site_name" content="' . get_bloginfo('name') . '">';

    $feedbase=$scheme . '://' . $host;
$html .= '<link rel="alternate" type="application/rss+xml" title="' . get_bloginfo('name') . ' &raquo; feed" href="' . $feedbase . '/feed/">';
$html .= '<link rel="alternate" type="application/rss+xml" title="' . get_bloginfo('name') . ' &raquo; comment feed" href="' . $feedbase . '/comments/feed/">';	

    $html .= '</head></html>';

    echo $html;
    exit;
}

// クッキーからエンコードされたURLを取得し、リダイレクトする
function pgv_redirect_from_cookie() {
    if (isset($_COOKIE['neo_encoded_url'])) {
        // クッキーにエンコードされたURLがある場合、デコードしてリダイレクト
        $neo_encoded_url = $_COOKIE['neo_encoded_url'];
        $decoded_url = base64_decode($neo_encoded_url);

        setcookie("neo_encoded_url", "", time() - 3600, "/"); 

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
        // RSSでないこと
        if(strpos($_SERVER['REQUEST_URI'], '/feed/') === false) {
            // クッキーがセットされている場合、リダイレクト処理を実行
            if (isset($_COOKIE['neo_encoded_url'])) {
                pgv_redirect_from_cookie();
            } else {
                // クッキーがない場合、10秒のクッキーを発行してリダイレクト
                pgv_set_cookie_and_redirect();
            }
        }
    }
}

// 高い優先度でリダイレクト処理を追加（template_redirectフックを使用）
add_action('template_redirect', 'pgv_check_and_redirect', 1);
