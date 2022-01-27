<div class="text-left pl-20 py-6">
    <div class="font-semibold uppercase text-blue-dark mb-6">
        {{ __('Request Leave') }}
    </div>
    <form wire:submit.prevent="submit">
        <div class="font-semibold mb-6">
            <span class="text-gray-500 text-sm mr-16">{{ __('Gender') }}</span>
            <x-jet-input id="gender-homme" wire:model="advisor.gender" class="shadow-none form-radio form-tick h-5 w-5 mr-2" type="radio" name="gender" value="Homme" />
            <x-jet-label for="gender-homme" class="font-semibold text-sm mr-4" value="Homme" />

            <x-jet-input id="gender-femme" wire:model="advisor.gender" class="shadow-none form-radio form-tick h-5 w-5 mr-2" type="radio" name="gender" value="Femme" />
            <x-jet-label for="gender-femme" class="font-semibold text-sm" value="Femme" />
        </div>
        <div class="mt-4 w-132">
            <x-jet-input id="firstname" wire:model="advisor.firstname" class="form-input block shadow-none focus:shadow-none rounded-none text-sm w-full py-3 {{ $errors->has('advisor.firstname') ? 'border-red-400' : '' }} " type="text" name="firstname" placeholder="{{ __('Firstname') }}" required autofocus autocomplete="current-firstname" />
        </div>
        <div class="mt-4 w-132">
            <x-jet-input id="lastname" wire:model="advisor.lastname" class="form-input block shadow-none focus:shadow-none rounded-none text-sm w-full py-3 {{ $errors->has('advisor.lastname') ? 'border-red-400' : '' }} " type="text" name="lastname" placeholder="{{ __('Lastname') }}" required autocomplete="current-lastname" />
        </div>
        <div class="mt-4 w-132">
            <x-jet-input id="phone-number" wire:model="advisor.phone_number" class="form-input block shadow-none focus:shadow-none rounded-none text-sm w-full py-3 {{ $errors->has('advisor.phone_number') ? 'border-red-400' : '' }} " type="tele" name="phone_number" placeholder="{{ __('Phone number') }} ex:+212600000000" required autocomplete="current-phone_number" />
            {{-- @error('advisor.phone_number') <span class="error">{{ $message }}</span> @enderror --}}
        </div>
        <div class="mt-4 w-132">
            <x-jet-input id="email" wire:model="user.email" class="form-input block shadow-none focus:shadow-none rounded-none text-sm w-full py-3 {{ $errors->has('user.email') ? 'border-red-400' : '' }} " type="email" name="email" :value="old('email')" placeholder="{{ __('E-mail') }}" required />
            {{-- @error('user.email') <span class="error">{{ $message }}</span> @enderror --}}
        </div>
        <div class="mt-4 w-132">
            <select id="sale-point" wire:model="advisor.sale_point_id" class="form-select shadow-none focus:shadow-none rounded-none text-sm w-full py-3 {{ $errors->has('advisor.sale_point_id') ? 'border-red-400' : '' }}" name="sale_point_id">
                <option value="">{{ __('Sale point') }}</option>
                @foreach ($salePoints as $salePoint)
                    <option value="{{ $salePoint->id }}">{{ $salePoint->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-center mt-10" x-data>
            <x-jet-button type="submit" class="bg-blue-light outline-none capitalize text-white text-base rounded-none py-3 px-20 mr-2">
                {{ __('Validate') }}
            </x-jet-button>
            <x-jet-button type="button" @click="$wire.back()" class="bg-gray-darkest outline-none capitalize text-white text-base rounded-none py-3 px-20 ml-2">
                {{ __('Cancel') }}
            </x-jet-button>
        </div>
    </form>
</div>
