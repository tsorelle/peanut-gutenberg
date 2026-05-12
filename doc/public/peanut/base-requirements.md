[Return to docs home page](../index.md)
# Base Requirements for Latest Core

updated: 12/14/2025

## Core Libraries and third party components
These indicated the latest versions used in testing on this site as of update. 

### For base functonaility
- [Javascript](https://developer.mozilla.org/en-US/docs/Web/JavaScript/JavaScript_technologies_overview) (typescript target) ES6/ES2015
- [PHP](https://www.php.net/) 8.2.0
- [MySQL](https://www.mysql.com/) 8.0.31
- [Typescript](https://www.typescriptlang.org/) 5.4.3 (bundled with PHPStorm)
- [Knockout JS](https://www.typescriptlang.org/) 3.5.1
- [Head JS](https://headjs.github.io/) v1.0.3
- [JQuery](https://jquery.com/) 3.6.0  (jquery only used for Ajax calls)
- [Node JS](https://nodejs.org/en/) v20.11.0 (development only)

### For UI features
- [Bootstrap](https://getbootstrap.com/) 5.x
- [Fontawesome](https://fontawesome.com/) 6.x
- [SASS](https://sass-lang.com/) and [LESS](https://lesscss.org/). These are scripting languages used to produce
css files. SASS is used in ConcreteCMS 9 and is preferred.

JQuery Ajax and HeadJS are included in PEANUT

### Deprecated:
- LoDash or Underscore, use ES6 JavaScript for string and array functions.

## Technical Discussion

PEANUT for PHP/Typescript was originally developed around 2016 and used several
foundational libraries and techniques that have sense become "passe" but not
obsolete. Although they could be replaced by more modern alternatives, I believe
the core Peanut source should hold up well for the next five or more years.

Here I'll discuss these technologies and techniques and indicate why they were chosen
in the first place and suggest alternatives for future exploration.

## Knockout JS

https://knockoutjs.com/

Knockout JS is a JavaScript library that supports data binding between a viewmodel object
and an HTML fragment called the view. This is known as the MVVM (Model-View-Viewmodel) pattern.

It is very much at the heart of the Peanut framework.

Knockout has not been enhanced since 2019, and alternatives such as Angular JS and Vue may be
preferred for new projects.  Knockout has several virtues though.  It is lightweight and reliable.
And it is far easier to use than any of the other frameworks.  The syntax and technique is 
so different from the others that it would take considerable effort to replace in the existing code.

Alternatives: Vue.js (https://vuejs.org/) is widely used and well regarded. I'd recommend it if
a top-to-bottom redesign is considered.

## JQuery

Use of JQuery was dropped by Bootstrap and many of its core features are obviated in recent JavaScript
versions.  In Peanut we are using it for "AJAX" service calls (see pnut/core/Services.ts) and to
dynamically load HTML such as Knockout "views".
Theoretically it may be inefficient to use such a large library for such narrow purposes.  
But I have not notices any issues so it is hard to find a compelling reason to change.

Alternatives: [Fetch API](https://developer.mozilla.org/en-US/docs/Web/API/Fetch_API/Using_Fetch]) or 
[Axios](https://axios-http.com/docs/intro)

For more see [Best Ajax Alternatives](https://sourcebae.com/blog/what-are-the-best-alternatives-to-ajax-exploring-the-options/)

## JavaScript Modules and Loading

When Peanut was written the current most widely supported versions of JavaScript had standard to
support modules and no functionality for dynamic loading of them.

Accordingly we used [TypeScript "Namespaces"](https://axios-http.com/docs/intro) to define modules.
To load modules and other resources we wrote our own code using the (Head JS)[https://headjs.github.io/] to handle the task.

Since then, JavaScript introduced the "Import" and "Export" keywords to define modules. 
See [Modules](https://www.typescriptlang.org/docs/handbook/2/modules.html) in the TypeScript
manual.  Not that since we are not using import/export much, the "reference path" directive is 
often require and is used in all our TS files:
````typescript
/// <reference path="..." />
````

But JavaScript still lacks suffient support for ordered asynchronous loading. An a third party library or system
is needed. Currently [Webpack](https://webpack.js.org/) is the most popular solution.

Replacing our loader with WebPack would require considerable revision to nearly all our Typescript files and
it is not clear whether Webpack would serve out purposes.

To get an idea of how our loading system see pnut/core/PeanutLoader.ts. You may note that Head.js is only used
in two places and JQuery in another two.  If a preferred alternavive to this low level 
loading function can be found it would be easy to introduce. I'm not really sure one could improve
on head.js and JQuery may still be needed for Ajax calls.

To see how our loading routines work or a higher level, examine the init() function in any 
viewmodel TS file and look for application functions: loadStyleSheets and loadResources.
Note that these have a function parameter that executes on load success. This is how we
make sure that dependent modules are not loaded in the wrong order.

