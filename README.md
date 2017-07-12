# Deployment sandbox

Setup:

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

