# Coolify MariaDB Deployment

Use MariaDB/MySQL in Coolify by setting these environment variables on the app:

```env
APP_NAME="Kote Kob La"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.example

DB_CONNECTION=mysql
DB_HOST=your-mariadb-service
DB_PORT=3306
DB_DATABASE=kote_kob_la
DB_USERNAME=your_user
DB_PASSWORD=your_password
```

Then run:

```bash
composer install --no-dev --optimize-autoloader
php artisan key:generate --force
php artisan migrate --force
php artisan storage:link
php artisan optimize
```

To seed the original spreadsheet data on a fresh database:

```bash
php artisan db:seed --force
```

To migrate the current local SQLite data instead of reseeding, generate a dump locally:

```bash
php scripts/sqlite_to_mysql_dump.php
```

That writes `storage/app/mysql-data-dump.sql`. Import that file into MariaDB after running migrations.

The local Windows PHP build used during development needs `pdo_mysql` enabled to connect directly to MariaDB. Coolify PHP images normally include this driver, but confirm it if the app reports `could not find driver`.
