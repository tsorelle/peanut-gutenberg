WordPress plugin Peanut Block bootstrapped in the D:\dev\twoquakers\peanut2\peanut-gutenberg\peanut-block directory.

You can run several commands inside:

$ npm start
Starts the build for development.

$ npm run build
Builds the code for production.

$ npm run format
Formats files.

$ npm run lint:css
Lints CSS files.

$ npm run lint:js
Lints JavaScript files.

$ npm run plugin-zip
Creates a zip file for a WordPress plugin.

$ npm run packages-update
Updates WordPress packages to the latest version.

To enter the directory type:

$ cd peanut-block

You can start development with:

$ npm start

Code is Poetry

cd D:\dev\twoquakers\peanut2\peanut-gutenberg\web.root\wp-content\plugins\peanut
npx @wordpress/create-block@latest peanut-block  --variant=dynamic

### Adding Attributes to Peanut Block

To add the `viewmodel` and `vmcontext` attributes to the 'peanut' block, you need to modify the `block.json` file.

#### 1. Modify `block.json`
Location: `wp-block/peanut-block/src/peanut-block/block.json`

Add an `"attributes"` section to the JSON object:

```json
{
	"attributes": {
		"viewmodel": {
			"type": "string",
			"default": ""
		},
		"vmcontext": {
			"type": "string",
			"default": ""
		}
	}
}
```

#### 2. Accessing Attributes in `edit.js`
Location: `wp-block/peanut-block/src/peanut-block/edit.js`

Update the `Edit` function to receive `attributes` and `setAttributes`:

```javascript
export default function Edit( { attributes, setAttributes } ) {
	const { viewmodel, vmcontext } = attributes;
	// ...
}
```

#### 3. Accessing Attributes in `render.php`
Location: `wp-block/peanut-block/src/peanut-block/render.php`

Since it is a dynamic block, the attributes are passed in the `$attributes` array. You can use these to register a Knockout ViewModel with the Peanut framework:

```php
if ( ! empty( $attributes['viewmodel'] ) && class_exists( '\Peanut\sys\ViewModelManager' ) ) {
	\Peanut\sys\ViewModelManager::getViewModelSettings(
		$attributes['viewmodel'],
		$attributes['vmcontext'] ?? null
	);
}
```

The attributes can also be used directly in the template:

```php
$viewmodel = $attributes['viewmodel'] ?? '';
$vmcontext = $attributes['vmcontext'] ?? '';
```


#### 4. Deploying Changes to the Plugin
After running `npm run build` in the `wp-block/peanut-block` directory, copy the contents of the `build/peanut-block` directory to your main plugin's blocks folder to make the changes available in WordPress.

*   **Source:** `wp-block/peanut-block/build/peanut-block/`
*   **Destination:** `web.root/wp-content/plugins/peanut/blocks/peanut-block/`
