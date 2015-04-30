# simple-shortener

<p>Simple shortener working with MySQL or SQLite database in php and small javascript functions.<br /><br />
This shortener create a ID for every user to have a list of short url create by user (working with cookie). The user can add comments for the link to find it faster into its history.<br />
<br/>
Nice shortcut added. The shortcut will create a new short url of your current page when you click on it.<br /><br />
Writed to work into subfolder. (don't need to be at the root)
</p>
=======
<p>
File installation.php to execute at first access. This php file need the +w mode on the folder (only the first time, you can remove the writing after the install) is you choose the SQlite database<br />
</p>

=============
#####Nginx configuration :
```NGINX
if (!-e $request_filename) {
    	rewrite ^/([^/]*)$ /index.php?site=$1 last;
}
```
Think about remove access to the sqlite file (and .htaccess) with :
```NGINX
location ~* \.(sqlite3|ht)$ {
        deny all;
}
```
#####Apache configuration (.htaccess) :
    not tested (send me feedback)

<br/>
####Credit :<p>
Based on code provided by [SilouFR](https://github.com/SilouFr)
</p>
