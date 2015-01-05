# Idealist Installation
* Download Idealist files put them in a directory. E.g. `/var/www/idealist`.
* Rename the `config.ini-example` file to `config.ini`.
* Open up the `config.ini` and configure your installation. You'll have to provide where your repositories are located and the base Idealist URL (in our case, http://localhost/idealist).
* Create the cache folder and give read/write permissions to your web server user:

```
cd /var/www/idealist
mkdir cache
chmod 777 cache
```

That's it, installation complete!

## Webserver configuration
Apache is the "default" webserver for Idealist. You will find the configuration inside the `.htaccess` file. However, nginx and lighttpd are also supported.

### nginx server.conf

```
server {
    server_name MYSERVER;
    access_log /var/log/nginx/MYSERVER.access.log combined;
    error_log /var/log/nginx/MYSERVER.error.log error;

    root /var/www/DIR;
    index index.php;

#   auth_basic "Restricted";
#   auth_basic_user_file .htpasswd;

    location = /robots.txt {
        allow all;
        log_not_found off;
        access_log off;
    }

    location ~* ^/index.php.*$ {
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;

        # if you're using php5-fpm via tcp
        fastcgi_pass 127.0.0.1:9000;

        # if you're using php5-fpm via socket
        #fastcgi_pass unix:/var/run/php5-fpm.sock;

        include /etc/nginx/fastcgi_params;
    }

    location / {
        try_files $uri @idealist;
    }

    location ~* \.(js|css|png|jpg|jpeg|gif|ico)$ {
    add_header Vary "Accept-Encoding";
        expires max;
        try_files $uri @idealist;
        tcp_nodelay off;
        tcp_nopush on;
    }

#   location ~* \.(git|svn|patch|htaccess|log|route|plist|inc|json|pl|po|sh|ini|sample|kdev4)$ {
#       deny all;
#   }

    location @idealist {
        rewrite ^/.*$ /index.php;
    }
}
```

### lighttpd

```
# Idealist is located in /var/www/idealist
server.document-root        = "/var/www"

url.rewrite-once = (
    "^/idealist/web/.+" => "$0",
    "^/idealist/favicon\.ico$" => "$0",
    "^/idealist(/[^\?]*)(\?.*)?" => "/idealist/index.php$1$2"
)
```

### hiawatha

```
UrlToolkit {
    ToolkitID = idealist
    RequestURI isfile Return
    # If you have example.com/idealist/ ; Otherwise remove "/idealist" below
    Match ^/idealist/.* Rewrite /idealist/index.php
    Match ^/idealist/.*\.ini DenyAccess
}
```
