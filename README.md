# TODOアプリ

PHP/MySQLを使用したシンプルなTODOアプリケーションです。

## 機能

- TODOの追加
- TODOの削除
- TODOの完了/未完了の切り替え
- リアルタイムでのデータ更新

## 必要な環境

- PHP 7.4以上
- MySQL 5.7以上
- Webサーバー（Apache、Nginxなど）

## セットアップ手順

### 1. データベースの作成

MySQLにログインして、以下のコマンドでデータベースとテーブルを作成します：

```bash
mysql -u root -p < schema.sql
```

または、MySQLコマンドラインで直接実行：

```bash
mysql -u root -p
```

```sql
source /var/www/html/todo/schema.sql
```

### 2. データベース接続設定

`config.php` ファイルで、データベース接続情報を設定します。

デフォルトの設定：
- ホスト: localhost
- データベース名: todo_app
- ユーザー名: root
- パスワード: （空）

環境変数で設定する場合：

```bash
export DB_HOST=localhost
export DB_NAME=todo_app
export DB_USER=root
export DB_PASS=your_password
```

### 3. Webサーバーの設定

#### Apache の場合

ドキュメントルートを `/var/www/html/todo` に設定するか、既存の設定で `http://localhost/todo/` でアクセスできるようにします。

#### PHP組み込みサーバーの場合（開発用）

```bash
cd /var/www/html/todo
php -S localhost:8000
```

ブラウザで `http://localhost:8000` にアクセスします。

### 4. アクセス

Webブラウザで以下のURLにアクセスします：

- Apache: `http://localhost/todo/`
- PHP組み込みサーバー: `http://localhost:8000/`

## ファイル構成

```
todo/
├── index.html      # フロントエンド（UI）
├── api.php         # バックエンドAPI
├── config.php      # データベース接続設定
├── schema.sql      # データベーススキーマ
└── README.md       # このファイル
```

## API エンドポイント

### GET /api.php
すべてのTODOを取得

### POST /api.php
新しいTODOを作成
```json
{
  "title": "TODOのタイトル"
}
```

### PUT /api.php
TODOの完了状態を更新
```json
{
  "id": 1,
  "completed": true
}
```

### DELETE /api.php
TODOを削除
```json
{
  "id": 1
}
```

## トラブルシューティング

### データベース接続エラー

- MySQLが起動しているか確認
- `config.php` の接続情報が正しいか確認
- データベースとテーブルが作成されているか確認

### パーミッションエラー

ファイルとディレクトリのパーミッションを確認：

```bash
sudo chown -R www-data:www-data /var/www/html/todo
sudo chmod -R 755 /var/www/html/todo
```

（Apacheユーザーは環境によって `apache` や `www-data` などに変わります）

## ライセンス

MIT License
