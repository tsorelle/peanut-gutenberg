# Nutshell Routing

Nutshell employs a configuration file to define routes and associated pages with view models: \tq-peanut\application\config\routing.ini.

Because WordPress lacks any custom routing other that the menu system, we use Nutshell to fill the gap.  The routing configuation is
used to define service URIs and implement redirection.  If a path is configured for a view model, the system redirects
to the Nutshell system to execute the service or display the view model on a page.  When the path is not found in the 
configuration, WordPress takes over and handles the request.

Here are some examples of routing configuration:

Show the administrators permissions configuration page in Nutshell:

````ini
[admin/permissions]
handler='page';
mvvm=permissions
roles='administrator';
````

Execute a Peanut service:

````ini
[peanut/service/execute]
handler='service'
args=sid,arg
method='executeService'
````

Redirect to WordPress Page:

````ini
[recover-password]
; wordpress
handler='redirect'
target='wp-login.php?action=lostpassword'
````
