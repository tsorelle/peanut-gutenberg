[Return to docs home page](../index.md)
# searchListObservable
Type: Observable container class.

A class for supporting presentation of a UI component allowing a pageable selection list based on a query selection result.

Source code:
```
/pnut/js/searchListObservable.ts
```
##Examples
Usage example, within a Knockout component:
````
/pnut/packages/qnut-directory/components/personSelectorComponent.ts
````
Usage within a ViewModel:
````
/pnut/packages/qnut-directory/vm/DirectoryViewModel.ts
````
## Initialization
```typescript
           me.application.loadResources([
                    '@pnut/ViewModelHelpers',
                    '@pnut/editPanel',
                    '@pnut/searchListObservable',
                ], () => {
               
                });

```
##Constructor
###Example:
```typescript
me.personsList = new Peanut.searchListObservable(2, 12);
```
####Parameters:
- columnCount: Number of columns used to display result links
- maxInColumn: Maximum links in each column
#### Markup example

Note references to personList methods and observables. Also the use of $parent to reference the owning view model.

```html
 <div id="findPersonResults"  class="row found well" data-bind="visible:personsList.selectionCount">
    <div id="persons-found-header" class="row">
        <div class="col-md-12">
            <translate params="code:'label-found',translator:self"></translate>: <span data-bind="text: personsList.selectionCount"></span> <translate params="code:'dir-person-entity-plural',translator:self"></translate>.
        </div>

    </div>
    <div id="persons-found-nav" class="row">
        <div class="col-md-6" style="padding-right: 3px" data-bind="visible:personsList.hasPrevious">
            <a href="#"  data-bind="click:personsList.previousPage"><span class="fa fa-backward"></span> <translate params="code:'nav-previous',translator:self"></translate></a>
        </div>
        <div class="col-md-6" style="padding-right: 3px" data-bind="visible:personsList.hasMore">
            <a href="#" data-bind="click:personsList.nextPage"><translate params="code:'nav-more',translator:self"></translate>&nbsp;<span class="fa fa-forward"></span></a>
        </div>
    </div> <!-- end found header -->

    <div class="row" id="persons-found-list">
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" data-bind="foreach: personsList.selectionList[1]">
            <div class="link-list-item">
                <a href="#" data-bind="click:$parent.addPersonToAddress"><span data-bind="text: Name"></span></a>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" data-bind="foreach: personsList.selectionList[2]">
            <div class="link-list-item">
                <a href="#" data-bind="click:$parent.addPersonToAddress"><span data-bind="text: Name"></span></a>
            </div>
        </div>
    </div> <!-- end found list -->

</div>

```
#### Methods
These methods and observables are typically used in the owning view model:
- setList(list : INameValuePair[]) - assign the search result list
- reset() - clear the search result list
- searchValue :  ko.observable(''); - extract the selected value (selectedItem.Value)

##Incremental Search Select Component

Type: Model/View Pattern

#### Example:
Extracted from:
````
/pnut/packages/qnut-directory/view/Directory.html
````
This example present a searchable selection list is presented in order for the user to select multiple affiliation values.

```html
<div class="form-group">
    <div>
        <span style="font-weight: bold"><translate params="code:'dir-label-organization',translator:self"></translate>: </span>
    </div>
    <div class="input-group" style="margin-bottom: 10px">
        <input class="form-control" id="orgsearch" type="search" name="search" placeholder="Select or search for an organization"
               data-bind="value: personForm.orgSearchValue, valueUpdate: 'keyup'" autocomplete="off" />
        <span class="input-group-btn"><button class="btn btn-default btn-outline-secondary" type="button" data-bind="click:personForm.onShowOrgList">
			<i data-bind="visible:personForm.orgListVisible" class="fa fa-caret-up"></i>
			<i data-bind="visible:!personForm.orgListVisible()"class="fa fa-caret-down"></i>
		</button></span>
    </div>
    <div style="position:absolute; z-index: 2; clear: both;background-color: white; max-height: 25ex; overflow: scroll; border: 1px solid lightgrey; padding: 10px"
         data-bind="visible:personForm.orgListVisible">
        <table class="table table-bordered" >
            <tbody data-bind="foreach:personForm.orgLookupList">
            <tr>
                <td data-bind="text:name"></td>
                <td><a href="#" data-bind="click:$parent.personForm.onOrgSelect">
                    <translate params="code:'label-select',translator:$parent.self"></translate></a> </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

```
Note the data-bind prameters to the input control:
````html
data-bind="value: personForm.orgSearchValue, valueUpdate: 'keyup'" autocomplete="off"
````
The view model code is in the observable container class  personObservable:
````
/pnut/packages/qnut-directory/js/PersonObservable.ts
````
Here the orgSearchValue is subscribed to a method that filters the list, orgLookupList, referenced in the table below.
```typescript
onOrgSearchChange = (value: string) => {
	let me = this;
	if (value) {
		me.selectedOrganization(null);
		me.orgLookupList([]);
		value = value.toLowerCase();
		let newlist = me.organizations.filter((org: Peanut.ILookupItem) => {
			return (org.name.toLowerCase().indexOf(value) >= 0);
		});
		me.orgLookupList(newlist);
		me.orgListVisible(newlist.length > 0);
	}
	else {
		me.orgLookupList(me.organizations);
	}
};
```
The "valueUpdate: 'keyup'" binding causes orgSearchValue() to be updated as the user types, thus triggering
the onOrgSearchChange() method to refilter the list on every key stroke.

Also note the styles on the DIV element containing the table which position the selection list as a drop-down.
