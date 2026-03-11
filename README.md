# Aerem WordPress Public

Mã nguồn WordPress của `aerem.com.vn` dành cho local clone. Repo này chỉ chứa source code có thể chia sẻ qua GitHub, không chứa secret, license key, database dump hay media uploads.

## Repo chứa gì

- Core WordPress trong root
- Plugin và theme cần để site chạy local
- Theme tùy biến tại `wp-content/themes/bricks-child`
- MU plugin local tại `wp-content/mu-plugins/local-disable-remote-updates.php` để chặn update check và remote activation khi chạy local
- Script hỗ trợ tại `scripts/bootstrap-local.sh` và `scripts/sanitize-db.sh`

## Repo không chứa gì

- `wp-config.php`
- `.env`
- `wp-content/uploads/`
- cache, backup runtime, file log
- `database.sql.gz`, `uploads.tar.gz`
- bất kỳ API key, SMTP credential, webhook secret hoặc license key production

## Clone và chạy local bằng Local

### 1. Clone repo

```bash
git clone git@github.com:congminh12000/aerem.git
cd aerem
```

### 2. Tạo cấu hình local

```bash
cp wp-config.example.php wp-config.php
```

Mặc định file mẫu đã dùng:

- `DB_HOST=localhost`
- `DB_NAME=local`
- `DB_USER=root`
- `DB_PASSWORD=root`
- `WP_HOME=http://aerem.local`
- `WP_SITEURL=http://aerem.local`
- `WP_ENVIRONMENT_TYPE=local`

Nếu máy bạn dùng giá trị khác, chỉnh trực tiếp trong `wp-config.php` hoặc set biến môi trường trước khi chạy PHP.

### 3. Chuẩn bị private backup package

Bạn cần lấy riêng 2 file sau từ kênh private:

- `private-backups/database.sql.gz`
- `private-backups/uploads.tar.gz`

Trong Git chỉ có placeholder tại `private-backups/README.example.md`. Không public file backup thật lên repo.

Chi tiết cách đóng gói nằm trong [docs/private-backup.md](/Users/minhphan/Local%20Sites/aeren/app/public/docs/private-backup.md).

### 4. Tạo site trong Local

Tạo một site WordPress custom trong Local với document root trỏ vào thư mục repo này. Có thể dùng:

- site domain: `aerem.local`
- database: `local`
- username: `root`
- password: `root`

### 5. Bootstrap dữ liệu và admin local

Đặt private backup package lấy từ kênh private vào `private-backups/`, sau đó chạy:

```bash
chmod +x scripts/bootstrap-local.sh
./scripts/bootstrap-local.sh
```

Script sẽ:

1. tạo database nếu chưa có
2. import `database.sql.gz`
3. giải nén `uploads.tar.gz`
4. replace domain từ `https://aerem.com.vn` sang `http://aerem.local`
5. tạo tài khoản admin local mới
6. flush rewrite rules

Tài khoản mặc định sau bootstrap:

- username: `localadmin`
- email: `localadmin@example.test`
- password: `LocalAdmin#ChangeMe123!`

Quan trọng:

- Không dùng lại tài khoản admin production
- Script luôn tạo hoặc cập nhật một admin local riêng để đăng nhập trên máy dev

### 6. Mở site

- Frontend: `http://aerem.local`
- Admin: `http://aerem.local/wp-admin`

## Chạy local không dùng Local

Nếu không dùng Local, bạn tự chuẩn bị:

1. PHP tương thích với WordPress hiện tại
2. MySQL/MariaDB
3. web server trỏ document root vào thư mục này
4. `wp-config.php` tạo từ `wp-config.example.php`
5. private backup package

Sau đó chạy:

```bash
DB_HOST=localhost \
DB_PORT=3306 \
DB_NAME=local \
DB_USER=root \
DB_PASSWORD=root \
LOCAL_URL=http://aerem.local \
PRODUCTION_URL=https://aerem.com.vn \
./scripts/bootstrap-local.sh
```

## Tạo private backup package mới

Để phát hành bản clone mới mà không lộ secret:

```bash
chmod +x scripts/sanitize-db.sh
DB_HOST=localhost \
DB_PORT=3306 \
DB_USER=root \
DB_PASSWORD=root \
SOURCE_DB_NAME=local \
SANITIZED_DB_NAME=local_sanitized \
./scripts/sanitize-db.sh
```

Script sẽ tạo file thật trong thư mục đang bị ignore:

- `private-backups/database.sql.gz`
- `private-backups/uploads.tar.gz`
- `private-backups/SHA256SUMS` nếu máy có `shasum`

Sau khi chạy, nên kiểm tra nhanh dump:

```bash
gzip -dc private-backups/database.sql.gz | rg -n -i "license|token|secret|smtp|api[_ -]?key"
```

## Điểm cần chú ý khi chỉnh sửa

- Ưu tiên chỉnh trong `wp-content/themes/bricks-child`
- File custom chính hiện nằm ở `wp-content/themes/bricks-child/functions.php`
- Custom Bricks element hiện được đăng ký từ `wp-content/themes/bricks-child/elements/title.php`
- Không commit `wp-config.php`, `.env`, private backup thật, media uploads hoặc dữ liệu runtime
- Nếu cần minh họa trong repo public, chỉ để placeholder hoặc data mẫu đã vô hiệu hóa mọi thông tin nhạy cảm
