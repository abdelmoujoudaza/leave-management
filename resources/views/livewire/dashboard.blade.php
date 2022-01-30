<div class="w-full px-6 py-6 font-semibold">
    <div class="flex flex-wrap mt-6 -mx-3">
        @foreach($this->leavesCollections as $leaveCollection)
            <div class="xl:w-1/3 w-full pb-6 xl:px-3">
                <div class="relative h-full bg-white flex flex-col">
                    <h2 class="px-4 py-3">{{ $leaveCollection['title'] }}</h2>
                    <table class="w-full table-auto border-collapse text-sm text-left">
                        <thead class="text-gray-300 bg-gray-800">
                            <th class="border border-gray-800 border-r-white px-4 py-3">{{ __('Employee') }}</th>
                            <th class="border border-gray-800 border-r-white px-4 py-3">{{ __('Start date') }}</th>
                            <th class="border border-gray-800 px-4 py-3">{{ __('End date') }}</th>
                            {{-- <th class="border border-gray-800 px-4 py-3">{{ __('Leave type') }}</th> --}}
                        </thead>
                        <tbody class="text-gray-normal">
                            @forelse ($leaveCollection['leaves'] as $leave)
                                <tr>
                                    <td class="border border-gray-darkest px-4 py-3 whitespace-no-wrap">{{ $leave->user->firstname }} {{ $leave->user->lastname }}</td>
                                    <td class="border border-gray-darkest px-4 py-3 whitespace-no-wrap">{{ date('d/m/Y', strtotime($leave->start_date)) }}</td>
                                    <td class="border border-gray-darkest px-4 py-3 whitespace-no-wrap">{{ date('d/m/Y', strtotime($leave->end_date)) }}</td>
                                    {{-- <td class="border border-gray-darkest px-4 py-3 whitespace-no-wrap">{{ $leave->leave_type_name }}</td> --}}
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="13" class="border border-gray-500 text-center px-4 py-3 whitespace-no-wrap">{{ __('No data available in table') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    </div>
</div>
