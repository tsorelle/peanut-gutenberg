[Return to docs home page](../index.md)

# Peanut Integration in ConcreteCMS

To include Peanut in a Concrete CMS site, we provide two custom blocks,
a custom theme is required but it only needs a small change in the 
footer_bottom.php file.

To integrate the Peanut/Qnut system with ConcreteCMS we provide two custom
blocks:

1. Knockout View Block  (in "Knockout View" package)<br>
   in web.root/packages/knockout_view<br>
   see: [Knockout View Block](knockout-view.md)<br>

2. Peanut Attribute Field Block (in "Peanut Utilities" package)<br>
in web.root/packages/peanut_utilities<br>
see: [Peanut Attribute Block](peanut-attribute-block.md)

The core peanut code and packages are under the Knockout View ComcreteCMS package.

Configuration files and one essential PHP script are placed in the application/config directory
- settings.ini: Common settings for the current installaiton
- viewmodels.ini: Index of view models in application directories
- classes.ini: Plugin classes for the current applicaiton
- google.ini: API keys for google services
- mailgun.ini: API keys for mailgun
- peanut-bootstrap.php and settings.php: start up scripts for Peanut

Additionally the application\bootstrap\app.php, has been modified to define routing paths for Peanut.

The application\peanut and application\src directory contains installation specific PHP and Typescript code.

For more details, see [Where's the Code?](wheres-the-code.md) and [Startup Sequence](startup-sequence.md)

### ConcreteCMS Theme

In previous versions, and Peanut modifid theme was provided in a seperate package.
Now, we recommend locating your costom theme in the application\themes directory.

For the AustinQuakers.org version, the theme as a "clone" of ConcreteCMS's Atomik theme.

#### Footer modification

A change to the elements/footer_bottom.php is required. The following code must be inserted
at the end of the file, just before the terminating body tag.

```php
<script type="text/javascript" src="<?=$view->getThemePath()?>/main.js"></script>
<?php
    if (!$c->isEditMode()) {
        if (class_exists('\Peanut\sys\ViewModelManager')) {
            \Peanut\sys\ViewModelManager::RenderStartScript();
        } else {
            print "ViewModelManager not found. Package 'knockout_view' is required.";
        }
    }
?>
</body>

```

This inserts JavaScript code that loads the viewmodels needed for the particular page.  
See [Startup Sequence](startup-sequence.md) for details.


#### Required JavaScript
The required javascript is inserted by the controller.php file in the knockout_view package.

```php
    public function on_start()
    {
        $al = AssetList::getInstance();
        $al->register(
            'javascript', 'knockoutjs',
            'https://cdnjs.cloudflare.com/ajax/libs/knockout/3.4.1/knockout-min.js',
            array('local' => false,'minify' => false, 'position' =>  \Concrete\Core\Asset\Asset::ASSET_POSITION_HEADER)
        );

        $al->register(
            'javascript', 'headjs',
            'https://cdnjs.cloudflare.com/ajax/libs/headjs/1.0.3/head.load.min.js',
            array('local' => false,'minify' => false, 'position' =>  \Concrete\Core\Asset\Asset::ASSET_POSITION_HEADER)
        );

        $optimize = $this->getOptimizationSetting();
        $loaderScript = $optimize ?  'dist/loader.min.js' : 'core/PeanutLoader.js' ;
        $al->register(
            'javascript', 'peanut',
            'pnut/'.$loaderScript,
            array('minify' => false, 'position' =>  \Concrete\Core\Asset\Asset::ASSET_POSITION_HEADER),
            $this
        );
    }
```
