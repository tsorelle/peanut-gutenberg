[Return to docs home page](../index.md)
# Startup Sequence

This document is for reference only. It describes the somewhat
complicated process for loading the PEANUT system. 
The need to modify this code is very unlikely, but it might
be useful to understand what it does.

The viewmodel developer just needs to define and register (viewmodel.ini) the view (html) and the viewmodel (TypeScript),
and put a Knockout View Block on the page. See:  [View Models and Views](viewmodels-views.md) for details.

The rest takes care of itself.  Here is the actual sequence:

1. application/bootstrap/app.php is executed by ConcreteCMS on start up.  
This file registers routing instructions and performs other CMS configurations. 
Peanut specific routing etc is included in 'peanut-app.php' which 
calls /config/peanut-bootstrap.php
3. Bootstrap::initialize() in peanut-bootstrap.php<br>
Initalizes settings used by Peanut and registers autoload functions for our 
vendors directory as well as Peanut PHP code.
2. Concrete\Package\KnockoutView\on_start()<br>
Registers the required javascript assets: headJS and the Peanut loader. 
All other JS and CSS dependencies are loaded dynamically by Peanut, 
unless they are already present in ConcreteCMS.
2. Concrete\Package\KnockoutView\Block\KnockoutView\Controller.view()<br>
   - Retrieves and initializes view model settings: ViewModelManager::getViewModelSettings();
   - Using the view model setting, the content of the ViewModel's view html file is
   assigned to the 'content' attribute which is rendered with the page.
3. ViewModelManager::getViewModelSettings()<br>
Searches the config and packages directory, parses viewmodel.ini files and
creates an array of "vmInfo" objects to be refenced in
later loading code.
4. Footer code in theme/elements/footer_bottom.php
```php
    if (!$c->isEditMode()) {
        if (class_exists('\Peanut\sys\ViewModelManager')) {
            \Peanut\sys\ViewModelManager::RenderStartScript();
        } else {
            print "ViewModelManager not found. Package 'knockout_view' is required.";
        }
    }
```
At run time, RenderStartScript() produces code that loads one or more viewmodels used on the
current page, or blank if there are none. This is based on the array compiled in step #2. Because
this code is located at the very bottom of the page, all the markup has been loaded including the
view, the previous steps have been performed, so we are all ready to load the viewmodel and bind it 
to the markup.<br><br>Example:
```php 
    Peanut.PeanutLoader.renderServiceMessageTags();
    Peanut.PeanutLoader.startApplication('@pkg/qnut-committees/CommitteeDescription#750', function() {
      Peanut.PeanutLoader.loadViewModel('@pkg/qnut-committees/CommitteeMembers#2995', function() {
        Peanut.PeanutLoader.loadViewModel('@pkg/qnut-documents/DocumentList#2996', function() {
          Peanut.PeanutLoader.loadViewModel('@pkg/qnut-calendar/Calendar#2997');
        });
      });
    });
```
In most cases only one view model is loaded. A Committee page uses four blocks.<br>


5. PeanutLoader.startApplication() in web.root/packages/knockout_view/pnut/core/PeanutLoader.ts<br>
   - Loads remaining javascript dependencies
   - Calls Application.startVM
6. Appliction.startVM() in web.root/packages/knockout_view/pnut/core/App.ts<br>
Calls koHelper.loadViewModel() to load the first viewmodel.
7. Peanut.KnockoutHelper.loadViewModel() in web.root/packages/knockout_view/pnut/core/KnockoutHelper.ts<br>
Instantiates and initializes the ViewModel, then calls the start() method for the viewmodel.
8. Peanut.ViewModelBase.start() in web.root/packages/knockout_view/pnut/core/ViewModelBase.ts<br>
ViewModelBase, as you might guess, is the base class for all viewmodels. The start() routine which
performs initialization and them calls the init() method for the current instance.
9. ViewModel init() method. For example see: web.root/application/peanut/tests/vm/SimpleTestViewModel.ts<br>
Every implementation of a viewmodel must include an init() method. This will contain all specific
startup code for the view model and must call the bindDefaultSection() method and any other
binding methods that use Knockout helper utilities such as  koHelper.bindSection (by way of the Application object)
to bind the viewmodel two the view.
10. Peanut.KnockoutHelper.bindSection() <br>
Uses the KnockoutJS library to bind the view model to the view.
8. If needed addtional viewmodels are loaded with Peanut.PeanutLoader.loadViewModel()
