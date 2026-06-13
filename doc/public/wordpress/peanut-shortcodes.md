## Peanut shortcodes

WordPress shortcodes are a way to embed dynamic content into your WordPress posts and pages. 
They are a simple and easy way to add functionality to your site without having to write any code. 

Peanut provides some custom short codes that are especially useful for placing in widget areas.

Currently we have the following short codes:
- [peanut-contact-link] displays a link to the peanut contact form.
- [peanut-signin-link] displays a "Sign Out" link if the user is signed in.  If not signed in, the user full name and a "Sign In" link is displayed.

These are typically placed in footer widget areas to emulate the look of the default Nutshell theme. For example in the Bootscore theme, place 
- [peanut-contact-link] in "Footer 1"
- [peanut-signin-link]  in "Footer 4"

Caution: At certain times, a shortcode may execute its code before the peanut classes are loaded, causing an exception.
This is especially true for those inserted in widget areas, since the customizer page may load without peanut initialization.

In theme and plugin files watch out for functions that may execute early or in admin or customizer pages, 
such as function.php in themes and plugin php files such as peanut.php. Check if a peanut class exists before executing 
code that depends on peanut PHP libraries. As in the example below.

```php
        if (class_exists('\Tops\sys\TUser')) {
            // better formatting
            $userName = TUser::getCurrent()->getFullName() .' | ';
        } else {
            // use if Peanut class is not available
            $userName = wp_get_current_user()->display_name .' | ';
        }
```

See  web.root/wp-content/themes/bootscore-child/functions.php for the full example.