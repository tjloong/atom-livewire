<x-modal uid="email-form-modal" header="Send Email" icon="paper-plane" class="max-w-screen-md">
    @if ($email)
        <div class="p-6 grid gap-6">
            <div class="grid gap-6 md:grid-cols-2">
                <x-form.email label="Sender Email"
                    wire:model.defer="email.from.email"
                    :error="$errors->first('email.from.email')"
                    required
                />

                <x-form.text label="Sender Name"
                    wire:model.defer="email.from.name"
                    :error="$errors->first('email.from.name')"
                    required
                />
            </div>

            <x-form.email label="To"
                wire:model.defer="email.to"
                :options="$this->emails->toArray()"
                :error="$errors->first('email.to')"
                multiple
                required
            />

            <x-form.email label="Cc"
                wire:model.defer="email.cc"
                :options="$this->emails->toArray()"
                multiple
            />

            <x-form.text label="Subject"
                wire:model.defer="email.subject"
                :error="$errors->first('email.subject')"
                required
            />

            <x-form.textarea label="Body"
                wire:model.defer="email.body"
                :error="$errors->first('email.body')"
                rows="10"
                required
            />

            <x-form.email label="Send a copy to"
                wire:model.defer="email.bcc"
                multiple
            />
        </div>

        <x-slot:foot>
            <x-button.submit type="button"
                label="Send Email"
                wire:click="sendEmail"
            />
        </x-slot:foot>
    @endif
</x-modal>
