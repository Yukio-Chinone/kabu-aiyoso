# docker compose を利用したローカル環境構築


## 2container.yml の内容

#### ＜概要＞
以下の２つのコンテナを作成
- Nginx + php-fpm7.0 on AmazonLinux1
  - Zephir0.9.4
  - Phalcon3
  - Phalcon Devtools (初期無し。必要になったら追加)
- mysql5.7

#### ＜ブラウザ＞
- develop [http://localhost:8880](http://localhost:8880)
- tutorial [http://localhost:8881](http://localhost:8881)

#### ＜Command＞
```
Dockerディレクトリに移動
$ cd ~/mylocalhost/finance.web/docker

ビルド
$ docker-compose -f 2containers.yml build

イメージから起動
$ docker-compose -f 2containers.yml up -d

コンテナの開始
$ docker-compose -f 2containers.yml start

コンテナの終了
$ docker-compose -f 2containers.yml stop

コンテナ(finance_web)にログイン
$ docker exec -i -t -u develop finance_web bash

コンテナ(finance_db)にログイン
$ docker exec -i -t -u root finance_db bash

mysql にテストデータの流し込み
$ cd /var/www/develop
$ mysql -h finance_db -u root -p # q8b5v3dh
  mysql> source finance.sql
  
mysql の設定
$ vi /etc/mysql/conf.d/develop.cnf

mysql ログ
$ tail -f /var/log/mysql/error.log
$ tail -f /var/log/mysql/slow.log
$ tail -f /var/log/mysql/general.log

コンテナとイメージを全て削除
$ docker-compose -f 2containers.yml down --rmi all
```

### AIの株価予想: https://kabu.aiyoso.com

---
### AWS / Google / Twitter / JRA-VAN

|見出し|内容|
|:---|:---|
|**Name**| 分析開発 |
|**ID**|racing55dev@gmail.com|
|**PW**|S9vqBcxa|

---
### Facebook 開発者アカウント
|見出し|内容|
|:---|:---|
|**URL**|https://developers.facebook.com|
|**ID**|fbdevchino@gmail.com|
|**PW**|ychino55|

---
### facebook for developers
|見出し|内容|
|:---|:---|
|**参考(公式)**|https://developers.facebook.com/docs/php/howto/example_facebook_login|
|**参考(公式)**|https://developers.facebook.com/docs/php/howto/example_retrieve_user_profile|

#### facebook for developers での設定内容
##### 1. プロダクト > Facebookログイン > 設定
- クライアントOAuthログイン（はい）
- ウェブOAuthログイン（はい）
- ウェブOAuth再認証を強制（いいえ）
- リダイレクトURIに制限モードを使用（はい）
- 有効なOAuthリダイレクトURI（https://kabu.aiyoso.com/user/callback/facebook）　※コールバックURL

##### 2. 設定 > ベーシック
- アプリID（自動）
- app secret（自動）
- 表示名（任意）
- アプリドメイン（kabu.aiyoso.com）
- 連絡先メールアドレス（任意）
- カテゴリ（ビジネスページ）
- ビジネス目的で使用（自分のビジネスをサポート）

---
### facebook アプリ連携解除 (facebookの”一般ページ”ログイン後)
##### 1. 画面右上の▼ > 設定
- アプリとウェブサイト
- アクティブなアプリとウェブサイト に にチェックつけて「削除」
>【重要】
> ホーム > 画面右下の「アプリを管理」だと、facebook for developers に行くので注意（ここだとアプリ自体を削除してしまう）