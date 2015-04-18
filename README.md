# simple-shortener

<p>Simple shortener working with MySQL database (for now) in php and small javascript functions.
This shortener create a ID for every user to have a list of short url create by user (working with cookie).
Writed to work into subfolder. (don't need to be at the root)
</p>
<p>
File bdd.php to change with your own user/password of your MySQL's user.<br>
I have in every css file there are in line 5 : 
</p>
	background: url("sunset.png") no-repeat fixed;
<p>
Add a picture or remove this line.
</p><p></p>
#####Nginx configuration :
    if (!-e $request_filename) {
    		rewrite ^/([^/]*)$ /index.php?site=$1 last;
    }

#####Apache configuration (.htaccess) :
    ..... (help)

#####Commande MySQL to create the table :
	CREATE TABLE shortener
	(
			id INT(6) PRIMARY KEY NOT NULL AUTO_INCREMENT,
			short CHAR(5) NOT NULL,
			url VARCHAR(700) NOT NULL,
			comment CHAR(30),
			views INT(5),
			id_user CHAR(4),
			date DATE NOT NULL
	);
	CREATE INDEX id_user ON shortener (id_user);


<p><br/>
Credit :<br />
base on code provide by @SilouFr
</p>
