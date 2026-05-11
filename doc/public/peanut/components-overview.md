[Return to docs home page](../../index.html)
# Components in Peanut

Knockout view models can be reused across pages on a web site.  Knockout 
components can be reused across view models and embedded in the view as 
a custom element.

See the [Knockout Documentation](https://knockoutjs.com/documentation/component-overview.html)

Here is the convention for component source using the example of the 
"Modal Confirm" component
- ViewModel in components/modalConfirmComponent.ts
- Markup in templates/modalConfirm.html
- Custom element name = modal-confirm. example:
```html
<modal-confirm params="id:'confirm-save-modal',
    headerText: 'Please confirm SAVE', bodyText: confirmText, 
    confirmClick: save"></modal-confirm>
```
See [Modal Confirm](modalconfirm.md) for details about this component.

For a full example see the test page for modals:
- web.root/application/peanut/tests/vm/ModalTestViewModel.ts
- web.root/application/peanut/tests/view/ModalTest.html

## Loading a component

To use a component, place the custom tag in your view.  And load it
in the init() funtion of the view model as in this example:

```typescript
    init(successFunction?: () => void) {
        console.log('Init ModalTest');
        let me = this;
        me.application.registerComponents(['@pnut/modal-confirm'], () => {
            me.bindDefaultSection();
            successFunction();
        });
    }
```
Note that the component name is preceded by a code indication the source location.
In this case '@pnut' is a 'path token' expands to web.root/packages/knockout_view/pnut.  
The viewmodel and html files would be found in 'component' and 'template'
subdirectories.

See [View Model Intialization](../view-model-init.md) for information 
about other path tokens.

## Creating a component
Creating a component is similar to creating a view model with some departures.

1. Decide on a component name. Example: 'my-widget'.  There is no need to list it in an ini file.
Loading code in the hosting view model takes care of finding and loading the component.
2. Use web.root/packages/knockout_view/pnut/examples/component-viewmodel.txt as a template.
3. Change the class name according to your component name, example:<br>
'my-widget' = 'myWidgetComponent'<br>
Note that our convention is to begin the class name in lowercase and camel case
the rest.
4. Find a location in web.root/application/peanut or one of the peanut packages directories
5. Create the view model and template files in the appropriate sub-directories,
Example:
- components/myWidgetComponent.ts
- templates/myWidget.html
6. In the constructor of the component class, retrive and assign the
attribute values. Example
```typescript
constructor(params : any) {
    let me = this;

    if (!params) {
        throw('Params not defined in modalConfirmComponent');
    }
    if (!params.name) {
        throw('Parameter "name" is required.')
    }
    me.name(params.name);
}
```
Check out the existing components for detailed examples.
