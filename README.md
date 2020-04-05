# jpostal_json

jquery.jpostalのjsonpファイル作成スクリプト

Copyright 2018-, Aoki Makoto, Ninton G.K. http://www.ninton.co.jp
Released under the MIT license - http://en.wikipedia.org/wiki/MIT_License

Requirements
PHP/7
composer/1.7.2

## 使い方

git clone後、./main.sh を実行してください。

```
$ ./main.sh
```

自動で 
日本郵便サイトから郵便番号データをダウンロードし、
./jquery.jpostal.js/ に git cloneし、
./jquery.jpostal.js/json/*.json をアップデートします。
