# Local Home Page for OSX Web Development

Forked from [https://github.com/cmall/LocalHomePage](https://github.com/cmall/LocalHomePage).

## Local Setup Documentation

Local DocumentRoot: `/Users/username/Sites`  
Local project root: `/Users/username/Repositories`

### Add VirtualHost

Add VirtualHost configuration in `/usr/local/etc/httpd/extra/httpd-vhosts.conf`:

```
<VirtualHost *:80>
    DocumentRoot "/Users/username/Sites/sitename.test"
    ServerName sitename.test
    <Directory "/Users/username/Sites/sitename.test">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

Symlink project from local repository to `Sites` directory:

```
ln -s /Users/username/Repositories/sitename/public /Users/username/Sites/sitename.test
```

Restart apache:

```
sudo apachectl -k restart
```
