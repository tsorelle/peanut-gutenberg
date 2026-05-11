# ConcreteCMS Email Providers

ConcreteCMS uses provider classes the permit the overriding of core services. We use a custom provider to override
the default mail service to use the Tops email system instead.
The concreteCms service uses Lamina Mail (and formerly Zend mail)

There are two classes:
- Application\providers\TopsMailServiceProvider
    -Located in application\src\providers directory
- Tops\concrete5\services\TopsMailService
  - Located in packages/knockout_view/src/concrete5/services/

To configure your application:

1) In classes.ini register a mailer class:

Example:

```ini
[tops.mailer]
type='Tops\mail\TMailgunMailer'
singleton=1
```
See [Email](../peanut/email.md) for more details.

2) Add code to register the ConcreteCMS provider application/config/app.php (not to be confused with bootstrap/app.php)

```php
<?php
include_once (DIR_APPLICATION . '/' . DIRNAME_CLASSES . '/providers/TopsMailServiceProvider.php');

return [
    'providers' => array(
       'core_mail' => '\Application\providers\TopsMailServiceProvider'
    ),

];

```
If you want to use the default ConcreteCMS service instead, omit this code from app.php

3)  Be sure 'topsmailservice' is enabled in settings.ini.  Since it is enabled by default, just comment out
any existing setting that would turn it off.
