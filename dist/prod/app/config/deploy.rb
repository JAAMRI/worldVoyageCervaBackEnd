set :application, "chroo-heed-demo-api"
set :domain,      "35.156.23.37"
set :user,        "ubuntu"
set :deploy_to,   "/var/www"
set :deploy_via,    :copy

set :app_path,    "app"

set :repository,  "/Applications/AMPPS/www/clients/zfzmyrjlmj/dist/prod"
ssh_options[:keys] = "/Users/HappyMan/WEALTH/CHRONO_HEED/black/chronoHeedDemoInstance.pem"
ssh_options[:forward_agent] = true

#set :scm,         :subversion
# Or: `accurev`, `bzr`, `cvs`, `darcs`, `subversion`, `mercurial`, `perforce`, or `none`

set :model_manager, "doctrine"
# Or: `propel`

#Make parameters.yml and .htaccess files shared between all deployments
set :shared_files,      ["app/config/parameters.yml", "web/.htaccess"]

#Share the vendor directory between all deployments to make deploying faster
set :shared_children,     [app_path + "/logs", web_path + "/uploads", "vendor"]

#Make cache and logs writable
set :writable_dirs,       ["app/cache", "app/logs"]
set :webserver_user,      "www-data"
set :permission_method,   :acl
set :use_set_permissions, true

#Keep only 3 releases
set   :keep_releases, 3

#Use Composer to update
set :use_composer, true

#Update Vendor
set :copy_vendors, :except => { :no_release => true }

set :update_vendors, true


role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain, :primary => true       # This may be the same as your `Web` server

set  :use_sudo,   true
set  :keep_releases,  3


# Be more verbose by uncommenting the following line
logger.level = Logger::MAX_LEVEL
