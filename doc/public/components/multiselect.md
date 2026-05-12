[Return to docs home page](../index.md)
# Multiselect Component
Type: knockout component

Supports user input of multiple associated values to an "array" field.

**Source:**: /knockout_view/pnut/components/multiSelectComponent.ts
**Template:**: /knockout_view/pnut/templates/multiSelect.html
**Example:** /application/peanut/tests/vm/TestPageViewModel.ts and /view/TestPage.html

### Markup Example

````html
<multi-select params="items:itemList, selected:selectedItems,sort:'code',
    translator:self, label:'Multiselect', caption:'Select something...'">
</multi-select>
````
### Parameters
- items: ***Required.***  : KnockoutObservableArray of ILookupItem;
- selected:  ***Required.*** : KnockoutObservableArray of ILookupItem;
- translator: A host class to perform translations.  Usually a view model
- label: May be literal, or translator code.  Default blank.
- caption: May be literal, or translator code. Default 'Please select...'
- sort: Value for sorting options, Default: 'name'

### Data
Data is in the form of ILookupItem[]
```typescript
    export interface ILookupItem {
        id : any;
        code: string;
        name: string;
        description : string;
    }
```
### Dependencies and Initialization
Initialized from a Peanut ViewModel init() function.
```typescript
// register the component
me.application.registerComponents('@pnut/multi-select', () => {
        
});
```
Lodash dependencies remove in SCYM version. 1/22/2022.  Older versions require lodash library to be loaded.
```typescript
me.application.loadResources(['@lib:lodash'], () => {
    
});
```
