<div class="text-left pl-20 py-6">
    <div class="font-semibold uppercase text-blue-dark mb-6">
        {{ __('Request leave') }}
    </div>
    <form wire:submit.prevent="submit">
        <div class="mt-4 w-132">
            <x-jet-label for="leave-type" class="inline-flex items-center pl-2 py-2 border-none font-semibold text-gray-800 outline-none text-sm rounded-none h-full">{{ __('Leave type') }}</x-jet-label>
            <select id="leave-type" wire:model.defer="leave.leave_type_id" class="form-select shadow-none focus:shadow-none rounded-none text-sm w-full py-3 border-gray-600 {{ $errors->has('leave.leave_type_id') ? 'text-red-400 placeholder:text-red-400 border-red-400' : '' }}" name="leave_type_id">
                @foreach ($this->leaveTypes as $leaveType)
                    <option value="{{ $leaveType->id }}">{{ $leaveType->name }} ({{ $leaveType->balanced ? $leaveType->number . ' ' . __('Days left') : $leaveType->limit . ' ' . __('Max days') }})</option>
                @endforeach
            </select>
        </div>
        <div class="mt-4 w-132" x-data>
            <x-jet-label for="period" class="inline-flex items-center pl-2 py-2 border-none font-semibold text-gray-800 outline-none text-sm rounded-none h-full">{{ __('Period') }}</x-jet-label>
            <x-jet-input id="period" x-ref="date" x-on:load="$refs.date.value=''" class="form-input block shadow-none focus:shadow-none rounded-none text-sm w-full py-3 border-gray-600 {{ ($errors->has('leave.number') || $errors->has('leave.period')) ? 'text-red-400 placeholder:text-red-400 border-red-400' : 'placeholder:text-black' }}" type="text" name="period" placeholder="{{ __('Period') }}" />
            @error('leave.number') <span class="text-red-400">{{ __('Add a valid period') }}</span> @enderror
            @error('leave.period') <span class="text-red-400">{{ __($message) }}</span> @enderror
        </div>
        <div class="mt-4 w-132">
            <x-jet-label for="description" class="inline-flex items-center pl-2 py-2 border-none font-semibold text-gray-800 outline-none text-sm rounded-none h-full">{{ __('Description') }}</x-jet-label>
            <textarea id="description" wire:model="leave.description" class="form-input block resize-none shadow-none focus:shadow-none rounded-none text-sm h-24 w-full py-3 border-gray-600 {{ $errors->has('leave.description') ? 'text-red-400 placeholder:text-red-400 border-red-400' : 'placeholder:text-black' }}" name="description" placeholder="{{ __('Description') }}"></textarea>
        </div>
        <div class="flex items-center mt-10" x-data>
            <x-jet-button type="submit" class="bg-gray-800 outline-none capitalize text-white text-base rounded-none py-3 px-20 mr-2">
                {{ __('Validate') }}
            </x-jet-button>
            <x-jet-button type="button" @click="$wire.back()" class="bg-gray-400 focus:bg-gray-400 active:bg-gray-400 hover:bg-gray-400 outline-none border-none capitalize text-white text-base rounded-none py-3 px-20 ml-2">
                {{ __('Cancel') }}
            </x-jet-button>
        </div>
    </form>
    <script>
        window.addEventListener('livewire:load', function () {
            window.flatpickr.default('#period', {
                mode: 'range',
                minDate: 'today',
                dateFormat: 'Y-m-d',
                locale: {
                    firstDayOfWeek: 1,
                    weekdays: {
                        shorthand: ['dim', 'lun', 'mar', 'mer', 'jeu', 'ven', 'sam'],
                        longhand: [
                            'dimanche',
                            'lundi',
                            'mardi',
                            'mercredi',
                            'jeudi',
                            'vendredi',
                            'samedi',
                        ],
                    },
                    months: {
                        shorthand: [
                            'janv',
                            'févr',
                            'mars',
                            'avr',
                            'mai',
                            'juin',
                            'juil',
                            'août',
                            'sept',
                            'oct',
                            'nov',
                            'déc',
                        ],
                        longhand: [
                            'janvier',
                            'février',
                            'mars',
                            'avril',
                            'mai',
                            'juin',
                            'juillet',
                            'août',
                            'septembre',
                            'octobre',
                            'novembre',
                            'décembre',
                        ],
                    },
                    ordinal: function (nth) {
                        if (nth > 1)
                            return '';
                        return 'er';
                    },
                    rangeSeparator: ' au ',
                    weekAbbreviation: 'Sem',
                    scrollTitle: 'Défiler pour augmenter la valeur',
                    toggleTitle: 'Cliquer pour basculer',
                    time_24hr: true,
                },
                onChange: function(selectedDates, dateStr, instance) {
                    @this.set('period', (selectedDates.length === 2) ? dateStr.replace(instance.l10n.rangeSeparator, ',') : '');
                },
            });
        });
    </script>
</div>
