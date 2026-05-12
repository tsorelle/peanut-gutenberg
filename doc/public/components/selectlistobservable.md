[Return to docs home page](../index.md)
# selectListObservable
Type: knockout component

Encapsulates select list handling for INameValue[]
## Initialize
In init() function
````typescript
me.application.loadResources([
'@pnut/selectListObservable'
], () => {
  
});
````
## Constructor
```typescript
me.taskEditForm.intervalType= new Peanut.selectListObservable(
    // handler - null if no handler
	me.onIntervalTypeChange,  
	// selection array
	[
          {name: 'On demand', id: 1},
          {name: 'Regular interval', id: 2},
          {name: 'Weeky', id: 3},
          {name: 'Daily', id: 4},
          {name: 'Fixed time', id: 5}
        ],
	// initial default value 
    1 );
```
## Handler
````typescript
        onIntervalTypeChange = (type: ILookupItem) => {
            let me = this;
            me.taskEditForm.time('');
        };
````

HTML in view example
```html
<label for="document-types">Document type</label>
<select class="form-control form-select"  id="document-types"
   data-bind = "options: documentType.options, optionsText:'Name', value:documentType.selected"
></select>
```

for selectlist with handlers you need to call subscrible and unsubscribe
````typescript
this.taskEditForm.intervalType.unsubscribe();
if (value) {
	this.taskEditForm.intervalType.setValue(value);
}
else {
	this.taskEditForm.intervalType.setDefaultValue();
}
this.taskEditForm.intervalType.subscribe();
````
If list is of type ILookupItem[] use assignLookupList:
```typescript
me.sessiontypeSelector.assignLookupList(response.sessionTypes);
```
