## Me
what is the procedure for overriding bootstrap file such as scss in bootscore using bootscore-child without modification to the bootscore core theme

## AI Assistant

To override/customize Bootstrap SCSS in
Bootscore using
bootscore-child, the correct procedure is not to edit the parent bootscore theme. Instead, place your SCSS overrides in the child theme and compile the child theme assets.
Recommended Bootscore child-theme workflow
1. Work inside bootscore-child
   Use the child theme directory:``` text
   wp-content/themes/bootscore-child/
```

Do not modify files inside:``` text
wp-content/themes/bootscore/
```

because those changes will be lost when Bootscore is updated.

2. Locate the child theme SCSS files
   Bootscore child themes usually provide an assets structure similar to:``` text
   bootscore-child/
   assets/
   scss/
   _variables.scss
   _bootscore_custom.scss
   style.scss
```

Depending on the Bootscore child version, file names may vary slightly, but the idea is the same:
Use a variables file for Bootstrap variable overrides.
Use a custom SCSS file for your own CSS rules.
Let the main SCSS file import Bootstrap and your custom partials.
 
3. Override Bootstrap variables before Bootstrap is imported
Bootstrap SCSS variables must be overridden before Bootstrap’s SCSS is imported.
For example, in the child theme variable file:``` scss
$primary: #7a3cff;
$secondary: #222222;

$border-radius: 0.75rem;
$btn-border-radius: 2rem;

$font-family-base: "Inter", sans-serif;
```

This changes Bootstrap-generated CSS such as buttons, badges, links, theme colors, borders, etc.

4. Add custom SCSS after Bootstrap is imported
   For normal CSS customizations, add them to the child theme’s custom SCSS file.
   Example:``` scss
   .site-header {
   background-color: $primary;
   }

.btn-custom {
background-color: $secondary;
color: #ffffff;
}
```

Use this approach for theme-specific styling that does not need to redefine Bootstrap variables.
 
5. Compile the child theme SCSS
After editing SCSS, compile the child theme assets.
Typically this is done from the bootscore-child directory:``` bash
cd wp-content/themes/bootscore-child
npm install
npm run build
```

For development/watch mode, it may be something like:``` bash
npm run watch
```

or:``` bash
npm start
```

The exact script names depend on the child theme’s package.json.

6. Confirm the compiled CSS is enqueued
   The child theme should enqueue its compiled stylesheet through functions.php.
   Usually Bootscore child themes already do this. You generally should not need to manually enqueue the parent Bootstrap CSS unless the child theme setup has been changed.

Important rules
Do not edit parent theme files
Avoid changing:``` text
wp-content/themes/bootscore/assets/scss/
```

or any other parent theme file.
Parent theme changes are overwritten during updates.
 
Override Bootstrap variables, not compiled CSS, when possible
Prefer this:``` scss
$primary: #0057ff;
$border-radius: 1rem;
```

over this:``` scss
.btn-primary {
background-color: #0057ff;
}
```

Variable overrides are cleaner because Bootstrap regenerates all related styles consistently.
 
Keep Bootstrap variable overrides before Bootstrap import
This is the most important SCSS rule.
Correct order:``` scss
// 1. Your variable overrides
@import "variables";

// 2. Bootstrap SCSS
@import "bootstrap";

// 3. Your custom styles
@import "custom";
```

If the variables are imported after Bootstrap, many overrides will not work.

Summary
To override Bootstrap SCSS in Bootscore safely:
Use wp-content/themes/bootscore-child/.
Add Bootstrap variable overrides in the child theme SCSS variables file.
Add normal custom styles in the child theme custom SCSS file.
Compile the child theme SCSS using the child theme’s npm scripts.
Never modify the parent bootscore theme directly.
This preserves update safety while letting you fully customize Bootstrap and Bootscore styling.
