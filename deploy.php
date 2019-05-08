<?php
namespace Deployer;

require 'recipe/laravel.php';
require 'recipe/slack.php';

// Project name
set('application', 'casinobit');

// Project repository
set('repository', 'git@github.com:webmaxstudio/casinobit.git');

set('default_stage', 'staging');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true); 

// Shared files/dirs between deploys 
add('shared_files', []);
add('shared_dirs', []);

// Writable dirs by web server 
add('writable_dirs', []);

before('deploy', 'slack:notify');
after('success', 'slack:notify:success');

set('slack_webhook', 'https://hooks.slack.com/services/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');


// Hosts

host('188.166.192.94')
    ->stage('staging')
    ->user('deployer')
    ->identityFile(__DIR__.'/deployer/id_rsa_deployer')
    ->forwardAgent(true)
    ->multiplexing(true)
    ->addSshOption('UserKnownHostsFile', '/dev/null')
    ->addSshOption('StrictHostKeyChecking', 'no')
    ->set('http_user','www-data')
    ->set('deploy_path', '/var/www/{{application}}');

host('46.28.205.63')
    ->stage('prod')
    ->user('administrator')
    ->identityFile(__DIR__.'/deployer/id_rsa_deployer')
    ->forwardAgent(true)
    ->multiplexing(true)
    ->addSshOption('UserKnownHostsFile', '/dev/null')
    ->addSshOption('StrictHostKeyChecking', 'no')
    ->set('http_user','www-data')
    ->set('branch', 'master')
    ->set('deploy_path', '/var/www/{{application}}');
    
// Tasks

task('build', function () {
    run('cd {{release_path}} && build');
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

before('deploy:symlink', 'artisan:migrate');

