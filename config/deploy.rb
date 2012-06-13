set :application, "rithis.com"

set :repository,  "git://github.com/rithis/#{application}.git"
set :scm, :git

set :shared_files, []
set :shared_children, []

role :web, application
set :user, "www-data"
set :deploy_to, "/var/www/#{application}"

namespace :deploy do
    task :finalize_update do
    end
end
