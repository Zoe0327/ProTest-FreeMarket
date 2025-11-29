# アプリケーション名
  FreeMarket

## 環境構築
Dockerビルド
・ git clone git@github.com:Zoe0327/FreeMarket.git
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
8. ストレージングの作成 php artisan storage:link

## 使用技術（実行環境）
・ Laravel 8.83
・ PHP 8.1
・ MySql 8.0.26
・ Nginx 1.21.1
・ Docker 28.11
・ Composer v2.35.1

## 使用技術（外部サービス）
・ Mailtrap（メール送信テスト用）
・ Stripe（クレジットカード決済 & Webhook）

## ER図
<img width="1391" height="1091" alt="freemarket" src="https://github.com/user-attachments/assets/976f1e8e-9c4e-4b40-a790-fd28c5533206" />

## URL
・商品一覧画面：http://localhost/
・ユーザー登録：http://localhost/register/
・phpMyAdmin：http://localhost:8080
・Mailtrap：https://mailtrap.io/
・Stripe：https://stripe.com/

## 追加事項
・購入状態の判定は sold_items テーブルで行い、該当商品が存在する場合に「SOLD」を表示。
・ログイン中のユーザーが自分の出品した商品を開いた場合、購入ボタンが表示されないように設定。
　（自分の商品を自分で購入できないため）

## Mailtrap（メール送信テスト用）
・メール認証の際はMailtrapにログインした状態で会員登録を行いメールの認証を実行する。
・.envのMAILの修正
　MAIL_HOST=sandbox.smtp.mailtrap.io
　MAIL_PORT=2525
　MAIL_USERNAME=xxx（自身のアカウントを参照）
　MAIL_PASSWORD=xxx（自身のアカウントを参照）
　MAIL_ENCRYPTION=tls
　MAIL_FROM_ADDRESS=noreply@example.com
　MAIL_FROM_NAME="${APP_NAME}"

## Stripe テスト購入（ローカルで SoldItem 確認）
1. .env に Stripe テストキーを追加
　（各自の Stripe アカウントで取得してください）
STRIPE_KEY=pk_test_xxx
STRIPE_SECRET=sk_test_xxx
STRIPE_WEBHOOK_SECRET=whsec_xxx
2. Stripe CLI で Webhook をローカルに転送
　ターミナルで下記のstripe listenを実行してからカード決済ボタンをクリックする。
"stripe listen --forward-to http://localhost/stripe/webhook"
3. ブラウザで商品購入 → Stripe Checkout でテスト決済
・テストカード番号：4242 4242 4242 4242
・有効期限：任意未来日
・CVC：任意
4. Stripe CLI のターミナルでイベントが転送されることを確認
5. Laravel DB に SoldItem が作成されていることを確認
 php artisan tinker
>>> \App\Models\SoldItem::all();
6. 商品一覧ページで「SOLD」と表示されることを確認
 ※注意
 ・WSL2 + Docker 環境では php artisan serve は使わず、Nginx を通した localhost URL を使用
 ・本番環境では Webhook は Stripe ダッシュボードに設定した公開 URL に届くため、ここでの手順は 開発環境用テスト手順 とする

 ## PHPunitテスト
1. テスト用データベースを作成 docker-compose exec mysql mysql -u root -pにログイン後CREATE DATABASE demo_test;を実行
2. PHPUnitでテストを実行 docker-compose exec php php artisan test


