<div class="w-full px-6 py-6 font-semibold">
    <div x-data="{ open: false, user: null }" @toggle.window="open = !open; user = $event.detail;">
        <div :class="{ 'hidden': open === false, 'flex': open === true }" class="overflow-x-hidden overflow-y-auto fixed inset-0 z-50 outline-none focus:outline-none justify-center items-center" id="modal-id">
            <template x-if="open">
                <div
                    x-transition:enter="ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                    role="dialog"
                    aria-modal="true"
                    aria-labelledby="modal-headline"
                    @click.outside="open = false;"
                >
                    <div class="bg-white px-4 pt-5 pb-4">
                        <div class="flex items-center">
                            <p class="w-full text-center text-sm text-grat-700">
                                {{ __('Are you sure you want to delete this user ?') }}
                            </p>
                        </div>
                    </div>
                    <div class="px-4 py-3 sm:px-6 sm:flex sm:flex-row items-center justify-center">
                        <button @click="$wire.call('archivedUser', user);" @user-archived.window="open = false;" type="button" class="w-full inline-flex justify-center font-bold shadow-sm px-4 py-2 bg-gray-800 text-gray-300 focus:outline-none sm:mr-2 sm:w-48 sm:text-sm">
                            {{ __('Remove') }}
                        </button>
                        <button @click="open = false;" type="button" class="mt-3 w-full inline-flex justify-center font-bold shadow-sm px-4 py-2 bg-gray-400 text-white focus:outline-none sm:mt-0 sm:ml-2 sm:w-48 sm:text-sm">
                            {{ __('Cancel') }}
                        </button>
                    </div>
                </div>
            </template>
        </div>
        <div :class="{ 'hidden': open === false, 'flex': open === true }" class="hidden opacity-25 fixed inset-0 z-40 bg-black hidden" id="modal-id-backdrop"></div>
    </div>

    {{-- <div class="flex flex-col bg-gray-800 mb-4 py-2">
        <div class="flex justify-center font-semibold text-gray-100">{{ __('Filter by') }}</div>
        <hr class="h-px bg-gray-100 my-2" />
        <div class="flex justify-around w-full items-center">
            <div class="flex items-center relative w-64" x-data>
                <x-jet-label for="user" class="inline-flex items-center bg-gray-100 w-3/6 p-2 border-none font-semibold text-gray-800 outline-none text-sm rounded-none h-full">{{ __('Name') }}</x-jet-label>
                <x-jet-input id="user" x-ref="name" x-on:load="$refs.name.value=''" class="form-input block shadow-none w-3/6 focus:shadow-none rounded-none placeholder-black text-sm py-2" type="text" name="name" placeholder="{{ __('Name') }}" />
            </div>
        </div>
    </div> --}}

    <div class="flex justify-between mb-4">
        <div class="flex justify-start items-center" x-data @file-exported.window="$refs.xlsx.disabled = false; $refs.csv.disabled = false;">
            <button type="button" x-ref="xlsx" @click="$refs.xlsx.disabled = true; $refs.csv.disabled = true;" wire:click="export('xlsx')" class="bg-gray-800 border-gray-800 outline-none text-gray-300 text-base py-3 px-5 mr-5 disabled:opacity-50">
                {{ __('Excel export') }}
            </button>
            <button type="button" x-ref="csv" @click="$refs.xlsx.disabled = true; $refs.csv.disabled = true;" wire:click="export('csv')" class="bg-gray-800 border-gray-800 outline-none text-gray-300 text-base py-3 px-5 disabled:opacity-50">
                {{ __('CSV export') }}
            </button>
        </div>
        <a href="{{ route('user.create') }}" class="flex flex-row items-center bg-gray-800 border-gray-800 outline-none text-gray-300 text-base py-3 px-5 disabled:opacity-50">
            <span class="flex items-center justify-center text-lg text-green-400">
                <svg fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6">
                    <path d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </span>
            <span class="ml-3">{{ __('Add an Employe') }}</span>
        </a>
    </div>
    <div class="w-full max-w-full relative overflow-x-scroll mb-4">
        <table class="w-full table-auto border-collapse text-sm text-left">
            <thead class="text-gray-300 bg-gray-800">
                <th class="border border-gray-800 border-r-white px-4 py-3">{{ __('National id') }}</th>
                <th class="border border-gray-800 border-r-white px-4 py-3">{{ __('Employee') }}</th>
                <th class="border border-gray-800 border-r-white px-4 py-3">{{ __('Gender') }}</th>
                <th class="border border-gray-800 border-r-white px-4 py-3">{{ __('Position') }}</th>
                <th class="border border-gray-800 border-r-white px-4 py-3">{{ __('E-mail') }}</th>
                <th class="border border-gray-800 px-4 py-3">{{ __('Action') }}</th>
            </thead>
            <tbody class="text-gray-normal">
                @forelse ($this->users as $user)
                    <tr x-data="{user: {{ $user->id }}}">
                        <td class="border border-gray-darkest px-4 py-3 whitespace-no-wrap">{{ $user->national_id }}</td>
                        <td class="border border-gray-darkest px-4 py-3 whitespace-no-wrap">{{ $user->fullname }}</td>
                        <td class="border border-gray-darkest px-4 py-3 whitespace-no-wrap">{{ __(ucfirst($user->gender)) }}</td>
                        <td class="border border-gray-darkest px-4 py-3 whitespace-no-wrap">{{ $user->position }}</td>
                        <td class="border border-gray-darkest px-4 py-3 whitespace-no-wrap">{{ $user->email }}</td>
                        <td class="border border-gray-darkest px-4 py-3">
                            <div class="flex items-center justify-around h-full">
                                <a href="{{ route('user.update', ['user' => $user->id]) }}" class="cursor-pointer mx-px">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 fill-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                        <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                                @if ($user->id != auth()->user()->id)
                                    <a @click.stop="$dispatch('toggle', user);" class="cursor-pointer mx-px">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 fill-red-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="13" class="border border-gray-500 text-center px-4 py-3 whitespace-no-wrap">{{ __('No data available in table') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $this->users->links() }}
</div>
