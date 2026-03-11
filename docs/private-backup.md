# Private Backup Package

GitHub repo này không chứa dữ liệu nhạy cảm, database dump hoặc media uploads. Để dựng site local đầy đủ nội dung, cần một gói private backup phát hành ngoài GitHub.

## Cấu trúc gói

Thư mục `private-backups/` trong repo chỉ là placeholder. Khi phát hành nội bộ, dùng đúng 2 file chính:

- `database.sql.gz`
- `uploads.tar.gz`

Tùy chọn:

- `SHA256SUMS`

## Cách tạo gói

Chạy script tại root WordPress:

```bash
DB_HOST=localhost \
DB_PORT=3306 \
DB_USER=root \
DB_PASSWORD=root \
SOURCE_DB_NAME=local \
SANITIZED_DB_NAME=local_sanitized \
./scripts/sanitize-db.sh
```

Script sẽ:

1. Clone database nguồn sang database tạm đã sanitize
2. Xóa transient, session và một số option/update token phổ biến
3. Xóa các option chứa pattern secret như `license`, `token`, `secret`, `smtp`, `api_key`
4. Export thành `private-backups/database.sql.gz`
5. Đóng gói `wp-content/uploads/` thành `private-backups/uploads.tar.gz`

## Lưu ý vận hành

- Chỉ chia sẻ gói này qua kênh private như Google Drive, S3 private, hoặc private release
- Không commit `private-backups/` vào Git
- Nếu cần đưa ví dụ lên repo public, chỉ dùng file tên mẫu hoặc dữ liệu giả, không dùng backup thật
- Sau khi tạo gói, nên rà lại nhanh:

```bash
gzip -dc private-backups/database.sql.gz | rg -n -i "license|token|secret|smtp|api[_ -]?key"
```

Nếu còn giá trị nhạy cảm của plugin riêng, bổ sung thêm option name cần xóa trong `scripts/sanitize-db.sh`
