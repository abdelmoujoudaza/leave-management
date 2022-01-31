<div class="w-full px-6 py-6 font-semibold">
    <div class="flex flex-col bg-gray-800 mb-4 py-2">
        <div class="flex justify-center font-semibold text-gray-100">{{ __('Filter by') }}</div>
        <hr class="h-px bg-gray-100 my-2" />
        <div class="flex justify-around w-full items-center">
            @hasanyrole('manager|admin')
                <div class="flex items-center relative w-64" x-data>
                    <x-jet-label for="user" class="inline-flex items-center bg-gray-100 w-3/6 p-2 border-none font-semibold text-gray-800 outline-none text-sm rounded-none h-full">{{ __('Employee') }}</x-jet-label>
                    <select id="user" x-ref="select" x-on:load="$refs.select.value=''" wire:input="doSelectFilter('user', $event.target.value)" class="form-select shadow-none focus:shadow-none rounded-none text-sm border-none w-3/6 py-2" name="user">
                        <option value="">{{ __('All employees') }}</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->firstname }} {{ $user->lastname }}</option>
                        @endforeach
                    </select>
                </div>
            @endhasanyrole
            <div class="flex items-center relative w-64" x-data>
                <x-jet-label for="period" class="inline-flex items-center bg-gray-100 w-3/6 p-2 border-none font-semibold text-gray-800 outline-none text-sm rounded-none h-full">{{ __('Period') }}</x-jet-label>
                <x-jet-input id="period" x-ref="date" x-on:load="$refs.date.value=''" class="form-input block shadow-none w-3/6 focus:shadow-none rounded-none placeholder-black text-sm py-2" type="text" name="period" placeholder="Période" />
            </div>
            <div class="flex items-center relative w-64" x-data>
                <x-jet-label for="leave-type" class="inline-flex items-center bg-gray-100 w-3/6 p-2 border-none font-semibold text-gray-800 outline-none text-sm rounded-none h-full">{{ __('Leave type') }}</x-jet-label>
                <select id="leave-type" x-ref="select" x-on:load="$refs.select.value=''" wire:input="doSelectFilter('leaveType', $event.target.value)" class="form-select shadow-none focus:shadow-none rounded-none text-sm border-none w-3/6 py-2" name="leave_type">
                        <option value="">{{ __('All types') }}</option>
                        @foreach ($leaveTypes as $leaveType)
                            <option value="{{ $leaveType->id }}">{{ $leaveType->name }}</option>
                        @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="flex justify-between mb-4">
        <div class="flex justify-start items-center" x-data @file-exported.window="$refs.xlsx.disabled = false; $refs.csv.disabled = false;">
            <button type="button" x-ref="xlsx" @click="$refs.xlsx.disabled = true; $refs.csv.disabled = true;" wire:click="export('xlsx')" class="bg-gray-800 border-gray-800 outline-none text-gray-300 text-base py-3 px-5 mr-5 disabled:opacity-50">
                {{ __('Excel export') }}
            </button>
            <button type="button" x-ref="csv" @click="$refs.xlsx.disabled = true; $refs.csv.disabled = true;" wire:click="export('csv')" class="bg-gray-800 border-gray-800 outline-none text-gray-300 text-base py-3 px-5 disabled:opacity-50">
                {{ __('CSV export') }}
            </button>
        </div>
        <a href="{{ route('leave.create') }}" class="flex flex-row items-center bg-gray-800 border-gray-800 outline-none text-gray-300 text-base py-3 px-5 disabled:opacity-50">
            <span class="flex items-center justify-center text-lg text-green-400">
                <svg fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6">
                    <path d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </span>
            <span class="ml-3">{{ __('Request leave') }}</span>
        </a>
    </div>
    <div class="w-full max-w-full relative overflow-x-scroll mb-4">
        <table class="w-full table-auto border-collapse text-sm text-left">
            <thead class="text-gray-300 bg-gray-800">
                @hasanyrole('manager|admin')
                    <th class="border border-gray-800 border-r-white px-4 py-3">{{ __('Employee') }}</th>
                @endhasanyrole
                <th class="border border-gray-800 border-r-white px-4 py-3">{{ __('Number of days') }}</th>
                <th class="border border-gray-800 border-r-white px-4 py-3">{{ __('Start date') }}</th>
                <th class="border border-gray-800 border-r-white px-4 py-3">{{ __('End date') }}</th>
                <th class="border border-gray-800 border-r-white px-4 py-3">{{ __('Status') }}</th>
                {{-- <th class="border border-gray-800 border-r-white px-4 py-3">{{ __('Description') }}</th> --}}
                @hasanyrole('manager|admin')
                    <th class="border border-gray-800 border-r-white px-4 py-3">{{ __('Leave type') }}</th>
                    <th class="border border-gray-800 px-4 py-3">{{ __('Action') }}</th>
                @else
                    <th class="border border-gray-800 px-4 py-3">{{ __('Leave type') }}</th>
                @endhasanyrole
            </thead>
            <tbody class="text-gray-normal">
                @forelse ($this->leaves as $leave)
                    <tr x-data="{leave: {{ $leave->id }}}">
                        @hasanyrole('manager|admin')
                            <td class="border border-gray-darkest px-4 py-3 whitespace-no-wrap">{{ $leave->fullname }}</td>
                        @endhasanyrole
                        <td class="border border-gray-darkest px-4 py-3 whitespace-no-wrap">{{ $leave->number }}</td>
                        <td class="border border-gray-darkest px-4 py-3 whitespace-no-wrap">{{ $leave->start_date ? date('d/m/Y', strtotime($leave->start_date)) : '' }}</td>
                        <td class="border border-gray-darkest px-4 py-3 whitespace-no-wrap">{{ $leave->start_date ? date('d/m/Y', strtotime($leave->end_date)) : '' }}</td>
                        <td class="border border-gray-darkest px-4 py-3 whitespace-no-wrap">{{ __(ucfirst($leave->status)) }}</td>
                        {{-- <td class="border border-gray-darkest px-4 py-3 whitespace-no-wrap">{{ $leave->description }}</td> --}}
                        <td class="border border-gray-darkest px-4 py-3 whitespace-no-wrap">{{ $leave->leave_type_name }}</td>
                        @hasanyrole('manager|admin')
                            <td class="border border-gray-darkest px-4 py-3 whitespace-no-wrap">
                                <div class="flex items-center justify-around h-full">
                                    @if ($leave->status == 'pending')
                                        <button @click="$wire.call('updateLeave', leave, 'approved');" type="button" class="w-max inline-flex justify-center font-bold shadow-none p-2 bg-green-300 text-white border-none rounded-full focus:outline-none sm:mr-2 sm:w-28 sm:text-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="fill-green-400 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                            {{ __('Approved') }}
                                        </button>
                                        <button @click="$wire.call('updateLeave', leave, 'refused');" type="button" class="w-max inline-flex justify-center font-bold shadow-none p-2 bg-red-300 text-white border-none rounded-full focus:outline-none sm:mr-2 sm:w-28 sm:text-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="fill-red-400 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                            </svg>
                                            {{ __('Refused') }}
                                        </button>
                                    @endif
                                </div>
                            </td>
                        @endhasanyrole
                    </tr>
                @empty
                    <tr>
                        <td colspan="13" class="border border-gray-500 text-center px-4 py-3 whitespace-no-wrap">{{ __('No data available in table') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $this->leaves->links() }}
    <script>
        window.addEventListener('livewire:load', function () {
            window.flatpickr.default('#period', {
                mode: 'range',
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
                    @this.doDateFilter('period', (selectedDates.length === 2) ? dateStr.replace(instance.l10n.rangeSeparator, ',') : '');
                },
            });
        });
    </script>
</div>
