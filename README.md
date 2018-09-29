# Vanity
A simple directory content carrier

## How to build up a content service site with Vanity

1. install Nginx and PHP
2. fetch Vanity Source Code, `git clone https://github.com/sinri/Vanity.git` or `composer create-project sinri/vanity`
3. determine the directory to store the files, it is recommended to put it under the project root, or use a symbol link there
4. copy `config.sample.php` to `config.php` and modify it as you need
5. configure Nginx

### Permit and Forbid a Token to Access Directories or Files

You need to set the `permission` and `forbidden` in dictionary.
Just follow the sample file.

The pattern format could be referred by [fnmatch](https://secure.php.net/manual/en/function.fnmatch.php) document.

### Nginx Virtual Host Sample

```nginx
server {
    listen       80;
    #listen 443 ssl;
    server_name  vanity.free;

    #ssl on;
    #ssl_certificate /etc/letsencrypt/live/vanity.free/fullchain.pem;
    #ssl_certificate_key /etc/letsencrypt/live/vanity.free/privkey.pem;

    root   /var/www/vanity;
    index  index.php;
    access_log /var/log/nginx/access-vanity.log;
    error_log /var/log/nginx/error-vanity.log;
    error_page   500 502 503 504  /50x.html;
    location = /50x.html {
        root   html;
    }
    # LIMIT SCRIPT FOR STORE
    location ^~ /store/.*\.php$ {
    }
    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php7.0-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
    location ~ /\.git/{
        deny all;
    }
}
``` 