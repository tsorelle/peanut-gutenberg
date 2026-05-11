[Return to docs home page](../../index.html)
# TinyMce with htmlEditContainer

HtmlEditContainer is a Typescript class designed as a facade to simplify initialization and control of the TinyMce editor.
The TinyMce library is used to present a WYSIWYG editor for email and content. Email composition is the
primary use in our application.

## Initialization

Initialization sequence is important with TinyMce.  For instance unexpected behavior can occur if the editor
is used before it is fully initialized.  For that reason, it is important to define a startup function that
executes after initialization so that you do not present the editor for use prematurely.

HtmlEditContainer offers to initialization methods.

```typescript
       initialize = (selector: string, onInitialized?: () => void);
```
Selector is the id of the textarea where the editor appears. The optional onInitialized function
is the function the executes after initialization is complete.

This method is usually a safe bet to use in the init() method of a view model.  The constructor is
takes an instance of view model or a knockout component.

```typescript
    let me = this;
    me.application.loadResources([
        '@pnut/htmlEditContainer'
    ], () => {
        // create editor instance            
        me.htmlEditor =  new Peanut.htmlEditContainer(me);
        // set uptions
        me.htmlEditor.addOptions({height: '50ex'})
        me.htmlEditor.includeDesignTools();
        // initialize
        me.htmlEditor.initialize('test-editor', () => {
            me.bindDefaultSection();
            successFunction();
        })
    });
```
The htmlEditContainer.initialize() function takes care of loading TinyMce script dependencies and ensures the editor is not used
until after the view model is presented.  Another option is to load the script dependencies and instatiate the 
htmlEditContainer in the view model init() method and then call htmlEditContainer.initEditor() whenever 
you are ready to initialize the editor. In this case it is not necessary to pass the view model reference 
to the constructor.

```typescript
me.application.loadResources([
    '@lib:tinymce',
    '@pnut/ViewModelHelpers.js',
    '@pnut/htmlEditContainer'
], () => {
    me.htmlEditor = new Peanut.htmlEditContainer();
    me.htmlEditor.includeDesignTools();
    me.htmlEditor.initEditor('messagehtml',() => {
        me.bindDefaultSection();
        successFunction();
    })
});
```

In certain circumstances we need to delay initialization until first use of the editor. We found this to be the case
in one instance where the editor was presented in a dialog box and invoked in the context of a service response 
handler. It seems that trouble can occur if text assignment does not occur in the same context as the initialization.

The following example is taken for the implementation of a knockout component.  In the component initialization
we instantiate the htmlEditContainer.

```typescript
    me.application.loadResources([
        '@pnut/htmlEditContainer'
    ], () => {
        me.htmlEditor =  new Peanut.htmlEditContainer(me.owner);
        
        // finish component initialization
 
    });
```

Then in the service response we check for initialization and display the editor.

```typescript
    let me = this;
    if (serviceResponse.Result == Peanut.serviceResultSuccess) {
        if (me.htmlEditor.editorInitialized) {
            me.htmlEditor.setContent(serviceResponse.Value);
            me.showModal('editorModal')
        }
        else {
            me.htmlEditor.addOptions({height: '25em'})
            this.htmlEditor.initialize('confirmation-editor', () => {
                me.htmlEditor.setContent(serviceResponse.Value);
                me.showModal('editorModal')
            });
        }
    }
```
## Tool Bar Options

The editor offer two built-in configurations, and a means to declare custom options.

By default a simple tool bar is presented:

![tinymce-simple.jpg](../img/tinymce-simple.jpg)

Use this method to display a richer set of tool bar options:

```typescript
me.htmlEditor.includeDesignTools();
```

![tinymce-design-tools.jpg](../img/tinymce-design-tools.jpg)

There are additional methods you can used to show particular tools.

## Editor configuration and other options

By default, the editor is configured for use in email. You can change to a configuration
that favors content editing by using the configureForContentEditing() method.

```typescript
me.htmlEditor.configureForContentEditing();
```

The difference is the when configured for email, URLs for links and images are expanded to the full URL
including the protocol ('https://).  When the alternative expands to the document root with no
protocol.

And for most flexible configuration you can use the addOptions method to add or overwrite 
any TinyMce initialization options.  This is frequently used to set the height of the editor:

```typescript
 me.htmlEditor.addOptions({height: '50ex'})
```

For a full list and documentation of other options see:
[TinyMce Documentation](https://www.tiny.cloud/docs/tinymce/5/configure/)

## TinyMce inside Bootstrap 5 Modal

Inside a Bootstrap 5 modal, dialog windows such as the 'link' dialog mysterious fail to allow editing. This issue 
occurs due to Bootstrap's enforceFocus behavior, which prevents elements outside the modal from gaining focus. 
Since TinyMCE's link dialog is technically a separate modal, Bootstrap interferes with its functionality.

There are two steps to fix this.

First disable ensureFocus by adding the 'data-bs-focus="false"' attribute to the top level modal element.
```html
<div class="modal" id="send-message-modal" data-bs-focus="false" >
```
Since this automatic focus is now disabled, you need to "manually" focus an element when the dialog is shown. 
And your version of this event handler before the dialog box is shown.
```typescript
    document.getElementById('send-message-modal').addEventListener('shown.bs.modal', function () {
        let subjectField = document.getElementById('subject');
        if (subjectField) {
            subjectField.focus();
        }
    });
```



## Images and ConcreteCMS

In ConcreteCMS, the file manager can be used effectively with TinyMce to compose email with
images.  See a full description of the technique here:

[Email Images with Concrete CMS](../pdf/email-images.pdf)
