[Return to docs home page](../index.md)
# JQuery Replacements
Most jQuery code should be replaced by pure javascript alternatives.
One exception in the jQuery Ajax library. Although future version my use the "fetch api",
For now we continue to use jQuery Ajax for service calls for backward compatibility.

In ConcreteCMS, this library is included. For other versions such as "Nutshell" add "ajax" 
the "dependencies" list in settings.ini to load the ajax library.
## ViewModalBase functions
These helper functions can be called from any viewmodel class
````typescript
        // replaces JQuery('#id').val();
        public getInputElementValue(id: string) {
     
        // replaces jQuery('#id').hide()
        public hideElement(id: string) {
        }

        // replaces jQuery('#id').show()
        public showElement(id: string, style = 'block') {
     
````

### Post form
```Javascript
document.querySelector("#test-form").setAttribute("action", url);
document.querySelector("#test-form").submit();
```