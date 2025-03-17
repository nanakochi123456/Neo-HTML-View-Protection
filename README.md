# Free Wordpress HTML Protector

## 特徴
100% ChatGPT Made Code

HTMLソースがわずかこれだけになる

![sample image](img/neo_htmlprotect.png)

## インストール
WPROOT/wp-content/plugins/Neo-HTML-View-Protection ディレクトリを作成し
その中にsrc配下のファイルをすべて入れて有効化

## アンインストール
- 無効化して削除

## 仕組み
- HTTPアクセス
- JavaScriptで現在のURLをBase64にして10秒有効のcookieに保存してLocation
- cookieを削除して本来のコンテンツを表示

## 効能
- Wordpressであることがばれにくい (wp-contentとかでばれるかもしれない）
-- 注：/wp-login.php とか /wp-admin/ を外部からdenyすれば更に効果が高い
- HTMLが見れないから何のプラグインを使用してるか判別しずらい

## 注意
- 【重要】キャッシュ系プラグインが動作していると動きません
- リダイレクトがアクセス毎に発生しますので、SEOを狙うコンテンツには向きません
- OGPとかfetchによる画像取得ができない可能性があります
- ログインしていると普通のHTMLが見れます
- 専用のHTMLソースダウンローダーまでは対応していません

## サンプルサイト

https://blog.773.moe/

Control+Uとかでソースを見ようとすると別のプラグインが反応します

https://github.com/nanakochi123456/Neo-Copykey-Alert

view-source: で確認してみてください

## バージョン履歴
v0.2 - 最小限のSEOコード、最小限のOGPコードのみ記載、/feed/ までブロックするのを修正

v0.11 - JavaScriptコードを短縮化

v0.1 - 初版
