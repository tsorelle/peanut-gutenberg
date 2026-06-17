# Nutshell Site Map Generation

This document explains how to generate the Peanut Nutshell menu configuration (`sitemap.xml`) from the WordPress navigation menu.

## Overview

The Nutshell menu and site map is driven by an XML configuration file located in `web.root/tq-peanut/application/config/sitemap.xml`. 
While this file can be edited manually, a class is provided to synchronize it with your WordPress main menu.

The builder class is: Tops\cms\wordpress\WordPressSiteMapBuilder.  Example usage:

```php
$menuName = 'my-main-menu';
$builder = new WordPressSiteMapBuilder($menuName);
$result = $builder->build();
```
The return value is an \stdclass object containing:
- success: boolean indicating whether the build was successful
- errors: array of error messages or empty if successful
- outputFile: string with absolute path to the generated sitemap.xml file.

## Generating the Menu

The Peanut plugin, peanut.php, features an action filter that will run the menu regeneration 
when the main menu is changed and keep the Nutshell menu in sync with the WordPress main menu.  
To identify the main menu by menu name, place this entry in settings.ini:

```ini
[wordpress]
menu-name=main-menu
```
If no setting is provided, the default menu name is `main-menu`.

The builder will also run when changes to authorization paths are made using the 
administration feature.

If you need to regenerate the menu configuration, use the `BuildmenuTest` script. 
```
https://(your site)/peanut/tests/buildmenu
```

Or run the following command from the project root:

```powershell
php bin/pnutstart.inc PeanutTest\scripts\BuildmenuTest
```


## Generation Logic

- **Element Names**: Generated from the menu item title. The script strips HTML, converts text to lowercase, and concatenates words (e.g., "Simple Test" becomes `<simpletest>`).
- **Title**: Extracted from the menu item title with HTML stripped.
- **Description**: Taken from the WordPress menu item description, truncated at the first newline, and stripped of HTML.
- **URI**: The relative path of the menu item URL, with leading and trailing slashes removed.
- **Roles**: Automatically resolved by querying the `AccessPathsRepository` using the item's URI.
- **Icons**: Extracted from `<i>` tags within the title or from CSS classes (e.g., `fa-gear`) assigned to the menu item in WordPress.

## Manual Additions and Multi-level Menus

WordPress navigation menus may be limited to two levels of hierarchy depending on the theme or configuration. However, Peanut's Nutshell menu supports deeper nesting.

If your application requires a 3-level or deeper menu structure, 
you may need to manually add these sub-elements to the sitemap. file.

### Examples of Manual Nesting

Here are examples of manual nesting under the `tools/email` and `tools/admin` nodes.
These sub-items appear in the Nutshell menu.

```xml
<tools title="Tools" description="" roles="" uri="tools" icon="fa-solid fa-gear">
    <email title="Email" description="" roles="administrator,email_manager" uri="tools/email">
      <mailboxes title="Mailboxes" uri="tools/email/admin/mailboxes" description="Maintain Mailboxes" />
      <send title="Send Messages" description="Mailing form" uri="tools/email/admin/message"  />
    </email>
    <admin title="Admin" description="" roles="" uri="tools/admin">
      <permissions title="Permissions" uri="tools/admin/permissions" description="Manage permissions" />
    </admin>
</tools>
```

In the example above, nodes like `mailboxes`, `send`, and `permissions` are nested three levels deep. If these cannot be represented in the WordPress menu editor, they should be maintained manually in the XML configuration.
