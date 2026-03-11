# Aerem WordPress Public

Mã nguồn public của website `aerem.com.vn`, dùng WordPress và được chạy local bằng Local (Nginx + PHP-FPM + MySQL).

## Phạm vi repo

Repo này chỉ chứa phần thư mục `public` của site WordPress:

- Core WordPress trong root của thư mục này
- Toàn bộ plugin và theme cần thiết để chạy site
- Theme đang tùy biến tại `wp-content/themes/bricks-child`
- Cấu hình local nằm ở `wp-config.php` và đang được ignore bởi `.gitignore`

Không đưa vào Git:

- `wp-content/uploads/`
- cache, backup, file log runtime
- dữ liệu database export

## Cấu trúc quan trọng

```text
public/
├── index.php
├── wp-config.php
├── .gitignore
└── wp-content/
    ├── plugins/
    └── themes/
        ├── bricks/
        └── bricks-child/
```

## Phần cần chú ý khi chỉnh sửa

- Theme đang chạy là `bricks-child`
- File custom chính nằm ở `wp-content/themes/bricks-child/functions.php`
- Custom element Bricks hiện được đăng ký từ `wp-content/themes/bricks-child/elements/title.php`
- Một số dữ liệu media, sitemap và cache không nằm trong repo nên khi dựng máy mới cần đồng bộ thêm database và uploads

## Chạy local

Project này được export từ Local, nên cách nhanh nhất để chạy là import thư mục này vào Local và để Local tự tạo lại web server, PHP và MySQL.

Giá trị hiện có trong `wp-config.php`:

- `DB_NAME=local`
- `DB_USER=root`
- `DB_PASSWORD=root`
- `DB_HOST=localhost`
- `WP_ENVIRONMENT_TYPE=local`

Nếu không dùng Local, cần tự chuẩn bị:

1. PHP tương thích với WordPress hiện tại
2. MySQL/MariaDB
3. Web server trỏ document root vào thư mục này
4. Database dump và thư mục `wp-content/uploads/`

## Quy ước làm việc

- Ưu tiên chỉnh trong `bricks-child`, tránh sửa trực tiếp core WordPress hoặc theme parent `bricks`
- Khi cập nhật plugin hoặc core, cần kiểm tra lại các custom trong `functions.php`
- Không commit thông tin môi trường thật, file backup hoặc media runtime
