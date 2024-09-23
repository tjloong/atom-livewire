<x-mail::message>
## Hello!
    
You have a new enquiry:

**Name:** {{ get($enquiry, 'name') }}<br>
**Phone:** {{ get($enquiry, 'phone') }}<br>
**Email:** {{ get($enquiry, 'email') }}<br>
**Message:**<br>
*{{ nl2br(get($enquiry, 'message')) }}*
</x-mail::message>
