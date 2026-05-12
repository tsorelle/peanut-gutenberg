[Return to docs home page](../index.md)
# Peanut UI Messages

Two kinds of messages are built into the system:

1. Alert messages - Inform the user of events with warning, error and information alerts. See Bootstrap alerts.
2. Wait message (or "waiters") - These appear in a banner at the top of the screen to tell the user that some process
    such as a service call is occuring. They should close when the process is done.

## Alerts

## Waiters
There are three kinds of waiters, more may be added by registering a new template in the uiHelpers (more later)

The are invoked by calling Peanut.WaitMessage.show() and closed with a call to Peanut.WaitMessage.hide().

The first optional parameter is the message to display, "Please wait..." is the default:
````typescript
 Peanut.WaitMessage.show(); // displays "Please wait..."
 Peanut.WaitMessage.show("Loading template..."); // displays "Loading template...";
````
The  wait message template is selected by supplying an identifier in the second parameter.  The "spin-waiter" template is the 
default. Since spin-waiter is the default, these two examples are equivalent to the ones above.
````typescript
 Peanut.WaitMessage.show("Loading template...",'spin-waiter'); // displays "Loading template..." with a spinner icon;
````
The text of the waiter can be changed in "real time" using the WaitMessage.setMessage function.  You might 
do this at various points in the process where it is under the control of the viewmodel.
Example:
````typescript
Peanut.WaitMessage.setMessage(message +': Count ' + count);
````

Two currently supported templates are "non-cancellable". They don't go away until the WaitMessage.hide() method
is called.  The 'spin-waiter' template displays an animated spinner icon.  The plain jane 'banner-waiter' has
text only, no icon.

Waiters may be cancelable by clicking the "Cancel button" in the upper right corner. <br>
Currently two templates, "progress-waiter" and "process-waiter" support this behavior.<br>
The "progress-waiter" template displays a progress bar where 'process-waiter' shows text only.

In order to make the cancellation actually work, the programmer must supply an event handler method
that does whatever is needed to interrupt the process and then calls the hide() method.

Example:
````typescript
    onProcessCancelled = () => {
        this.processCancelled = true;
        alert('Progress cancelled');
        Peanut.WaitMessage.hide();
    }

  let message = 'Doing something long running. '
  Peanut.WaitMessage.showProgressWaiter(message,this.onProcessCancelled);
````
In the case of "progress-waiter" a method is provided to set the length of the progress bar.

````typescript
    Peanut.WaitMessage.setProgress(count,true);
````
The optional second parameter determine if the percentage should be displayed as text as well.

The hide method should always be called to close the waiter message.
````typescript
    Peanut.WaitMessage.hide();
````
### Convenience methods

Several methods are provided in the ViewModel base class and can be used for simplicity
and leverage the Peanut translation system for internationalization.

These can be called in the context of any view model:

````typescript
// Show translated message
        protected showWaitMessage = (message = 'wait-action-loading',waiter: string = 'spin-waiter') => {
            let me = this;
            message = me.translate(message)+ '...';
            Peanut.WaitMessage.show(message,waiter);
        };

        protected showLoadWaiter =(message = 'wait-action-loading') => {
            let me = this;
            message = me.translate('wait-action-loading')+ ', ' + me.translate('wait-please')+'...';
            Peanut.WaitMessage.show(message,message)
        };

        // Assemble typical message like 'Updating mailbox, please wait...'
        protected getActionMessage = (action: string, entity: string) => {
            return this.translate('wait-action-'+action) + ' ' + this.translate(entity) + ', ' + this.translate('wait-please')+'...';
        };

        protected showActionWaiter = (action: string, entity: string,waiter: string = 'spin-waiter') => {
            let message = this.getActionMessage(action,entity);
            Peanut.WaitMessage.show(message,waiter);
        };
        
        protected hideWaiter() {
            WaitMessage.hide();
        }

````
### Deprecated methods

There are a few legacy methods that you may see in existing code. They still work, but the preferred 
method now is to use WaitMessage directly or use the ViewModelBase convenience methods.

These are the deprecated methods:
-  Peanut.MessageManager.instance.hideWaitMessage()
-  this.application.showWaiter()
-  this.application.showBannerWaiter()
-  this.application.hideWaiter()

The variable Peanut.MessageManager.waiterVisible has been removed.
            