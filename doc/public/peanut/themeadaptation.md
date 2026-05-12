[Return to docs home page](../index.md)
# Theme Adaptation

## Current supported CMS

### Nutshell
Nutshell is our own framework for simple sites and demos.

## UI Framework Support

The theme must include required css and javascript for a supported uiFramework. 
Currently (2024) the preferred framework is Bootstrap 5.  Support for ui frameworks 
is implemented in TypeScript classes and templates found in pnut/extensions.

HTML Files in pnut/templates must support the selected ui framework, especially 
serviceMessages.html.  In the current version these are Bootstrap specific  
In a future version, these may be moved to pnut/extensions to afford better 
support of other frameworks. 

## Main Page Requirements
### Head Element 
Add bootstrap and font awesome script tags as needed. Bootstrap and FontAwesome based 
themes my take care of this anyway.

### End of body Element 
The following code must be added at the end of the body element.
````php
<!-- for Peanut support -->
    <?php if (isset($mvvm)) { ?>
        <!-- bootstrap dependencies for Peanut -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/headjs/1.0.3/head.load.js" integrity="sha512-XDpsu7o5F1+SqCmdXgSfbx7yPA99X0IQs8RsbiQSrJ4kxOZSlbJtgCJjmVbLiAPKOhnffctq61O/VMlD88GcxA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="/nutshell/pnut/core/PeanutLoader.js"></script>
    <?php }
        \Peanut\sys\ViewModelManager::RenderStartScript();
    ?>
````
In a ConcreteCMS theme, this may be added to the end of elements/footer.php.