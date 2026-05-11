[Return to docs home page](../index.md)
# updates for bootstrap 5

This versions of Peanut/Qnut depends on BootStrap ver 5.  Here are some notes about converting from 
Bootstrap 3, which was used previousl

## Modals

1. update modal-header<br>
    For BS-5 or BS-4 title goes before close button. Any button that
automatically closes a model shoud have: data-bs-dismiss="modal". 
Remove any X characters. This is handled in the styling.
````html
    <h4 class="modal-title">Title Here</h4>
    <button type="button" class="close btn-close"
         data-bs-dismiss="modal"
         aria-label="Close">
    </button>
````
   In the current php 7 version (2024-12-12) Austinquakers.org site, which is kind of a mix between
   Bootstrap 3 and 5 use this pattern. 
   The button preceeds the title and the X character is used.
````
   <button type="button" 
      class="close btn-close"
      data-bs-dismiss="modal"
      aria-label="Close">
         &times;
      </button>
   <h4 class="modal-title">TITLE GOES HERE!</h4>
````

2. UPDATE close button in footer, add this attribute to link: data-bs-dismiss="modal"

       <a href="#" data-bs-dismiss="modal" data-dismiss="modal">Close</a>

3. For wide modal use modal-lg class:

       <div class="modal-dialog modal-lg" >

## Forms
### Form Classes
Add 'form-select' class to select element:

     <select class="form-select">

Replace 'form-group' with 'form-group mb-3'>

    <div class='form-group mb-3'>

Class form-group is not defined in Bootstrap-5, mb-3 is defined in v.5 but not in previous versions. It added .5 rem to bottom margin.
For list of new generic spacing classes see:
https://getbootstrap.com/docs/5.0/utilities/spacing/
### Checkbox
Version 3:
````html
<div class="checkbox">
   <label >
      <input type="checkbox" > Checkbox label
   </label>
</div>
````
Version 5
````html
<div class="form-check">
  <input class="form-check-input" type="checkbox" value="" id="checkboxfield">
  <label class="form-check-label" for="checkboxfield">
     Checkbox label
  </label>
</div>
````
### Radio Buttons
Same as checkbox except type="radio" on input.
Note seperate div for each button.
```html
<div class="form-check">
  <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
  <label class="form-check-label" for="flexRadioDefault1">
    Default radio
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2" checked>
  <label class="form-check-label" for="flexRadioDefault2">
    Default checked radio
  </label>
</div>
````
### Classes
- pull-left > float-start
- pull-right > float-end

**Forms and Tables**
- form-group: form-group mb-3
- data-dismiss: data-bs-dismiss<br.>
  (any bootstrap "data-" should be changed to "data-bs". Careful, dont change "data-bind")
- btn-default:  btn-outline-primary
- text-right: text-end

**Replace bootstrap display classes:**
- hidden-print = d-print-none
- hidden-xs = d-none d-sm-block
- visible-print-block =  d-none d-print-block
- hidden-md hidden-sm hidden-lg = d-block d-sm-none

### Tabs
Tab styling changed a lot.
1. The "nav-item" class must be applied on all "li" elements withing the "nav nav-tabs" "ul"
2. The "nav-link" class must be applied to the "a" (link) elements
3. The "active" class must be applied to the active "a" (link) element. Not the "li" element as previously done. 
```html
   <ul class="nav nav-tabs">
       <li  class="nav-item" >
           <a class="nav-link"  href="#" data-bind="click:setSearchTypeInfo,
               css: { active: searchType() == 'info'}"
           >Document Information Search</a> </li>
       <li  class="nav-item">
           <a class="nav-link"  href="#"  data-bind="click:setSearchTypeText,
                css: { active: searchType() == 'text' }">
               Full Text Search
           </a> </li>
       <li class="nav-item">
           <a class="nav-link" href="#"  data-bind="click:setSearchTypeLookup
                ,css: { active: searchType() == 'lookup' }">
               Document Lookup
           </a> </li>
   </ul>
```

### Date Picker Popup
There is no date component in Bootstrap 5. Previously used jQueryUI.  
As an alternative we can use input type=date or type=datetime-local.<br><br>
Note that unlike the jQueryUI datepicker, the value of a type=date input
must be in ISO format.
- This works: 
  - '2001-09-02'
- These do not work:
  - '2001-9-2'
  - '9/2/2001'
  - '9/2/01'

````html
<label for="birthdaytime">Birthday (date and time):</label>
<input type="datetime-local" id="birthdaytime" name="birthdaytime">

<label for="birthday">Birthday:</label>
<input type="date" id="birthday" name="birthday">

<!-- knockout example  -->
<input class="form-control" type="date" id="startdate-input" data-bind="value:taskEditForm.startdate"/>
````
For existing markup, just add the type attribute (date or datetime-local) to the tag.

```html
<label for="endDate" class="form-label">End Date</label>
<input type="date" id="endDate" class="form-control datepopup" data-bind="value:sessionForm.endDate, css: {'is-invalid': sessionForm.endDateInvalid}"/>
```
### Dropdown Menus
In Bootstrap 5 each link in the li elements must have class="dropdown-item". 
Also the data-toggle attribut must be data-bs-toggle
````html
<div class="dropdown">
  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" 
          data-bs-toggle="dropdown" aria-expanded="false">
    Dropdown button
  </button>
  <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
    <li><a class="dropdown-item" href="#">Action</a></li>
    <li><a class="dropdown-item" href="#">Another action</a></li>
    <li><a class="dropdown-item" href="#">Something else here</a></li>
  </ul>
</div>
````

### Popovers

#### Initialization
In ViewModel.Init()
```typescript
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl)
    })
```
or Javascript:
````Javascript
const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]')
const popoverList = [...popoverTriggerList].map(
        popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl))
````
#### Markup Example
Change all data-toggle to data-bs-toggle, data-trigger to data-bs-trigger, data-content, data-bs-content
```html
  <a tabindex="0" role="button" data-bs-toggle="popover" data-bs-trigger="focus" title="Contacts Fields"
     data-bs-content="firstname, lastname, fullname, phone1, phone2, homephone, email, dateofbirth, 
        addressname, address1, address2, city, state, postalcode, 
        country, affiliation code, role, primary affiliation">Fields...</a>

```
