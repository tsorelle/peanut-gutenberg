# Web Server Configuration for Development

The target deployment webserver is usually running Apache under Linux or other Unix variant.  
The PHP version on the target testing server (testing.austinquakers.org) is 8.2

## Apache configuration
Indicate the document root directory for the project in the httpd.conf and httpd-vhosts.conf

In the examples below, substitute the full path to the document root in your environment. 

### httpd-vhosts.conf
```xml
<Directory "D:/dev/fma/austinquakers.new/web.root">
        Options Indexes FollowSymLinks
        AllowOverride all
        Order Allow,Deny
        Allow from all
</Directory>
```

### httpd-vhosts.conf
```xml
<VirtualHost *:80>
        ServerName local.austinquakers.org
        DocumentRoot "D:/dev/fma/austinquakers.new/web.root"
</VirtualHost>

```
For Windows you can place an entry in the \Windows\System32\drivers\etc\hosts file:
```php
127.0.0.1 local.austinquakers.org
```
This allows you to reference your test site in the browser as http://local.austinquakers.org


## PHP

Install PHP Version 8.2.x

### Required PHP settings:
- max_execution_time - 60 to 120
- allow_url_fopen - On
- allow_url_include - Off
- file_uploads - On
- memory_limit - 128M to 512M
- upload_max_filesize - 512M

### XDebug Extension:

Debugging PHP requires the Xdebug extension.  Here are some resources to 
help you install for PhpStorm, Microsoft Code or Visual Studio:

- PhpStorm<br>
https://www.jetbrains.com/help/phpstorm/configuring-xdebug.html

-  Microsoft Code and Visual Studio:<br>
https://marketplace.visualstudio.com/items?itemName=xdebug.php-debug

- Installation reference from Xdebug
https://xdebug.org/docs/install

