【環境構築】
1 docker-compose.ymlファイルがあるディレクトリで下記のコマンドを実行
    `$ docker-compose up -d` 
2 下記ポート指定でアクセスできる
<http://127.0.0.1:8080> 
<http://127.0.0.1:8888>(phpmyadmin)
3 `$ docker-compose exec php php artisan migrate` で`test_database`にユーザテーブルを作成