<div 
    x-data="{
        text: null,
        submit () {
            this.$wire.addFileUrls(this.text.split(`\n`)).then((res) => {
                this.$dispatch('input', res)
                this.text = null
            })
        },
    }"
    class="flex flex-col gap-4"
>
    <x-form.field label="From URL">
        <textarea x-model="text" x-on:input.stop class="form-input w-full" rows="5"></textarea>
    </x-form.field>

    <div class="flex items-center gap-2">
        <x-button x-on:click="submit" label="Add URL" color="gray" outlined/>
    </div>
</div>