### WordPress Equivalents to ConcreteCMS `$c->isEditMode()`

WordPress handles "editing" differently than ConcreteCMS. Depending on your context, use one of the following:

### 1. The Theme Customizer
If you want to know if the user is currently viewing the site through the **WordPress Customizer**:
```php
if ( is_customize_preview() ) {
    // Closest equivalent to Concrete's "Edit Mode" preview
}
```

### 2. Checking if in the Admin Dashboard
If you want to check if the current request is for the back-end dashboard:
```php
if ( is_admin() ) {
    // You are in the wp-admin dashboard
}
```

### 3. Checking User Permissions
To show/hide something on the front-end only for people who **can** edit the page:
```php
if ( current_user_can( 'edit_posts' ) ) {
    // The user has permission to edit
}
```

### 4. Gutenberg / Block Editor (Back-end)
To detect if you are inside the Gutenberg editor screen (PHP):
```php
$screen = get_current_screen();
if ( $screen && $screen->is_block_editor() ) {
    // You are in the Gutenberg editor
}
```

### 5. Avoiding Scripts in "Edit Mode" (Gutenberg/FSE)
If you want to prevent a script (like a KnockoutJS binder) from running while a block is being edited in Gutenberg or the Site Editor:

#### In a Theme Template (e.g., `footer.php`)
In WordPress, the dashboard is `is_admin()`. Since the Gutenberg editor is in the dashboard, this is the simplest check:
```php
if ( ! is_admin() && ! is_customize_preview() ) {
     if ( class_exists('\Peanut\sys\ViewModelManager') ) {
         \Peanut\sys\ViewModelManager::RenderStartScript();
     }
}
```

#### In a Dynamic Block (`render_callback`)
If your block is rendered via PHP, the `render_callback` runs in both the frontend and the editor preview. To exclude the editor:
```php
public function render_callback( $attributes, $content ) {
    // Only enqueue/print scripts if NOT in the admin/editor
    if ( ! is_admin() ) {
        // Enqueue your knockout binder here
    }
    return $content;
}
```

#### Modern Gutenberg Way (`block.json`)
If you are building native blocks, use the `viewScript` property in your `block.json`. WordPress will **automatically** only load this script on the frontend and NOT in the editor:
```json
{
  "name": "my-plugin/my-knockout-block",
  "viewScript": "file:./view.js"
}
```

### 6. The "Non-Template" Footer Hook (Wait for all blocks)
If you need your code to run **after all blocks** have been rendered (to collect data or initialize a manager), use the `wp_footer` hook in your plugin or `functions.php`. This avoids editing the `footer.php` template file.

```php
// In a plugin or functions.php
add_action( 'wp_footer', function() {
    // 1. Ensure we are NOT in the editor
    if ( is_admin() || is_customize_preview() ) {
        return;
    }

    // 2. Run your global initialization
    if ( class_exists('\Peanut\sys\ViewModelManager') ) {
        \Peanut\sys\ViewModelManager::RenderStartScript();
    }
}, 100 ); // High priority (100) ensures it runs at the very bottom
```

**Why this is the WordPress way:**
- **Hooks over Templates:** Unlike ConcreteCMS where you often edit the footer template, WordPress prefers hooks. This keeps your logic in your plugin/feature.
- **Lifecycle:** `wp_footer` fires after the page content (the Loop) is finished. Any block that "registered" itself with `ViewModelManager` during the rendering phase will be captured here.
- **Injected Scripts:** You can also use `wp_add_inline_script( 'handle', 'data' )` if you want to pass data to a specific registered JS file.

### Summary Table

| Context | ConcreteCMS | WordPress Equivalent |
| :--- | :--- | :--- |
| **General Edit Mode** | `$c->isEditMode()` | `is_admin()` (WP handles editing in backend) |
| **Customizer** | `$c->isEditMode()` | `is_customize_preview()` |
| **Block Preview** | `n/a` | `is_admin()` or `defined('REST_REQUEST')` |
| **Permissions** | `$c->isEditMode()` | `current_user_can('edit_post', $id)` |

If you are trying to show specific "edit-only" styles on the front-end, developers typically use `is_user_logged_in()` or `current_user_can()` alongside `is_admin_bar_showing()`.