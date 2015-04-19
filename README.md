# simple-shortener

<p>Simple shortener working with MySQL database (for now) in php and small javascript functions.
This shortener create a ID for every user to have a list of short url create by user (working with cookie). The user can add comments for the link to find it faster into its history.<br />
Writed to work into subfolder. (don't need to be at the root)
</p>
<p>
File bdd.php to change with your own user/password of your MySQL's user.<br />
I have in every css file there are in line 5 : 
</p>
```CSS
background: url("sunset.png") no-repeat fixed;
```
<p>
Add a picture or remove this line.
</p>
=============
#####Nginx configuration :
```NGINX
if (!-e $request_filename) {
    	rewrite ^/([^/]*)$ /index.php?site=$1 last;
}
```
#####Apache configuration (.htaccess) :
    not tested (send me feedback)

#####MySQL command to create the table :
```SQL
CREATE TABLE shortener
(
    short CHAR(5) PRIMARY KEY NOT NULL,
    url VARCHAR(700) NOT NULL,
    comment CHAR(30),
    views INT,
    id_user CHAR(4),
    date DATE NOT NULL
);
CREATE INDEX id_user ON shortener (id_user);
```

<br/>
####Credit :<p>
Based on code provided by [SilouFR](https://github.com/SilouFr)
</p>
