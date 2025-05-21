# coachtech フリマアプリ

## 環境構築

### Dicker ビルド

1. clone

```
git clone git@github.com:Mai1933/CTfleamarket.git
```

2. docker 立ち上げ

```
docker-compose up -d --build
```

3. ＊MySQL は、OS によって起動しない場合があるので,それぞれの PC に合わせて docker-compose.yml ファイルを編集してください。

### Laravel 環境構築

1. `docker-compose exec php bash`
2. `composer install`
3. .env.example ファイルから.env を作成し、環境変数を変更  
   (1)DB_PORT から DB_PASSWORD までのコメントアウトを解除  
   (2)以下を該当箇所へコピペ

   ```
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
   ```

4. `php artisan key:generate`
5. `php artisan migrate`
6. `php artisan db:seed`
7. `php artisan storage:link`

### テスト環境構築

1.  `composer require phpunit/phpunit --dev`
2.  `php artisan key:generate --env=testing`

## ダミーデータ

1. name:１から５出品者 email:1to5@email.com pass:password (青のアイコン)
2. name:６から１０出品者 email:6to10@email.com pass:password (赤のアイコン)
3. name:紐づけなし email:noitem@email.com pass:password (灰色のアイコン)

## 使用技術

・PHP 8.3.13  
・Laravel 11.34.2  
・MySQL 8.0.40

## ER 図(表示されない場合は再読み込みしてください）

# before(模擬案件)

![Image](https://github.com/user-attachments/assets/834a0450-e336-483e-a78e-0e995a0ae82b)

# after(Pro 入会テスト)

![Image](https://github.com/user-attachments/assets/9dd39845-cfd8-4e56-8f77-546daf9c7b53)

## URL

開発環境：http://localhost/  
phpMyAdmin:http://localhost:8080/  
mailhog:http://localhost:8025/

## 注意

・拡張機能で ChatGPT のサイドバーをオンにしている場合、画面下部のボタンが押せない事象が発生することがあります。一時的に拡張機能をアンインストールすると解消します。

・画像は src/storage/app/public/及び src/storage/app/public/user_image/に保存しています。

・左上のロゴはトップページにリンクしています。

・いいねした後、戻るボタンからトップページ → マイリストへと遷移すると反映されません。再読み込みするか、ロゴからトップページにお飛びください。

・テストに関して、RegisterTest,LoginTest,ProductTest の 3 つのファイルに記述しています。  
//【項目】  
//テスト内容１  
//テスト内容２  
テスト内容１及び２に関するメソッド  
の順で表記しています。

# 追記

・最初の取引メッセージのやり取りに関して、商品詳細ページの「取引メッセージを送る」ボタンから該当商品の取引メッセージ画面へ遷移できます。

・取引メッセージの編集に関して、「編集」ボタンを押す → 編集したいメッセージを当該メッセージ欄にて編集する →「編集」ボタンが「決定」ボタンに変化しているので、「決定」ボタンを押す　という手順で実装しています。

・購入後でないとユーザーの評価ができないようにしています。一度取引メッセージ画面の商品画像をクリックし、購入手続きを行ったうえで評価を実施してください。
