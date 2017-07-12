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

Open `tester/index.html` for testing.

Nginx + php-fpm:

```bash
cd deployer
php deployer.phar deploy -vvv nginx
```

[http://localhost:8080/app.php](http://localhost:8080/app.php)

Redeploy - main script is old, included one - new.
