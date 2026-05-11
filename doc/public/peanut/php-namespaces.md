# PHP Namespaces

On startup, Peanut uses initializes an autoloader per the Psr-4 [[1]](#1-autoloading) standard to map namespaces 
to directories. Mapping starts with the HTML document root and builds  
a set of paths that are mapped to their respective PHP namespace roots.

Peanut packages [[3]](#3-peanut-packages) are mapped to a root namespace matchine the directory name,
The directory name is lowercase with '-' separators. For the namespace these
are converted to camel case, "peanut-tasks" > "PeanutTasks".

These example paths are from the ConcreteCMS version.

Two root paths are defined:
- module-root: web.root/packages/knockout_view
- application-root: web.root/application/src

Then concatenated to determine sub-directories
- peanut-root-path: (module-root)/pnut
- peanut-php-source: (module-root)/src/peanut
- peanut-packages [[3]](#3-peanut-packages): (peanut-root-path)/packages
- tops-source [[2]](#2-tops-php-library): (module-root)/src/tops

Using these variables, top level namespaces are mapped as follows:

| Namespace                | Base path in ConcreteCMS                                    |
| ------------------------ |-------------------------------------------------------------|
| Tops 				   | packages/knockout_view/src/tops                             |
| Peanut 				   | packages/knockout_view/src/peanut                           |
| Application             | application/src
| PeanutTest		       | packages/knockout_view/src/test                             |

Some concrete examples are:

| Namespace                  | Path in ConcreteCMS                             |
|----------------------------|-------------------------------------------------|
| Tops\db\model\entity       | packages/knockout_view/src/tops/db/model/entity |
| Peanut\services            | packages/knockout_view/src/peanut/services      |
| Application\fma\services 	 | application/src/fma                          |
| PeanutTest\scripts		       | packages/knockout_view/src/test/scripts    |


Peanut packages are mapped using a package name derived from a dash seperated directory name which is
joined and camel cased. E.g.

- mailboxes = Mailboxes
- peanut-tasks = PeanutTasks

The formula for mapping directory location is:

| Namespace                 | Path in ConcreteCMS                                          |
|---------------------------|--------------------------------------------------------------|
| Peanut\(package name)   	 | packages/knockout_view/pnut/packages/(package directory)/src |

Some concrete examples are:

| Namespace                | Path in ConcreteCMS                                         |
| ------------------------ |-------------------------------------------------------------|
| Peanut\Malboxes   	   | packages/knockout_view/pnut/packages/mailboxes/src          |
| Peanut\PeanutPermissions | packages/knockout_view/pnut/packages/peanut-permissions/src |
| Peanut\PeanutRiddler     | packages/knockout_view/pnut/packages/peanut-riddler/src     |
| Peanut\PeanutTasks       | packages/knockout_view/pnut/packages/peanut-tasks/src       |
| Peanut\QnutCalendar      | packages/knockout_view/pnut/packages/qnut-calendar/src      |
| Peanut\QnutCommittees    | packages/knockout_view/pnut/packages/qnut-committees/src    |
| Peanut\QnutDirectory     | packages/knockout_view/pnut/packages/qnut-directory/src     |
| Peanut\QnutDocuments     | packages/knockout_view/pnut/packages/qnut-documents/src     |
| Peanut\QnutUsergroups    | packages/knockout_view/pnut/packages/qnut-usergroups/src    |

Namespaces to classes in sub-directories to the base paths have namespaces corresponding to the 
subdirectory name. Examples:
- PeanutTest\scripts in packages/knockout_view/src/test
- Application\fma\services in application/src/fma/services
- Peanut\QnutDirectory\db\model\repository in 
  - packages/knockout_view/pnut/packages/qnut-directory/src/db/model/repository

<hr>

### Footnotes:

#### 1-Autoloading

Autoloading is a PHP feature causing class files to be located by namespace and class name and
included without an explicit "include" or "require" statement.
Psr-4 is a standard for autoloaders established by the [PHP Framework Interop Group](https://www.php-fig.org/psr/psr-4/)

#### 2-Tops PHP Library

TOPS is a general library of PHP classes by Terry SoRelle