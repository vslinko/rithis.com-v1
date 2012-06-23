set :application, "rithis.com"

set :repository, "git://github.com/rithis/#{application}.git"
set :scm, :git

set :shared_files, ["app/config.php"]
set :shared_children, []

role :web, application
set :user, "www-data"
set :deploy_to, "/var/www/#{application}"

set :composer_path, "#{shared_path}/composer.phar"
after "deploy:finalize_update", "deploy:update_vendors", "deploy:touch_config"

namespace :deploy do
    task :update_vendors do
        run "mkdir -p #{shared_path}/vendor && ln -s #{shared_path}/vendor #{release_path}/vendor"
        run "if [ ! -f #{composer_path} ]; then wget -qO #{composer_path} http://getcomposer.org/composer.phar; fi"
        run "cd #{release_path} && php #{composer_path} install"
    end
    task :touch_config do
        run "touch #{shared_path}/config.php && ln -s #{shared_path}/config.php #{release_path}/app/config.php"
    end
end
