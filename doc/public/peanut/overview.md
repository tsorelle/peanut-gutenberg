[Return to docs home page](../../index.md)
# Peanut UI Components
Notes from SCYM project may apply to or be adapted in the FMA project.

## Component types

We use "component" here to mean any Typescript/HTML code that can be reused across peanut ViewModel/Views.  They include:
- Knockout Components
- Observable container classes
- Model/View Patterns

### Knockout Components
Based on Knockout js component model. Like a viewmodel, has a view model class match with an HTML templated.  Peanut has special support for dynamic loading of thes components.

Components models are stored in a 'components' and named by convention:
````
componentName + 'Component.ts
e.g. personsSelectorComponent.ts
````
Templates are store in a 'templates' directory parallel to the components directory and named by convention:
````
componentName + '.html'
````
Here's a template for the component viewmodel:
```typescript
/// <reference path='../../typings/knockout/knockout.d.ts' />
namespace Peanut {
    export class componentnameComponent {
        // observables

        // variables.


        // include constructor if any params used
        constructor(params: any) {
            let me = this;

            if (!params) {
                console.error('componentnameComponent: Params not defined in translateComponent');
                return;
            }
            // initialize observavles and variables from params
            /* Example:
                if (!params.parameterName) {
                    console.error('componentnameComponent: Parameter "parameterName" is required');
                    return;
                }
            */

        }
    }
}
```
Component templates are an ordinary HTML fragment with data bindings to the component view model.

#### Initialization
Components are registered or loaded in the ViewModel.init() function using the application.registerComponent method.
```typescript
me.application.registerComponents('@pnut/modal-confirm', () => {
    
});
```
Sometimes the view model needs some control over the component, for instance to load or clear data.  In this case
late binding can be used so that the view model may create the component vm and keep the reference.
In this technique, the application.loadComponent() method is used to reference the component source and
templalte then registerComponent is called passign in an instance of the model.
```typescript
me.application.loadComponents('tests/message-constructor', () => {
    let cvm = new messageConstructorComponent('Smoke Test Buttons:');
    me.application.registerComponent('tests/message-constructor', cvm, () => {
        me.bindDefaultSection();
        successFunction();
    });
});

```
### Comunication with ViewModel
The component can communicate with the owning ViewModel using one or more of these techniques.
1. Pass a reference to the view model using a parameter in the markup with the value 'self'.  Often used to provide a translator object.
   as in the case of translateComponent.
2. Pass a reverence to a method on the view model. See pagerComponent for an example.
3. Use the constructor in the case of a late bound component like 'personSelectorComponent'.

####Markup examples:
```html
<translate params="code:'form-legend',translator:self"></translate>
<pager params='click:onPagerClick,page:currentPage,max:maxPages,waiter:refreshing,owner:self'></pager>
```

#### ViewModel event handlers

The handle event method is typically used for communication between objects such as knockout components.  An evant handler
method is defined in the interface IEventHandle (peanut.d.ts)
```typescript
    export interface IEventSubscriber {
        handleEvent : (eventName: string, data?: any) => void;
    }
```

ViewModelBase implements IEventSubscriber with an empty method, so overiding is optional and calls to handleEvent
have not effect if not overriden in the concrete view model class.

A reference to the view model may be passed using the constructor or in the markup, and implemented in view model like this:

```typescript
handleEvent = (eventName:string, data?:any)=> {
    let me = this;
    switch (eventName) {
        case 'person-selected' :
            me.newTerm(<INameValuePair>data.person);
            break;

        case 'person-search-cancelled' :
            // alert('Search cancelled.');
            break;
        case 'test' :
            alert('event test');
            break;
    }
};
```

```typescript
selectPerson = (personItem:INameValuePair)=> {
    var me = this;
    me.hide();
    if (me.owner) {
        me.owner.handleEvent('person-selected',
            {person: personItem, modalId: me.
              Id()}
        )
    }
};
```


### Observable container classes
These classes encapsulate a set of observables and may be used in different view models. Implementation of the view is done in the specific ViewModel/View that hosts the instance of this class.

#### Usages
Often used for forms.

Class example:
````
/pnut/packages/qnut-directory/js/PersonObservable.ts
````
Usage example:
````
/pnut/packages/qnut-directory/vm/UserProfileViewModel.ts
````

Or used for special ui components.
Class example:
````
/pnut/js/searchListObservable.ts
````

Usage example:
````
/pnut/packages/qnut-directory/js/DirectoryEntities.ts
````
### Model/View Patterns
These are simply code fragments that may be extracted from an existing ViewModel and View and adapted for use elsewhere.

They may be suitable for later refactoring to a component or reusable class.
