# Important Announcement
I don't really use this project anymore, so I will not improve this project.

For me, this project is finished and feature completed, since I want a REALLY simple URL shortener.

If you want improvements, fell free to fork it. If I see a new good fork, I will write it here.
If you want a better shortener, you can search on the [awesome-selfhosted page](https://github.com/awesome-selfhosted/awesome-selfhosted?tab=readme-ov-file#url-shorteners)

See https://github.com/azlux/Simple-URL-Shortener/issues/15 if you want to give me feedback.

# simple-shortener by Azlux

The source of this project is https://git.azlux.fr/azlux/Simple-URL-Shortener, **PR are accepted on the source only**.

Simple shortener working with MySQL or SQLite database in PHP.
The goal is to create a simple and KISS shortener without dependencies.
The user can add comments for the link to find it faster into its history.


Nice shortcut added. The shortcut will create a new short url of your current page when you click on it.
Writed to work into subfolder. (don't need to be at the root)


## Installation :
- clone this project
- Copy `inc/config.example.php` to `inc/config.php`
- Set you config file
- Call `installation.php` to setup the database
- delete `installation.php`
- Create a user, the first one will be an admin (allow you to see no connected shorted links)

## Warning
For security reasons, the cookies are set on *https* only (cookie_secure mode)
Authentification will not for if your website isn't on HTTPS.
For testing purpose, you need an browser addons to disable this security [like this one on Firefox](https://addons.mozilla.org/en-US/firefox/addon/set-cookie-no-secure-httponly/).

### Nginx configuration :

```NGINX
location / {
    rewrite ^/(.*)$ /index.php?site=$1 last;
    try_files $uri $uri/ /index.php;
}
location /assets {
    try_files $uri =404;
}
location /favicon.ico {
    try_files $uri =404;
}
location ~ \.php$ {
    try_files $uri =404;
    fastcgi_split_path_info ^(.+\.php)(/.+)$;
    fastcgi_index index.php;
    fastcgi_pass unix:/var/run/php/php7.2-fpm.sock;
    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    include fastcgi_params;
}
location ~* \.(sqlite3|ht)$ {
    deny all;
}

```
### Apache configuration (.htaccess) :
```
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^([a-zA-Z0-9]+)/?$ index.php?site=$1 [L] 
```

### Credit :
Based on code provided by [SilouFR](https://github.com/SilouFr)

Dev PHP : [Azlux](https://github.com/azlux)

Design  : [Spokeek](https://github.com/Spokeek)
