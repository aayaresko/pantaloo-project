<?php
namespace Deployer;

require 'recipe/laravel.php';

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


// Hosts

host('188.166.192.94')
    ->stage('staging')
    ->user('deployer')
    ->identityFile('deployer/id_rsa_deployer')
    ->forwardAgent(true)
    ->multiplexing(true)
    ->addSshOption('UserKnownHostsFile', '/dev/null')
    ->addSshOption('StrictHostKeyChecking', 'no');
    ->set('deploy_path', '/var/www/{{application}}');
    
// Tasks

task('build', function () {
    run('cd {{release_path}} && build');
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

before('deploy:symlink', 'artisan:migrate');

