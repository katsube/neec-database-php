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

デフォルトで指定されているキーワードは以下の20個です。
```php
$words = [
	'アイドルマスター',
	'陰陽師',
	'ガンダム',
	'歌ってみた',
	'踊ってみた',
	'演奏してみた',
	'初音ミク',
	'ポケモン',
	'東方',
	'霊夢',
	'魔理沙',
	'ひぐらし',
	'田村ゆかり',
	'ファミコン',
	'マリオ',
	'ゼルダ',
	'風来のシレン',
	'イース',
	'三国志',
	'エースコンバット'
];
```

実行方法は以下の通り。
```shellsession
$ cd step2
$ php createIndex.php
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

### STEP3. データの挿入/更新/削除
STEP2までのインデックスはいったん忘れ、データの更新をどのように行うか試します。

データの挿入は単純にファイルの末尾へ記録します。
```shellsession
$ php insert.php
```

現状のファイルフォーマットではファイルの途中にある情報を書き換えることが難しいため、一時ファイルに全データを書き写す過程でデータの更新や削除を行う形を取っています。非常に効率が悪いため、小規模なサービスでデータ件数が100件程度であれば問題なく動作しますが、大規模なサービスの場合は破綻する可能性が高いと言えます。
```shellsession
$ php update1.php
$ php delete1.php
```

### STEP4. データファイルを固定長化
「data/video.csv」を固定長に変換します。変換後のファイルは「data/video_fixed.csv」へ保存されます。
```shellsession
$ php convert.php
```

各項目のサイズは以下の通り。不足しているスペースは半角スペースで埋められます。

1. 状態フラグ（1byte）
    * 1:有効、0:削除済み
1. ID（10byte）
1. タイトル（128byte）
1. 動画フォーマット（5byte）
1. 動画の長さ（5byte）

今回は`fscanf()`でファイルからデータを読み取っています。
```shellsession
$ php search.php
```

insertはSTEP3とそれほど変わりませんが、updateは固定長であるメリットを活かし既存のレコードを直接書き換えています。deleteはフラグを下ろすだけ。
```shellsession
$ php insert2.php
$ php update2.php
$ php delete2.php
```


### STEP.n デーモン化
**※STEPnの実行には、STEP2で作成したインデックスファイル(data/index.txt)が必要になります。**

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

STEPn
```shellsession
$ time php client.php
real	0m0.178s
user	0m0.020s
sys	0m0.055s
```

## Licence
The MIT License