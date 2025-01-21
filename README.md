# coachtech フリマアプリ

## 環境構築

### Dicker ビルド

1. git clone リンク
2. docker-compose up -d --build
3. ＊MySQL は、OS によって起動しない場合があるので,それぞれの PC に合わせて docker-compose.yml ファイルを編集してください。

### Laravel 環境構築

1. docker-compose exec php bash
2. composer install
3. .env.example ファイルから.env を作成し、環境変数を変更  
   (1)DB_PORT から DB_PASSWORD までのコメントアウトを解除  
   (2)以下を該当箇所へコピペ  
   APP_LOCALE=ja  
   APP_FALLBACK_LOCALE=ja  
   APP_FAKER_LOCALE=ja_JP

MAIL_MAILER=smtp  
 MAIL_HOST=mailhog  
 MAIL_PORT=1025  
 MAIL_USERNAME=test  
 MAIL_PASSWORD=pass  
 MAIL_ENCRYPTION=smtp  
 MAIL_FROM_ADDRESS="test@test"  
 MAIL_FROM_NAME="${APP_NAME}"  
4. php artisan key:generate  
5. php artisan migrate  
6. php artisan db:seed  
7. php artisan storage:link

## 使用技術

・PHP 8.3.13  
・Laravel 11.34.2  
・MySQL 8.0.40

## ER 図(表示されない場合は再読み込みしてください）

![ER drawio](https://github.com/user-attachments/assets/6e766371-da2c-4eac-8e34-ae9c914d20ee)

## URL

開発環境：http://localhost/
phpMyAdmin:http://localhost:8080/
mailhog:http://localhost:8025/

## 注意

・拡張機能で ChatGPT のサイドバーをオンにしている場合、画面下部のボタンが押せない事象が発生することがあります。一時的に拡張機能をアンインストールすると解消します。

・画像は src/storage/app/public/及び src/storage/app/public/user_image/に保存しています。

・左上のロゴはトップページにリンクしています。

・いいねした後、戻るボタンからトップページ → マイリストへと遷移すると反映されません。再読み込みするか、ロゴからトップページにお飛びください。
