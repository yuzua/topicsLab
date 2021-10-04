# topicsLab
### 作業進捗
* 月曜日分
```
1　　齋藤 123
2	中野さん ◯
3,5	 山口さん ◎
4,6,7 岩井さん◯
```
### 環境設定コマンド
```
$cd <任意の場所>
$git clone 
$docker-compose up --build -d
$docker-compose exec api /bin/bash
    $php artisan db:seed DatabaseSeeder
    $php artisan serve --host=0.0.0.0 --port=8000
    $exit
$docker-compose exec front /bin/bash
    $yarn serve
    $control+c
```
### 開発開始コマンド
```
$git pull remote feature
$docker-compose up
$docker-compose exec front /bin/bash
    $yarn serve
    $control+c
```
### 開発終了コマンド
```
$exit
$docker-compose stop
$git add .
$git commit -m "<任意のコメント>"
$git push origin feature
```