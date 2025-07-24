import '../../vendor/alperenersoy/filament-export/resources/js/filament-export.js';
import Cleave from 'cleave.js';

document.addEventListener('DOMContentLoaded', () => {
    Livewire.hook('message.processed', () => {
        document.querySelectorAll('[data-cleave-initialized!="true"]').forEach((el) => {
            new Cleave(el, {
                numeral: true,
                numeralThousandsGroupStyle: 'thousand',
                prefix: 'Rp ',
                noImmediatePrefix: false,
                rawValueTrimPrefix: true,
                numeralDecimalScale: 0
            });
            el.dataset.cleaveInitialized = 'true';
        });
    });
});

