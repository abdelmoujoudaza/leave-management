<div class="text-left pl-20 py-6">
    <div class="font-semibold uppercase text-blue-dark mb-6">
        {{ __('Add a direction') }}
    </div>
    <form wire:submit.prevent="submit">
        <div class="mt-4 w-132">
            <x-jet-label for="driver" class="inline-flex items-center pl-2 py-2 border-none font-semibold text-gray-800 outline-none text-sm rounded-none h-full">{{ __('Driver') }}</x-jet-label>
            <select id="driver" wire:model.defer="direction.driver_id" class="form-select shadow-none focus:shadow-none rounded-none text-sm w-full py-3 border-gray-300 {{ $errors->has('direction.driver_id') ? 'text-red-400 placeholder:text-red-400 border-red-400' : '' }}" name="driver_id">
                @foreach ($drivers as $driver)
                    <option value="{{ $driver->id }}">{{ $driver->firstname }} {{ $driver->lastname }}</option>
                @endforeach
            </select>
        </div>
        <div class="mt-4 w-132">
            <x-jet-input id="name" wire:model="direction.name" class="form-input block shadow-none focus:shadow-none rounded-none text-sm w-full py-3 {{ $errors->has('direction.name') ? 'text-red-400 placeholder:text-red-400 border-red-400' : '' }}" required type="text" name="name" placeholder="{{ __('Name') }}" />
        </div>
        <div class="mt-4 w-132">
            <x-jet-input id="time" wire:model="direction.time" class="form-input block resize-none shadow-none focus:shadow-none rounded-none text-sm w-full py-3 border-gray-300 {{ $errors->has('direction.time') ? 'text-red-400 placeholder:text-red-400 border-red-400' : '' }}" required type="text" name="time" placeholder="{{ __('Time') }}" />
        </div>
        <div class="mt-4 w-132">
            <x-jet-label for="status" class="inline-flex items-center pl-2 py-2 border-none font-semibold text-gray-800 outline-none text-sm rounded-none h-full">{{ __('Status') }}</x-jet-label>
            <div class="flex items-center gap-1">
                <button
                    id="status"
                    wire:model="direction.status"
                    role="button"
                    aria-checked="false"
                    type="button"
                    wire:click.stop="toggleStatus"
                    class="relative inline-flex shrink-0 h-6 w-11 border-2 border-gray-300 rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none disabled:opacity-70 disabled:cursor-not-allowed filament-forms-toggle-component {{ $direction->status ? 'bg-primary-600' : 'bg-gray-200' }} {{ $errors->has('direction.status') ? 'border-danger-300 ring-danger-500 border-red-400' : 'border-gray-300' }}"
                >
                    <span class="pointer-events-none relative inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 ease-in-out transition duration-200 {{ $direction->status ? 'translate-x-5 rtl:-translate-x-5' : 'translate-x-0' }}">
                        <span class="absolute inset-0 h-full w-full flex items-center justify-center transition-opacity {{ $direction->status ? 'opacity-0 ease-out duration-100' : 'opacity-100 ease-in duration-200' }}" aria-hidden="true">
                        </span>
                        <span class="absolute inset-0 h-full w-full flex items-center justify-center transition-opacity {{ $direction->status ? 'opacity-100 ease-in duration-200' : 'opacity-0 ease-out duration-100' }}" aria-hidden="true">
                        </span>
                    </span>
                </button>
                <span>{{ __($direction->status ? 'Enable' : 'Disable') }}</span>
            </div>
        </div>
        <div class="flex items-center mt-10" x-data>
            <x-jet-button type="submit" class="bg-gray-800 outline-none capitalize text-white text-base rounded-none py-3 px-20 mr-2">
                {{ __('Save') }}
            </x-jet-button>
            <x-jet-button type="button" @click="$wire.back()" class="bg-gray-400 focus:bg-gray-400 active:bg-gray-400 hover:bg-gray-400 outline-none border-none capitalize text-white text-base rounded-none py-3 px-20 ml-2">
                {{ __('Cancel') }}
            </x-jet-button>
        </div>
    </form>
</div>
