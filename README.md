# neec-database-php
日本工学院八王子専門学校 ゲームクリエイター科の講義で利用するPHPを利用した簡易データベースです。

## 準備
PHPが実行できる環境でこのリポジトリを`git clone`もしくはダウンロードしてください。
```shellsession
$ php -v
PHP 8.1.6 (cli) (built: May 12 2022 23:43:09) (NTS)

$ git clone https://github.com/katsube/neec-database-php.git
```

dataディレクトリ内にあるvideo.csv.gzを解凍します。video.csvが作成されれば成功です。
```shellsession
$ gzip -d data/video.csv.gz
```

## 実行する
### STEP.1 全文検索
video.csvをファイルの先頭から最後まで何も考えずに検索するパターンです。
```shellsession
$ cd step1
$ php searchNormal.php
```

timeコマンドを利用して実行時間も計測してみましょう。
```shellsession
$ time php searchNormal.php
```

### STEP.2 インデックス
#### インデックスの作成
簡易的なインデックスを作成した上で検索を行います。インデックスを作成するキーワードはcreateIndex.php内で指定する必要があります。
```shellsession
$ cd step2
$ php createIndex.php
```

デフォルトで指定されているキーワードは以下の3つです。
```php
$words = [
	'アイドルマスター',
	'陰陽師',
	'孤独のグルメ'
];
```

#### インデックスを利用した検索
searchIndex.phpはコマンドラインから検索ワードを指定できます。インデックスが有効なものとそうでない物でどのように動きが異なるか確認します。
```shellsession
$ php searchIndex.php (検索キーワード)
```

これもtimeコマンドを利用して処理時間を計測しましょう。
```shellsession
$ time php searchIndex.php (検索キーワード)
```

### STEP.3 デーモン化
**※STEP3の実行には、STEP2で作成したインデックスファイル(data/index.txt)が必要になります。**

常時起動した状態になるとどの程度高速化するかも試してみましょう。このステップではサーバを起動した状態でクライアントが通信を行い検索結果を取得します。

まずはサーバを起動します。
```shellsession
$ cd step3
$ php serve.php
```

次に新しくTerminalを立ち上げクライアントからサーバへ通信します。client.phpもコマンドラインからキーワードの指定が可能です。
```shellsession
$ cd step3
$ php client.php (検索キーワード)
```

timeコマンドを利用して処理時間を計測しましょう。
```shellsession
$ time php client.php (検索キーワード)
```

## 実行結果のサンプル
STEP1
```shellsession
$ time php searchNormal.php
real	0m0.268s
user	0m0.101s
sys	0m0.078s
```

STEP2
```shellsession
$ time php searchIndex.php
real	0m0.225s
user	0m0.022s
sys	0m0.061s
```

STEP3
```shellsession
$ time php client.php
real	0m0.178s
user	0m0.020s
sys	0m0.055s
```

## Licence
The MIT License