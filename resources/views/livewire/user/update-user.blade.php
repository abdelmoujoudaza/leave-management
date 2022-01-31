<div class="text-left pl-20 py-6">
    <div class="font-semibold uppercase text-blue-dark mb-6">
        {{ __('Personal informations') }}
    </div>
    <form wire:submit.prevent="submit">

        <div class="font-semibold mb-6">
            <span class="text-gray-500 text-sm mr-16">{{ __('Gender') }}</span>
            <div class="flex gap-4 mt-2">
                <div class="flex">
                    <x-jet-input id="gender-man" wire:model="user.gender" class="shadow-none form-radio form-tick h-5 w-5 mr-2" type="radio" name="gender" value="man" />
                    <x-jet-label for="gender-man" class="font-semibold text-sm mr-4" value="{{ __('Man') }}" />
                </div>
                <div class="flex">
                    <x-jet-input id="gender-woman" wire:model="user.gender" class="shadow-none form-radio form-tick h-5 w-5 mr-2" type="radio" name="gender" value="woman" />
                    <x-jet-label for="gender-woman" class="font-semibold text-sm" value="{{ __('woman') }}" />
                </div>
            </div>
        </div>

        <div class="font-semibold mb-6">
            <span class="text-gray-500 text-sm mr-16">{{ __('Civil status') }}</span>
            <div class="flex gap-4 mt-2">
                <div class="flex">
                    <x-jet-input id="civil-status-single" wire:model="user.civil_status" class="shadow-none form-radio form-tick h-5 w-5 mr-2" type="radio" name="civil_status" value="single" />
                    <x-jet-label for="civil-status-single" class="font-semibold text-sm mr-4" value="{{ __('Single') }}" />
                </div>
                <div class="flex">
                    <x-jet-input id="civil-status-married" wire:model="user.civil_status" class="shadow-none form-radio form-tick h-5 w-5 mr-2" type="radio" name="civil_status" value="married" />
                    <x-jet-label for="civil-status-married" class="font-semibold text-sm" value="{{ __('Married') }}" />
                </div>
            </div>
        </div>

        <div class="mt-4 w-132">
            <x-jet-label for="role" class="text-gray-500 font-semibold text-sm mr-16">{{ __('Role') }}</x-jet-label>
            <select id="role" wire:model.defer="role" class="form-select block shadow-none focus:shadow-none rounded-none text-sm w-full py-3 mt-2 {{ $errors->has('role') ? 'text-red-400 placeholder:text-red-400 border-red-400' : '' }}" name="role">
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}">{{ __(ucfirst($role->name)) }}</option>
                @endforeach
            </select>
        </div>

        <div class="mt-4 w-132">
            <x-jet-input id="national_id" wire:model="user.national_id" class="form-input block shadow-none focus:shadow-none rounded-none text-sm w-full py-3 {{ $errors->has('user.national_id') ? 'text-red-400 placeholder:text-red-400 border-red-400' : '' }}" type="text" name="national_id" placeholder="{{ __('National id') }}" required autofocus autocomplete="current-national-id" />
        </div>

        <div class="mt-4 w-132">
            <x-jet-input id="firstname" wire:model="user.firstname" class="form-input block shadow-none focus:shadow-none rounded-none text-sm w-full py-3 {{ $errors->has('user.firstname') ? 'text-red-400 placeholder:text-red-400 border-red-400' : '' }}" type="text" name="firstname" placeholder="{{ __('Firstname') }}" required autofocus autocomplete="current-firstname" />
        </div>

        <div class="mt-4 w-132">
            <x-jet-input id="lastname" wire:model="user.lastname" class="form-input block shadow-none focus:shadow-none rounded-none text-sm w-full py-3 {{ $errors->has('user.lastname') ? 'text-red-400 placeholder:text-red-400 border-red-400' : '' }}" type="text" name="lastname" placeholder="{{ __('Lastname') }}" required autocomplete="current-lastname" />
        </div>

        <div class="mt-4 w-132 relative" x-data>
            <x-jet-input id="password" wire:model="password" class="form-input block shadow-none focus:shadow-none rounded-none text-sm w-full py-3 pr-32 {{ $errors->has('password') ? 'text-red-400 placeholder:text-red-400 border-red-400' : '' }}" type="text" name="password" placeholder="{{ __('Password') }}" />
            <x-jet-button type="button" @click="$wire.password = generator.generate()" class="justify-center bg-gray-800 outline-none capitalize text-white text-base font-semibold rounded-none h-full w-28 absolute bottom-0 right-0">{{ __('Generate') }}</x-jet-button>
        </div>

        <div class="mt-4 w-132">
            <x-jet-input id="position" wire:model="user.position" class="form-input block shadow-none focus:shadow-none rounded-none text-sm w-full py-3 {{ $errors->has('user.position') ? 'text-red-400 placeholder:text-red-400 border-red-400' : '' }}" type="text" name="position" placeholder="{{ __('Position') }}" required autocomplete="current-position" />
        </div>

        <div class="mt-4 w-132">
            <x-jet-input id="address" wire:model="user.address" class="form-input block shadow-none focus:shadow-none rounded-none text-sm w-full py-3 {{ $errors->has('user.address') ? 'text-red-400 placeholder:text-red-400 border-red-400' : '' }}" type="text" name="address" placeholder="{{ __('Address') }}" required autocomplete="current-address" />
        </div>

        <div class="mt-4 w-132">
            <x-jet-input id="birth" wire:model="user.birth" class="form-input block shadow-none focus:shadow-none rounded-none text-sm w-full py-3 {{ $errors->has('user.birth') ? 'text-red-400 placeholder:text-red-400 border-red-400' : '' }}" type="date" name="birth" placeholder="{{ __('Birth') }}" required />
        </div>

        <div class="mt-4 w-132">
            <x-jet-input id="email" wire:model="user.email" class="form-input block shadow-none focus:shadow-none rounded-none text-sm w-full py-3 {{ $errors->has('user.email') ? 'text-red-400 placeholder:text-red-400 border-red-400' : '' }}" type="email" name="email" :value="old('email')" placeholder="{{ __('E-mail') }}" required />
            @error('user.email') <span class="text-red-400">{{ $message }}</span> @enderror
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
</div>

