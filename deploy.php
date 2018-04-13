<?php
namespace Deployer;

require 'recipe/laravel.php';

// Project name
set('application', 'mcdata-api');

// Project repository
set('repository', 'git@github.com:StevenWilliams/mcdata-api.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true); 

// Shared files/dirs between deploys 
add('shared_files', ['.env',]);
add('shared_dirs', ['storage',]);

// Writable dirs by web server 
add('writable_dirs', []);


// Hosts

host('54.39.58.28')
    ->user('ubuntu')
    ->set('deploy_path', '/var/www/html/laravel/{{application}}');
    
// Tasks

task('build', function () {
    run('cd {{release_path}} && build');
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

before('deploy:symlink', 'artisan:migrate');

