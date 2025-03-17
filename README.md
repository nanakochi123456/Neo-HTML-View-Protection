# Free Wordpress HTML Protector

## 特徴
100% ChatGPT Made Code

HTMLソースがわずかこれだけになる

 <script> document.cookie="encoded_url=Lw==;max-age=10;path=/"; var encodedUrl="Lw==",decodedUrl=atob(encodedUrl); window.location.href=decodedUrl; </script>

## インストール
WPROOT/wp-content/plugins/Neo-HTML-View-Protection ディレクトリを作成し
その中にsrc配下のファイルをすべて入れて有効化

## アンインストール
- 無効化して削除

## 仕組み
- HTTPアクセス
- JavaScriptで現在のURLをBase64にして10秒有効のcookieに保存してLocation
- cookieを削除して本来のコンテンツを表示

## 注意
リダイレクトが発生しますので、SEOを狙うコンテンツには向きません

## バージョン履歴
v0.1 - 初版
