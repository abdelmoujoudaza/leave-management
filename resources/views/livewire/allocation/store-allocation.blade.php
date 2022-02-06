<div class="text-left pl-20 py-6">
    <div class="font-semibold uppercase text-blue-dark mb-6">
        {{ __('Allocate a leave') }}
    </div>
    <form wire:submit.prevent="submit">
        <div class="mt-4 w-132">
            <x-jet-label for="user" class="inline-flex items-center pl-2 py-2 border-none font-semibold text-gray-800 outline-none text-sm rounded-none h-full">{{ __('Employee') }}</x-jet-label>
            <select id="user" wire:model.defer="leave.user_id" class="form-select shadow-none focus:shadow-none rounded-none text-sm w-full py-3 border-gray-600 {{ $errors->has('leave.user_id') ? 'text-red-400 placeholder:text-red-400 border-red-400' : '' }}" name="user_id">
                @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->firstname }} {{ $user->lastname }}</option>
                @endforeach
            </select>
        </div>
        <div class="mt-4 w-132">
            <x-jet-label for="leave-type" class="inline-flex items-center pl-2 py-2 border-none font-semibold text-gray-800 outline-none text-sm rounded-none h-full">{{ __('Leave type') }}</x-jet-label>
            <select id="leave-type" wire:model.defer="leave.leave_type_id" class="form-select shadow-none focus:shadow-none rounded-none text-sm w-full py-3 border-gray-600 {{ $errors->has('leave.leave_type_id') ? 'text-red-400 placeholder:text-red-400 border-red-400' : '' }}" name="leave_type_id">
                @foreach ($this->leaveTypes as $leaveType)
                    <option value="{{ $leaveType->id }}">{{ $leaveType->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mt-4 w-132">
            <x-jet-label for="number" class="inline-flex items-center pl-2 py-2 border-none font-semibold text-gray-800 outline-none text-sm rounded-none h-full">{{ __('Number of days') }}</x-jet-label>
            <x-jet-input id="number" wire:model="leave.number" class="form-input block shadow-none focus:shadow-none rounded-none text-sm w-full py-3 border-gray-600 {{ ($errors->has('leave.number')) ? 'text-red-400 placeholder:text-red-400 border-red-400' : 'placeholder:text-black' }}" type="number" step="0.5" name="number" placeholder="Number of days" />
            @error('leave.number') <span class="text-red-400">{{ __('Add a number of days') }}</span> @enderror
        </div>
        <div class="mt-4 w-132">
            <x-jet-label for="description" class="inline-flex items-center pl-2 py-2 border-none font-semibold text-gray-800 outline-none text-sm rounded-none h-full">{{ __('Description') }}</x-jet-label>
            <textarea id="description" wire:model="leave.description" class="form-input block resize-none shadow-none focus:shadow-none rounded-none text-sm h-24 w-full py-3 border-gray-600 {{ $errors->has('leave.description') ? 'text-red-400 placeholder:text-red-400 border-red-400' : 'placeholder:text-black' }}" name="description" placeholder="Description"></textarea>
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
