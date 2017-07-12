<?php

namespace Deployer;

require 'recipe/common.php';

host('localhost/nginx')
    ->stage('nginx')
    ->user('root')
    ->port(2222)
    ->set('deploy_path', '/var/www')
    ->addSshOption('StrictHostKeyChecking', 'no');

host('localhost/apache')
    ->stage('apache')
    ->user('root')
    ->port(2223)
    ->set('deploy_path', '/var/www')
    ->addSshOption('StrictHostKeyChecking', 'no');

desc('Restart PHP-FPM service');
task('php-fpm:restart', function () {
    run('cachetool apcu:cache:clear');
    run('cachetool opcache:reset');
})->onStage('nginx');
after('deploy:symlink', 'php-fpm:restart');
after('rollback', 'php-fpm:restart');


desc('Restart Apache service');
task('apache:restart', function () {
    run('apachectl -k graceful');
})->onStage('apache');
after('deploy:symlink', 'apache:restart');
after('rollback', 'apache:restart');

desc('Deploy your project');
task('deploy', [
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'copy',
    'deploy:shared',
    'deploy:writable',
    'deploy:clear_paths',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
    'success'
]);

after('deploy:failed', 'deploy:unlock');

task('copy', function() {
    $config = Task\Context::get()->getHost();
    $host = $config->getRealHostname();
    $port = $config->getPort() ? ' -P' . $config->getPort() : '';
    $identityFile = $config->getIdentityFile() ? ' -i ' . $config->getIdentityFile() : '';
    $user = !$config->getUser() ? '' : $config->getUser() . '@';

    $src = __DIR__ . '/app';

    runLocally("scp -r -o StrictHostKeyChecking=no -o UserKnownHostsFile=/dev/null -q$port$identityFile $src/* '$user$host:{{release_path}}/'");
});
