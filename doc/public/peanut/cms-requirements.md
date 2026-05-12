[Return to docs home page](../index.md)

# Peanut Base Structure

In this document we want to describe the essential requirements, directory structure and files
that make up a Peanut installation on a CMS host.

**Terminology disambiguation:** The term "package" is used in ConcreteCMS to refer to installable modules,
located in the 'packages' directory, that implement themes, blocks and other add-on items.

Peanut also uses the term "package", it this case to refer to optional module for the peanut 
system.  Usually a collection of viewmodels and related PHP source for particular applications.
We'll refer to these respectively as "cms packages" and "peanut packages." 

Practically all CMS systems have such installable units called by different names. We refer to 
these generically as "modules" or "cms modules".

ConcreteCMS will refer to versions 8.0 and later or generically to ConcreteXXX.  
Concrete5 will refer to earlier versions.  

## Start up requirements

### Required JS scripts
The following scripts must be referenced at the top of the page.
- Knockout JS:<br>'https://cdnjs.cloudflare.com/ajax/libs/knockout/3.4.1/knockout-min.js'
- Head JS:<br>
  'https://cdnjs.cloudflare.com/ajax/libs/headjs/1.0.3/head.load.min.js',
- Peanut loader found in the peanut distribution directories (e.g. in a package or module)<br>
 'dist/loader.min.js' or 'core/PeanutLoader.js'<br>
  Note that the minified loader file has not been well tested.


## Startup Script
This statement must be used to generate the startup script in or below the footer area 

````php
print ViewModelManager::GetStartScript();
````
If the page contains a viewmodel, this outputs a script block like this:
````html
<script>
    Peanut.PeanutLoader.startApplication('FmaMailingLists#1351');
</script>
````
If multiple viewmodels are present:
````html
<script>
    Peanut.PeanutLoader.startApplication('@pkg/qnut-committees/CommitteeDescription#750', function() {
      Peanut.PeanutLoader.loadViewModel('@pkg/qnut-committees/CommitteeMembers#2986', function() {
        Peanut.PeanutLoader.loadViewModel('@pkg/qnut-documents/DocumentList#2987', function() {
          Peanut.PeanutLoader.loadViewModel('@pkg/qnut-calendar/Calendar#761');
        });
      });
    });
</script>
````
When no viewmodels are present on the page an empty string is output,
thus having no effect.

## ViewModel Loading converntions
# Naming and location
Viewmodels are is located in the application/peanut directory or in the "peanut package" directorys.<br>
The js file containing the ViewModel subclass is in a "/vm" subdirectory and the html file containing the "view" is
in the corresponding "/view" subdirtory

In all peanut host systems, configuration is specified for a viewmodel in a viewmodel.ini file as in this example:
````ini
[committees]
vm=Committees
roles=authenticated
heading='committee-list-heading'

[committee-sidebar]
vm=CommitteeSidebar

[committee-description]
vm=CommitteeDescription
````
One viewmodels.ini file may be located in either the application/config directory. Note that all
peanut host systems must have an "application" directory, not just ConcreteCMS.

Other viewmodels.ini are located in "config" subdirectory of each "peanut package" (not to be confused with ConcreteCMS packages).

The code example above comes from:<br>
pnut/packages/qnut-committees/config/viewmodels.ini

## Other configuration files

Peanut configuration files are located either in "peanut package" directories or application/config.
Those located in application/config include:
- settings.ini: general settings that may be deployment specfic.
- database.php: Database connection information.  This format conforms to ConcreteCMS but is used accross peanut implementations.
- classes.ini: used by the Peanut object container system (see below)
- mailgun.ini: Connection information for the Mailgun mailing service. IMPORTANT: this file
should never be posted in a public location such as a public git repository.

## Peanut paths
ViewModels use a convention to referece files and resources loaded in the init() function
and elsewhere. These files are loaded using application.loadResources, application.registerComponent 
PeanutLoader.loadViewModel and other load funtions. See KnockoutHelper.ts in core.<br>
E.g.<br>
````typescript
application.loadResources(
    [
        '@pnut/ViewModelHelpers',
        '@pnut/editPanel',
        '@pnut/searchListObservable',
````    
The prefixes beginning with "@" are resolved as follows:
- @lib: js library directory 
- @pnut: peanut core root path + "styles" or application + "assets/styles/pnut"
- @pkg: peanut packages directory.

### vendor directory

This is created by the 'composer' package manager and contains third party PHP code use by
peanut. It is located above the main document root and the location is specified in settings.ini.

The items contained here should not duplicate those in the vendor file of the CMS. So for 
various implementations we need to take account of what libraries are included with the CMS.

### assets directory
The application/assets directory contains javascript libraries and other item used by peanut,
but not necessarily included in the CMS.  In loading routines such as 'application.loadResources()'
the prefix "@lib:" resolves to this location.


## Applications

Currently Peanut is available for two CMS Systems: ConcreteCMS and Nutshell (by Terry SoRelle).

In the past we have offered versions Drupal and Wordpress.  These are not currently supported but could be revived
if needed.

For detail ConcreteCMS documentation see the section devoted to the ConcreteCMS specific section.  All documents
if this section (Peanut) apply to the other implementations as well.
