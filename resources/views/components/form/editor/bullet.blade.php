<div class="group">
    <button type="button" x-tooltip.text="Bullet List" x-on:click="commands().toggleBulletList()">
        <x-icon name="list-ul"/>
    </button>
    
    <button type="button" x-tooltip.text="Ordered List" x-on:click="commands().toggleOrderedList()">
        <x-icon name="list-ol"/>
    </button>
    
    <button type="button" x-tooltip.text="Sink List Item" x-on:click="commands().sinkListItem('listItem')">
        <x-icon name="indent"/>
    </button>
    
    <button type="button" x-tooltip.text="Lift List Item" x-on:click="commands().liftListItem('listItem')">
        <x-icon name="outdent"/>
    </button>
</div>
