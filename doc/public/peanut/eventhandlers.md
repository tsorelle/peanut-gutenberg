[Return to docs home page](../index.md)
## ViewModel event handlers

The handle event method is typically used for communication between objects such as knockout components

```typescript
        handleEvent = (eventName:string, data?:any)=> {
            let me = this;
            switch (eventName) {
                case 'person-selected' :
                    me.newTerm(<INameValuePair>data.person);
                    break;

                case 'person-search-cancelled' :
                    // alert('Search cancelled.');
                    break;
                case 'test' :
                    alert('event test');
                    break;
            }
        };

```