# アプリケーション名
  FreeMarket

## 環境構築
Dockerビルド
・ git clone <git@github.com:Zoe0327/FreeMarket.git>
・ docker-compose up -d --build
*MySQLは、OSによって起動しない場合があるのでそれぞれのPCに併せてdocker-compose.ymlファイルを編集してください。

Laravel環境構築
1. docker-compose exec php bash
2. composer install
3. .env.exampleを.env にリネーム、または新しく.env作成
4. .envに以下の環境変数を追加 --text
DB_CONNECTION=mysql DB_HOST=mysql DB_PORT=3306 DB_DATABASE=laravel_db DB_USERNAME=laravel_user DB_PASSWORD=laravel_pass
※ DB_HOST は Docker Compose の MySQL サービス名に合わせて設定してください。 デフォルトでは mysql ですが、環境によって自動生成されるコンテナ名になることがあります。
5. アプリケーションキーの作成 php artisan key:generate
6. マイグレーションの実行 php artisan migrate
7. シーディングの実行 php artisan db:seed
8. ストレージングの作成php artisan storage:link

## 使用技術（実行環境）
・ Laravel 8.83
・ PHP 8.1
・ MySql 8.0.26
・ Nginx 1.21.1
・ Docker 28.11
・ Composer v2.35.1


## ER図
（freemarket.png）
*ER図ファイルはプロジェクト直下に'freemarket.png'として配置してください。

## URL
・商品一覧画面：http://localhost/
・ユーザー登録：http://localhost/register/
・phpMyAdmin:http://localhost:8080
