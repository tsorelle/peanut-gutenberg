[Return to docs home page](../index.md)
# Java Script Arrays
We no longer use UnderscroreJS or Lodash (_.somefunction()). 
Use pure javascript alternatives.  There are several functions in Peanut.Helper 
that you may use:

```typescript
_.filter(list,function (...)
list.filter(function (...)

_.sortBy(list,propertyname);
Peanut.Helper.SortByAlpha(list,propertyname); //  case insensitied
Peanut.Helper.SortByInt(list,propertyname); //  whole number values
Peanut.Helper.SortBy(list,propertyname); //  conversions or case don't matter

 _.find(list, function(item: any) {...});
 list.find(function(item: any) {...});
 
 _.forEach
 // A plain javascript for loop is the mose efficient
let cars = ['Ford','Toyota','Tesla'];
for (let i = 0; i < cars.length; i++) {
    text += cars[i] + "<br>";
}

_.findIndex
let currentIndex = me.meetings.findIndex( function(meeting : FriendsMeeting){
    return meeting.meetingId == updated.meetingId;
});

/* Replacing _.forEach with with array.forEach,
 alhough less efficient, may be a reasonable 
 and simpler solution for legacy code */
cars.forEach(
    (car: string) => {
        console.log(car);
    }
)

```
More suggestions: https://blog.bitsrc.io/you-dont-need-lodash-or-how-i-started-loving-javascript-functions-3f45791fa6cd
