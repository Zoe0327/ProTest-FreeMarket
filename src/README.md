# アプリケーション名
  FreeMarket

## 環境構築
Dockerビルド
・ git clone <git@github.com:Zoe0327/FreeMarket.git>
・ docker-compose up -d --build

Laravel環境構築
・ docker-compose exec php bash
・ composer install
・ cp .env.example .env、　環境変数を変更
・ php artisan key:generate
・ php artisan migrate
・ php artisan db:seed
・ php artisan storage:link

##  開発環境
・商品一覧画面：http://localhost/
・ユーザー登録：http://localhost/register/
・phpMyAdmin:http://localhost:8080

## 使用技術（実行環境）
・ Laravel 8.83
・ PHP 8.1
・ MySql 8.0.26
・ Nginx 1.21.1
・ Docker 28.11
・ Composer v2.35.1


## ER図
coachtech/FreeMarket/freemarket.png