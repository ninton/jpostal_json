# jpostal_json

jquery.jpostalのjsonpファイル作成スクリプト

Copyright 2018-, Aoki Makoto, Ninton G.K. http://www.ninton.co.jp
Released under the MIT license - http://en.wikipedia.org/wiki/MIT_License

Requirements
PHP/7
composer/1.7.2

## 初期設定

git clone後、composer install を実行してください。

```
$ composer install
```

config.sample.sh を config.sh にコピーしてください。
config.shを編集して、jquery.jpostal.jsへのパスを設定してください。

```
# config.shで設定する環境変数

# version.txtのパス
export JPOSTAL_VERSION_TXT=/tmp/jquery.jpostal.js/version.txt

# 000.json〜999.jsonを作成保存するディレクトイ
export JPOSTAL_JSON_DIR=/tmp/jquery.jpostal.js/json
```

編集せず、そのまま使う場合は、次のディレクトリを作成してください。

```
$ mkdir /tmp/jquery.jpostal.js
$ mkdir /tmp/jquery.jpostal.js/json
```

## 使い方

ターミナルで、main.shを実行してください。
日本郵便サイトから郵便番号データをダウンロードし、
jquery.jpostal.js用に加工したjsonpファイルを作成します。

```
$ ./main.sh
```
