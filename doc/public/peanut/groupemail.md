[Return to docs home page](../../index.md)
# Group Email Component
The Group EMail component is used to present a message editor as part of a page routine to send email messages to a
small group such as a committee, comunity group or distribution list. Additionally the component may present a list
of recipient email addresses which can be used if the user wants to send a message from their own email program.

For a detailed explanation of usage, see the [user help document](https://austinquakers.org/qnut/documents/1825)

## Interface
The host vm must implement an interface consisting of a function and five Knockout ovservables, that matches this 
definition in the component.  This demonstrates a technique we often use to permit communication between the view model
and an early-bound component.

```typescript
    interface IGroupListOwner extends IViewModel {
        sendMemberMessages: (content: string, subject: string) => void;
        messageModalVisible: KnockoutObservable<boolean>;
        canShowEmails: KnockoutObservable<boolean>;
        emailsText: KnockoutObservable<string>;
        clearMessagesForm: KnockoutObservable<boolean>;
        mailFormRecipients: KnockoutObservable<string>;
    }
```

This set of observables and the function is accessed by the component through the "self" parameter that passes a 
reference to the host vm to the component. The component tag in the host view looks like this.

```html
<group-email params="owner:self"></group-email>
```
The "self" parameter is actually a function in the view model base class that returns a reference to itself.
This technique is used widely in our components.  The purpose of each interface item is as follows:

- messageModalVisible: when set to true this triggers display of the dialog box. 
- canShowEmails: when set to false the display of email addresses is hidden.
- emailsText is a "\n" seperated list of addresses that will receive the mesage, in the form "Name <email address>"
- mailFormRecipients is the name of the group, to be displayed in the modal title.
- sendMemberMessages is a function called with the user clicks "Send"
- clearMessagesForm is set to true signaling the component to clear the form when it is next displayed.

## Example: 

Some code, not relevant to our discussion, has been elided "// ...". 

### In the declaration section at the top of the class
```typescript
        clearMessagesForm = ko.observable(false);
        emailsText = ko.observable('');
        messageModalVisible = ko.observable(false);
        mailFormRecipients = ko.observable('Members');
        canShowEmails = ko.observable(true);
```
### On getAddresses

The getAddresses function is called when the user selects a distribution list.  The addresses are assigned on
a successful invocation of the "GetDistributionListEmails" service and the group message form is displayed

```typescript
	    getAddresses = (item: ILookupItem) => {
            this.selectedList = item;
			// ...
            me.services.executeService('peanut.qnut-directory::messaging.GetDistributionListEmails', item.id ,
                (serviceResponse: Peanut.IServiceResponse) => {
                    if (serviceResponse.Result == Peanut.serviceResultSuccess) {
                        let response = serviceResponse.Value;
                        me.emailsText(response.text);
                        this.mailFormRecipients(item.name);
                        this.messageModalVisible(true); // display email form
                    }
                })
			// ...	
            });
        }
```

Since the component has been subscribed to the messageModelVisible observable it is signaled to display the form
when messageModalVisible is set to true.  The component resets it to false.

## Sending the messages
The sendMemberMessages function

```typescript
        sendMemberMessages = (subject: string, content: string)=> {
            let me = this;
            me.distributionListCode(item.code);
            me.distributionListName(item.name);
            let request = {
                code: me.distributionListCode(),
                subject: subject,
                body: content,
                listName: me.distributionListName()
            };
            me.showWaitMessage('Sending messages...');
            me.services.executeService('peanut.qnut-directory::messaging.SendDistributionMessages', request,
                function (serviceResponse: Peanut.IServiceResponse) {
                    if (serviceResponse.Result == Peanut.serviceResultSuccess) {
                        me.clearMessagesForm(true);
                    } 
                })
            // ...
        
    }

```
The service called by the host vm takes reponsibility for selecting the address list and sending the message.

## Host vm code examples

- ComponentsTest: /application/peanut/tests
- Committees: pnut/packages/qnut-directory
- CommitteeMembers: pnut/packages/qnut-committees
- GroupMembership: pnut/packages/qnut-usergroups
- DistributionLists: pnut/packages/qnut-directory

The code for the groupEmail component is located in: pnut/components


