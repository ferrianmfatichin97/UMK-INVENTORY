<div>
    <input
        x-data
        x-ref="input"
        x-init="
            new Cleave($refs.input, {
                numeral: true,
                numeralThousandsGroupStyle: 'thousand',
                numeralDecimalScale: 0,
                prefix: 'Rp ',
                rawValueTrimPrefix: true
            })
        "
        wire:model.defer="{{ $attributes->wire('model')->value() }}"
        class="w-full border-gray-300 rounded-md shadow-sm"
        {{ $attributes->except('wire:model') }}
    />
</div>