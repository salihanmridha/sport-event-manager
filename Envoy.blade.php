@servers(['dev-server' => '-i ' . $perm . ' root@192.168.0.152'])

@story('deploy_dev', ['on' => 'dev-server'])
    dev_update_code
    dev_run_deploy_scripts
@endstory

@task('dev_update_code')
    echo '* Cloning repository'
    cd /home/sportapp/admin-panel
    git checkout develop
    git pull origin develop
@endtask

@task('dev_run_deploy_scripts')
    echo "* Starting deployment"
    cd /home/sportapp/admin-panel
    /usr/bin/php80 $(which composer) install

    /usr/bin/php80 artisan migrate --force

    {{-- echo '* Running optimize scripts'
    cd /home/sportapp/admin-panel
    /usr/bin/php80 artisan optimize
    /usr/bin/php80 artisan view:cache

    /usr/bin/php80 $(which composer) install --no-dev --optimize-autoloader --}}
@endtask


