<x-mail::message>
## Hello!
    
You have a new enquiry:

**Name:** @ee($enquiry->name)<br>
**Phone:** @ee($enquiry->phone)<br>
**Email:** @ee($enquiry->email)<br>
**Message:**<br>
*@ee(nl2br(get($enquiry, 'message')))*
</x-mail::message>
