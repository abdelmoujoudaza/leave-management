<div class="w-full px-6 py-6 font-semibold">
    <div>
        @if (session('message'))
            <div class="mb-8 font-medium text-sm text-green-600">
                {{ session('message') }}
            </div>
        @endif
    </div>
    <div class="flex flex-col bg-gray-800 mb-4 py-2">
        <div class="flex justify-center font-semibold text-gray-100">{{ __('Filter by') }}</div>
        <hr class="h-px bg-gray-100 my-2" />
        <div class="flex justify-around w-full items-center">
            <div class="flex items-center relative w-96" x-data>
                <x-jet-label for="direction" class="inline-flex items-center bg-gray-100 w-3/6 p-2 border-none font-semibold text-gray-800 outline-none text-sm rounded-none h-full">{{ __('Direction') }}</x-jet-label>
                    <select id="direction" x-ref="select" x-on:load="$refs.select.value=''" wire:input="doSelectFilter('direction', $event.target.value)" class="form-select shadow-none focus:shadow-none rounded-none text-sm border-none w-3/6 py-2" name="direction">
                        <option value="">{{ __('All directions') }}</option>
                        @foreach ($directions as $direction)
                            <option value="{{ $direction->id }}">{{ $direction->name }}</option>
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
        <a href="{{ route('station.create') }}" class="flex flex-row items-center bg-gray-800 border-gray-800 outline-none text-gray-300 text-base py-3 px-5 disabled:opacity-50">
            <span class="flex items-center justify-center text-lg text-green-400">
                <svg fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6">
                    <path d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </span>
            <span class="ml-3">{{ __('Add a station') }}</span>
        </a>
    </div>
    <div class="w-full max-w-full relative overflow-x-scroll mb-4">
        <table class="w-full table-auto border-collapse text-sm text-left">
            <thead class="text-gray-300 bg-gray-800">
                <th class="border border-gray-800 border-r-white px-4 py-3">{{ __('Station') }}</th>
                <th class="border border-gray-800 border-r-white px-4 py-3">{{ __('Address') }}</th>
                <th class="border border-gray-800 border-r-white px-4 py-3">{{ __('Latitude') }}</th>
                <th class="border border-gray-800 border-r-white px-4 py-3">{{ __('Longitude') }}</th>
                <th class="border border-gray-800 border-r-white px-4 py-3">{{ __('Status') }}</th>
                <th class="border border-gray-800 px-4 py-3">{{ __('Direction') }}</th>
            </thead>
            <tbody class="text-gray-normal">
                @forelse ($this->stations as $station)
                    <tr x-data="{station: {{ $station->id }}}">
                        <td class="border border-gray-darkest px-4 py-3 whitespace-no-wrap">{{ $station->name }}</td>
                        <td class="border border-gray-darkest px-4 py-3 whitespace-no-wrap max-w-sm">{{ $station->address }}</td>
                        <td class="border border-gray-darkest px-4 py-3 whitespace-no-wrap">{{ $station->latitude }}</td>
                        <td class="border border-gray-darkest px-4 py-3 whitespace-no-wrap">{{ $station->longitude }}</td>
                        <td class="border border-gray-darkest px-4 py-3 whitespace-no-wrap">{{ __($station->status ? 'Enable' : 'Disable') }}</td>
                        <td class="border border-gray-darkest px-4 py-3 whitespace-no-wrap">{{ $station->direction_name }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="13" class="border border-gray-500 text-center px-4 py-3 whitespace-no-wrap">{{ __('No data available in table') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $this->stations->links() }}
</div>
