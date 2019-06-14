# WARNING
Work in progress. We rewrite the UI, fix bug and other stuff during this week.
Please wait few days before using this repo.

# simple-shortener by Azlux

Simple shortener working with MySQL or SQLite database in PHP.
The goal is to create a simple and KISS shortener without dependencies.
The user can add comments for the link to find it faster into its history.


Nice shortcut added. The shortcut will create a new short url of your current page when you click on it.
Writed to work into subfolder. (don't need to be at the root)


## Installation :
- clone this project
- Copy `config.example.php` to `config.php`
- Set you config file
- Call `installation.php` to setup the database
- delete `installation.php`
- Create a user, the first one will be an admin (allow you to see no connected shorted links)


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
(delete the file if you are on nginx)


### Credit :<p>
Based on code provided by [SilouFR](https://github.com/SilouFr)

