# Nutshell Menu Generation

This document explains how to generate the Peanut Nutshell menu configuration (`sitemap.xml`) from the WordPress navigation menu.

## Overview

The Nutshell menu is driven by an XML configuration file located in `web.root/tq-peanut/application/config/sitemap.xml`. While this file can be edited manually, a routine is provided to synchronize it with the WordPress `main-menu`.

## Generating the Menu

To generate the menu configuration, use the `BuildmenuTest` script. This script fetches the WordPress menu items, resolves permissions (roles) via the Peanut Access Paths repository, and formats the output as XML.

### Command

Run the following command from the project root:

```powershell
php bin/pnutstart.inc PeanutTest\scripts\BuildmenuTest
```

*Note: Ensure that `php` is in your system path or use the full path to your PHP executable.*

### Output

The script writes the generated XML to:
`web.root/tq-peanut/application/config/wp-sitemap.xml`

This output file is separate from the production `sitemap.xml` to prevent accidental overwrites. You can inspect the results and merge them into the main configuration as needed.

## Generation Logic

- **Element Names**: Generated from the menu item title. The script strips HTML, converts text to lowercase, and concatenates words (e.g., "Simple Test" becomes `<simpletest>`).
- **Title**: Extracted from the menu item title with HTML stripped.
- **Description**: Taken from the WordPress menu item description, truncated at the first newline, and stripped of HTML.
- **URI**: The relative path of the menu item URL, with leading and trailing slashes removed.
- **Roles**: Automatically resolved by querying the `AccessPathsRepository` using the item's URI.
- **Icons**: Extracted from `<i>` tags within the title or from CSS classes (e.g., `fa-gear`) assigned to the menu item in WordPress.

## Manual Additions and Multi-level Menus

WordPress navigation menus may be limited to two levels of hierarchy depending on the theme or configuration. However, Peanut's Nutshell menu supports deeper nesting.

If your application requires a 3-level or deeper menu structure, you may need to manually add these sub-elements to the XML file.

### Examples of Manual Nesting

Refer to `web.root/tq-peanut/application/config/sitemap.xml` for examples of manual nesting under the `tools/email` and `tools/admin` nodes:

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
