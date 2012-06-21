# rithis.com

## Installation

    git clone git://github.com/rithis/rithis.com.git
    cd rithis.com
    git remote add silex-skeleton git://github.com/rithis/silex-skeleton.git
    composer.phar install

## Server configuration

### Apache

    <VirtualHost *:80>
        DocumentRoot /var/www/rithis.com/current
        ServerName rithis.com

        RewriteEngine on
        RewriteCond %{DOCUMENT_ROOT}/web%{REQUEST_URI} !-f
        RewriteRule ^ /app/index.php [L]
        RewriteRule (.*) /web$1
    </VirtualHost>

### nginx

    server {
        server_name rithis.com;
        root /var/www/rithis.com/current;

        location / {
            try_files /web$uri @fallback;
        }

        location @fallback {
            fastcgi_pass unix:/var/run/php5-fpm.sock;
            include fastcgi_params;
            fastcgi_param SCRIPT_FILENAME $document_root/app/index.php;
        }
    }
