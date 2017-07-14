# Deployment playground

## How to see the progress

If you want to see how things added up - run through commits from the first one.

This is especially important for DB migrations part, as same file was changed several times.

## Setup

```bash
cd deployer
curl -LO https://deployer.org/deployer.phar
cd app
composer install
cd ../../env
./run.sh
```

To look inside php-fpm:

```bash
ssh root@localhost -p 2222
```

Open `tester/index.html` for testing.

### Nginx + php-fpm

```bash
cd deployer
php deployer.phar deploy -vvv nginx
```

[http://localhost:8080/app.php](http://localhost:8080/app.php)

Not using restart: redeploy - main script is old, included one - new.

Using restart: release is changed, but errors on deployment

### Apache:

```bash
cd deployer
php deployer.phar deploy -vvv apache
```

[http://localhost:8083/app.php](http://localhost:8083/app.php)

Inconsistencies when deploying for small amount of time.

### Nginx + php-fpm + realpath_root

```bash
cd deployer
php deployer.phar deploy -vvv nginx
```

[http://localhost:8081/app.php](http://localhost:8081/app.php)

No symlinks anywhere - whee!

Problems with OpCache:

[http://localhost:8081/opcache.php](http://localhost:8081/opcache.php)

Use cachetool to reset OpCache:
[http://gordalina.github.io/cachetool/](http://gordalina.github.io/cachetool/)

### Cache

[http://localhost:8081/cache.php](http://localhost:8081/cache.php)

Cache needs to be cleared manually if not restarting php-fpm.

Either way, simple restart on running system is not enough.

Cache keys must include current release / version number.

### Database

Let's take a case: we have table where one column represents money in
this format: `### ABC`, where `###` is amount and `ABC` is currency.

We'll try to change this structure into two columns: `###` and `ABC`. 

[http://localhost:8081/db.php](http://localhost:8081/db.php)

Migrations are run on deployment, before changing to the new code.

Let's run Load tester on DB. Then:

```bash
php deployer.phar deploy -vvv nginx
# errors should be visible
php deployer.phar rollback -vvv nginx
# ouch!

# to reset test, from env/:
./reset-db.sh
```

#### How to handle migrations

First deployment:

1. Add new columns, do not remove old ones.
2. Always insert both old and new values.
This handles previous release and possible rollback.
3. Support both new and old columns - this will be needed later.

Second deployment:

1. Make old column nullable.
2. Migrate data from old to new columns.
3. Only use new columns in code - both for inserting and returning.
As we've got step No. 3 covered in previous deployment, it's OK
to rollback at this state.

Third deployment:

1. Remove old column.
2. Make new columns non nullable (could be in previous step).
