[Return to docs home page](../index.md)
# modal-confirm component
## Example markup:
````html
<modal-confirm params="id:'confirm-save-modal',headerText: 'Please confirm SAVE', bodyText: confirmText, confirmClick: save"></modal-confirm>
<modal-confirm params="id:'confirm-delete-modal',headerText: 'Please confirm', bodyText: confirmText, confirmClick: confirm"></modal-confirm>
````
Text parameters (bodyText, headerText) can be either a string (in quotes) or an observable (no quotes)

## Example handling
````typescript
showSaveModal = () => {
    this.showModal('#confirm-save-modal')
    // or Peanut.ui.helper.showModal(modal);
};

save = () => {
    this.hideModal('#confirm-save-modal')
    // or Peanut.ui.helper.showModal(modal);
    alert('you saved');
};
````
## Example initialization

```typescript
init(applicationPath: string, successFunction?: () => void) {
    me.application.initialize(applicationPath,
    function() {
    me.application.registerComponent('@pnut/modal-confirm', () => {
    ...
});
```

## Parameters:
- Id: the element id of the modal used to show or hide it:
- confirmClick: Event handler for the "ok" or "yes" button
- headerText:  Text to appear in the modal header area.
- bodyText: Text to appear in the body area.
- buttonSet: a string indicating how to label the buttons:
  - 'alert' : one button labeled 'Continue'
  - 'yesno' : two buttons labeled 'Yes' and 'No'
  - 'okcancel' : two buttons labelsed 'Ok' and 'Cancel'
  - 'your-ok-button||your-cancel-button' : uses custom labeling.