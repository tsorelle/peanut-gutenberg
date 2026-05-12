[Return to docs home page](../index.md)

# Object Container

In order to adapt peanut to different versions and installations
we use a lightweight dependency container system that creates instances based on entries in the application/classes.ini

## Dynamic object creation

For many scenarios we "plug in" an automaically created instance of a class that supports
a common interface.  For example objects that implement the IMailer interface are used to 
send email messages.

```php
interface IMailer {
    public function send(TEMailMessage $message);
    public function setSendEnabled($value);
}
```

Classes implement IMailer include:
- TPhpMailer: uses the PHPMailer framework.
- TMailgunMailer: uses the Mailgun email service
- TDevMailer: for testing in a development enviornment, writes 
the message to a file an a "maildrop" directory.
- TNullMailer: for testing or temporarily disabling mailing features. 
Does not interupt the mailing process but does not actually send a message.

The TObjectContainer class handles object creation. The most commonly used functions 
are these static methods:

- TObjectContainer::HasDefinition($key) - checks to see if a class is registered in classes.ini
- TObjectContainer::Get($key) - creates an instance.

### Example:
#### classes.ini
In classes.ini for a development environment:

```ini
[tops.mailer]
type='Tops\mail\TDevMailer'
singleton=1
```
In production:
```ini
[tops.mailer]
type='Tops\mail\TMailgunMailer'
singleton=1
```
Singleton means that a single global instance of the class is reused.


#### Usage in PHP

```php
$mailer = TObjectContainer::Get('tops.mailer');
$mailer->send($message);
```
## Common definitions
Here are some example entries in classes.ini that are frequently used.
- *[tops.userfactory]* Creates a facade for creation of user objects specific to the CMS or framework
- *[tops.mailer]* Uses different mail services to send email messages
- *[tops.permissions]*  Adapts the peanut permission structure to the CMS or framework
- *[tops.language]* Objects used for translation
- *[peanut.vmcontext]* Interprets vmcontext codes for different CMS implementations
- *[tops.eventhandler.user]* Routes user events depending on the CMS
- *[peanut.subscription_manager]* Handle email subscriptions for different implementations of Peanut

