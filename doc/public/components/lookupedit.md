[Return to docs home page](../index.md)
# lookupEditComponent
This component allows the a data management page for any entity that supports the ILookupItem interface.

## Markup example

## Parameters
Required:
- items: observable array
- onUpdate:  (item: ILookupItem) => void;

Optional:
- name: string - item name
- plural: string - plural version of name if needed
- canEdit: observable<boolean> or 'yes' - defaults to false
- translator: self - not supported at this time.

## Implementation
The view contains only the component tag and parameters.

Example:
```html
<div id="quarterlymeeting-load-message"><span class="fa fa-spinner fa-pulse fa-2x" style="color:lightgrey"></span></div>
<div id="quarterlymeeting-view-container" class="row" style="display: none">
    <lookup-edit params="
        items:quarterlies,
        onUpdate:updateQuarterly,
        name:'quarterly',
        plural:'quarterlies',
        canEdit:userCanEdit">
    </lookup-edit>
</div>

```


