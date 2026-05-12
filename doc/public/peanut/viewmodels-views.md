[Return to docs home page](../index.md)
# View Models and Views

The key technology used for the presentation parts of Peanut is the KnockoutJS, a 
lightweight JavaScript library that supports two-way databinding between a JavaScript
object called a ViewModel and a section of HTML called the View.

The online documentation provides a clear and easy explanation so I will refer you
there rather than attempt an explanation here.  Check out this like for a general 
understanding of viewmmodels, views, obsrvables and components. Then the rest of
this article will make sense.

[Knockout JS Documentaton](https://knockoutjs.com/documentation/introduction.html)

As long as you understand the concepts, you won't have to be concerned with the 
details of ViewModel loading and binding.  You will need to discover how the
various databinding work but you can pick that up as needed with the help of
the Knockout online reference.

You will need to understand the conventions we use for creating and managing viewmodels, 
views and components. This is what I'll explain here.

## ViewModels and Views in Peanut
Note: For the fist part of these instructions, please refer the the "Simple Test" 
page example.  This is a "Hello World" type of basic demonstration of Peanut/Knockout.
- URL: [/tasks/testing/simple-test](/tasks/testing/simple-test)<br>
  (sign in with an administrator account to see this page)
- Source:
  - viewmodel: web.root/application/peanut/tests/vm/SimpleTestViewModel.ts
  - view: web.root/application/peanut/tests/view/SimpleTest.html
- ini file: web.root/application/config/viewmodels.ini

You may not need to build your own, but understanding how it is done will 
help maintain the existing collection. It is important to follow our conventions for
naming a file location since much of the functionality is driven by convention.
Mind the case of filenames remembering that on the Linux servers where our sites live
the file system is case sensitive.

Our viewmodels are written in TypeScript. If you are not familiar see (TypeScriptLang.org)[https://www.typescriptlang.org/].
Most details of our code is plain JavaScript, so, you know JavaScript you should be at home.

Views for us are fragments of HTML that are inserted in the page. These files are location in particular places depending
on how you intend to apply or distrubute them.

### Other Essentials
#### Knockout Components

These components are essentially smaller viewmodel/view pieces that can be
used across view models.  Components are loaded and registered in the 
ViewModel.init() method and referenced in the view as a custom tag.

To learn about how they are implemented in Peanut,
see [Components Overview](components/components-overview.md)

#### Service Commands

Service commands are PHP classes that recieve remote requests (Ajax)
from a JavaScript/TypeScript client, usually a view model.

For a detailed discussion of Service Commands see: [Service Commands](service-commands.md)

## Location and naming conventions
Viewmodels are stored in direcories named "vm" with the related view file in a
directory named "view" on the same directory level.  We frequently use the term 
"viewmodel" to refer to both.  The pair share a view model
name used to name the files and to identify them in the code. 

In our "Simple Test" example, convertions for names and locations of files, as well
as naming of identifiers is as follows:
- ViewModel name "SimpleTest"
- ViewModel identifier: "simple-test"
- Section in viewmodel.ini: [simple-test]
- Viewmodel class name: **SimpleTest**ViewModel
- Viemodel file name: **SimpleTest**ViewModel.ts (compiles to SimpleTestViewModel.js)
- View file name: **SimpleTest**.html
- Source file locations are "view" and "vm" under web.root/application/peanut/tests
- Div id names in SimpleTest.html
  - id="**simpletest**-load-message"
  - id="**simpletest**-view-container"

## Creating and Installing a Viewmodel
The steps involved in producing a new viewmodel include:
1. Choose a location for the viewmodel files
2. Choose a globally unique view model name. 
3. Add an entry to a viewmodels.ini file
4. Create and implement the viewmodel class and view html file.
5. Implement any related service commands you will need. See: [Service Commands](service-commands.md)
6. Add a Knockout View block to a page as described in the previous section.

### Locations
These are the various locations you can choose from. You can also put your files
in a sub-directory if you indicate this in the viewmodel.ini entry.

- web.root/application/peanut<br>
  - This location is for viewmodels that are specific to a particular application. 
  For example: austinquakers.org for Friends Meeting of Austin.
  - The viewmodels.ini file for these is web.root/application/config/viewmodels.ini
  - PHP source such as Service Commands for the application go in web.root/application/src
- Sub-directories of the Peanut Package directory: web.root/packages/knockout_view/pnut/packages
  - This is for code that might be used in other sites with Peanut.
    - Each package has a "config" subdirectory containing its viewmodels.ini file.
    - Each package has a "src" subdirectory for PHP source code.
    - Directory names for packages for use by other Friends meetings and organizations are
      prefixed with "qnut-".  E.g. web.root/packages/knockout_view/pnut/packages/qnut-committees
    - Peanut core features are contained in package directories prefixt with "peanut-".
      E.g. web.root/packages/knockout_view/pnut/packages/qnut-committees
  - PHP code used in common by all Peanut and application features is in:
    web.root/packages/knockout_view/src
### Naming
Viewmodel names are camel cased, e.g. "SimpleTest". We derive the viewmodel identifier
by lowercasing the name and putting a hyphen between words. E.g. "simple-test"

Viewmodel names must be globally unique. For example if you have a one named 
"ContactForm" and there is an existing one by the same name, the loader will
pick the first one it comes to when scanning the viewmodels.ini files. This is
not fatal but could cause confusion.  The easiest way to determine uniqueness is
to use a search feature, such as the one in the PhpStorm IDE.

### Entry in viewmodels.ini

The viewmodels.ini entry begins with "[viewmodel-identifier]" follow by
vm=ViewModelName. Example:

```ini
[committees]
vm=Committees
```
If the viewmodel is located in a sub-directory, this can be included in the vm setting.
```ini
[simple-test]
vm=tests/SimpleTest
```
#### Other optional settings: #### 
- view: indicates a custom view file that will be used instead of the
  the usual one indicated by the naming convention. In the ConcreteCMS version,
  view=content" indicates that the content field of the Knockout View block will be used instead
  of a view file
- roles: Is a comma delimited of security roles to which use of the viewmodel is 
  limited.  This is an extra security measure, you should generally use page permissions
  to limit access in a graceful way.
```ini
[committees]
vm=Committees
roles=authenticated
```
## Creating the View file in HTML

To start, open the template file, web.root/packages/knockout_view/pnut/examples/view-template.txt.

Replace the two occurance of "viewname" with the viewmodel name, the name not the identifier.  For example
the SimpleTest viewmodel will have two divs
```html
<div id="simpletest-load-message"><span class="..." style="color:lightgrey"></span></div>
<div id="simpletest-view-container" style="display:none" class="..." >
```
Save in your chosen location as (ViewModel name).html. <br>Example: web.root/application/peanut/tests/view/SimpleTest.html

Now you can add your markup and data bindins inside the "-view-container" div.

## Defining the ViewModel class in TypeScript
To start, open the template file, web.root/packages/knockout_view/pnut/examples/viewmodel-template.txt

Replace "VmNameViewModel" with the class name of your view model. E.g "SimpleTestViewModel"

Replace "PackageName" with the appropriate name space for your view model.  For viewmodels in packages this corresponds 
to the directory name. Viewmodels in pnut/packages/qnut-directory have the namespace QnutDirectory. Viewmodels in
application/peanut use "Peanut" as the namespace.

Save the file to your chosen location as (Your ViewModel name) + "ViewModel.ts".  <br>Example:
web.root/application/peanut/tests/vm/SimpleTestViewModel.ts

Depending on the location you may need to correct the relative paths in reference path elements.

If your location is web.root/application/peanut/tests/vm.  The reference to ViewModelBase.ts is
```html
/// <reference path='../../../../packages/knockout_view/pnut/core/ViewModelBase.ts' />
```
In web.root/application/peanut/vm.
```html
/// <reference path='../../../packages/knockout_view/pnut/core/ViewModelBase.ts' />
```
In a package directory, e.g. web.root/packages/knockout_view/pnut/packages/qnut-directory/vm
```html
/// <reference path="../../../../pnut/core/ViewModelBase.ts" />
```
File names are case sensitive.
Correct:
```html
/// <reference path='../../../../pnut/core/Peanut.d.ts' />
```
Incorrect:
```html
/// <reference path='../../../../pnut/core/peanut.d.ts' />
```
If you have recently installed a type library using NPM you may not need to use a reference path. Just see if 
the TypeScript compiler or your IDE recognize the identifiers you use.

Now you can begin to implement the view model class. Start with defining knockout observables to match any databinding
you put in the view.  You don't have to initialize them yet but if you are testing any data-bind attribute in the view
must match an observable in the viewmodel class or an error will be raised.

Example:

In SimpleTest.html:
```html
<h2 data-bind="text:messageText">Not bound</h2>
```
Must have a corresponding observable defined in SimpleTestViewModel.ts
```typescript
    export class SimpleTestViewModel  extends Peanut.ViewModelBase {
    messageText =
        ko.observable('This is a simple test, just to make sure all the foundational MVVM stuff is working.');
    
}
```
The next step is to implement the init() method.  Here you will load any resources, libraries, components, styles and
execute any Service Commands you need to download initialization data.

## The ViewModel.init() function

The init() function is where you implement any custom code that must run
after all other page elements are loaded and before the view is displayed.
This can include variable and observable initialization and loading of any
additional JavaScript libraries, style sheets and other resources that
your view model depends on.

Often there will be a call to a service command to obtain initial data to
assign to the observables.

The init() function must finally call bindDefaultSection() to bind the 
observables to the view and successFuntions() which load subsequent view 
models if needed.

```typescript
  me.bindDefaultSection();
  if (successFunction) {
      successFunction();
  }
```
Often the init() function will have a series of loader function calls and
maybe a service command each of which will have an annoymous function that
executes on successfull completion. It might look something like
this:
```typescript
init(successFunction?: () => void) {
  let me = this;
  me.application.loadResources([..]),() => {
    me.application.loadComponents('..', () => {
      me.services.executeService('GetInitialData',null, 
              function(serviceResponse: Peanut.IServiceResponse) {
                if (serviceResponse.Result == Peanut.serviceResultSuccess) {
                  // assign observables
                  
                  // and finally ...
                  me.bindDefaultSection();
                  if (successFunction) {
                    successFunction();
                  }
                }
              }
      );
    });
  };
}
```
Note how the 'success' functions wait for each predecessor task to complete
and finally the binding and final success function takes place.

### Loader functions
The Peanut Application object provides a number of functions for use in initialization
that load components and resources. [[1]](#1-Component-loading)
 - loadResources: loads JavaScript files and libraries and css stylesheets
 - registerComponents: loads and registers Knockout components
 - loadComponents: loads component viewmodels for later registration (late bound components)

Each of these functions take as their first parameter a list of resource, either as an array of strings or
a comma delimited list in a single string. Example:
```typescript
me.application.registerComponents([
    '@pnut/month-lookup,',
    '@pnut/modal-confirm',
    '@pnut/pager',
    '@pnut/multi-select'
    ], () => {
        me.application.loadResources([
          '@lib:local/moment-js',
          '@pnut/ViewModelHelpers',
          '@lib:fullcalendar-js',
          '@lib:tinymce'
        ], () => {
            . . .
      });
    });    
```
Typically these resources references start with a token that begins with '@'.  These are used by the Peanut loader to 
locate the resource:

- '@pnut/' refers the the Peanut JavaScript source directory. 
  - In ConcreteCMS: web.root/packages/knockout_view/pnut/js
- '@pkg/' refers to the Peanut packages directory.<br>
  - In ConcreteCMS: web.root/packages/knockout_view/pnut/packages
- '@lib/' refers to an external or local Javascript library.
  - '@lib:local' refers to the application libraries location.<br>
    In ConcreteCMS: web.root/application/assets/js/libraries
  - External libraries (e.g. '@lib:tinymce') are resolved using the [libraries]
  section of the application/config/settings.ini file. These references can be a
  local path or a URL. A setting of 'installed' indicates that the library is
  already loaded by the CMS. Here is an example from austinquakers.org / ConcreteCMS:
```ini
[libraries]
tinymce='/application/assets/js/libraries/tinymce/js/tinymce/tinymce.min.js'
moment-js='moment/min/moment.min.js';
fullcalendar-js='https://cdn.jsdelivr.net/npm/fullcalendar/index.global.min.js';
fontawesome='installed'
```
<hr>
## Notes

### Component Loading
For usage examples of 'registerComponent' and late binding 
techniques with 'loadComponent' see the test page 'Component Test'
with source in: web.root/application/peanut/tests/vm/ComponentsTestViewModel.ts

See also: [Components Overview](components/components-overview.md)