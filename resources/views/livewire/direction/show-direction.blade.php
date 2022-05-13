<div class="text-left pl-10 py-6">
    @push('styles')
        <link type="text/css" rel="stylesheet" href="https://api.mqcdn.com/sdk/mapquest-js/v1.3.2/mapquest.css"/>
    @endpush
    <div class="font-semibold uppercase text-blue-dark mb-6">
        {{ __('Direction') }}
    </div>
    <div class="flex flex-wrap -mx-3">
        <div wire:poll.1000ms class="w-1/3 pb-6 px-3">
            <div class="flex flex-wrap justify-items-center gap-1 text-gray-500 mb-3">
                <span class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
                <span>{{ now()->format('H:s') }} {{ __('Current time') }}</span>
            </div>
            <div class="overflow-y-scroll max-h-160">
                @foreach ($stations as $station)
                    <div class="station rounded-lg shadow-lg bg-white p-3 mb-3">
                        @php
                            $isPast   = now()->setTimeFromTimeString($station['departureTime'])->isPast();
                            $isFuture = now()->setTimeFromTimeString($station['arrivalTime'])->isFuture();
                        @endphp
                        <div class="{{ $isPast ? 'text-red-500' : ($isFuture ? 'text-orange-500' : 'text-green-500') }} text-uppercase px-1 py-2">
                            {{ __($isPast ? 'Already left' : ($isFuture ? 'Not arrive yet' : 'Current station')) }}
                        </div>
                        <hr class="bg-gray-200 mb-3" />
                        <div class="border-l-4 border-l-green-500 px-2 mb-3">
                            <div class="text-black text-2xl font-medium">{{ $station['street'] }}</div>
                            <div class="text-gray-400 text-base">{{ $station['city'] }}</div>
                        </div>
                        <div class="flex flex-wrap">
                            <div class="w-1/2 flex flex-wrap gap-1 text-green-500">
                                <span class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </span>
                                <span>{{ $station['arrivalTime'] }} {{ __('Arrival time') }}</span>
                            </div>
                            <div class="w-1/2 flex flex-wrap gap-1 text-red-500">
                                <span class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </span>
                                <span>{{ $station['departureTime'] }} {{ __('Departure time') }}</span>
                            </div>
                            {{-- <div class="w-1/3 flex flex-wrap gap-1 text-gray-500">
                                <span class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </span>
                                <span>{{ __('Persons') }}</span>
                            </div> --}}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div wire:ignore class="w-2/3 pb-6 px-3">
            <div id="map-wrapper" class="h-160">
                <div id="map"></div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="https://api.mqcdn.com/sdk/mapquest-js/v1.3.2/mapquest.js"></script>
        <script>
            document.addEventListener('livewire:load', function () {
                let direction    = @js($direction);
                let timer        = moment(direction.time, 'hh:mm:ss');
                const waitTime   = 300; // 5 seconds
                const timeFormat = 'hh:mm';

                L.mapquest.key = 'Qhlo93BGVm24y16mIFvx7vyAGP46IFZ8';

                let locations = direction.stations.map((station) => ({latLng: {lat: station.latitude, lng: station.longitude}}));

                let map = L.mapquest.map('map', {
                    center: [34.0209, -6.8000],
                    layers: L.mapquest.tileLayer('map'),
                    zoom: 100
                });

                let directions = L.mapquest.directions();
                directions.setLayerOptions({
                    startMarker: {
                        draggable: false
                    },
                    endMarker: {
                        draggable: false
                    },
                    routeRibbon: {
                        draggable: false
                    }
                });
                directions.route({
                    start: locations[0],
                    end: locations[locations.length - 1],
                    waypoints: locations.slice(1, locations.length - 1),
                });

                fetchStations(locations, {timer, waitTime, timeFormat}).then((stations) => @this.setStations(JSON.stringify(stations)));
            });

            function fetchStations(
                locations,
                {
                    timer = moment(),
                    key = 'Qhlo93BGVm24y16mIFvx7vyAGP46IFZ8',
                    url = 'http://www.mapquestapi.com/directions/v2/route',
                    waitTime = 0,
                    timeFormat = 'hh:mm:ss',
                } = {}
            ) {
                return axios.get(url, {
                    headers: {
                        'Accept': 'application/json',
                    },
                    params: {
                        key,
                        json: {
                            locations,
                            options: {
                                shapeFormat: 'cmp6',
                                timeType: 1,
                                useTraffic: true,
                                conditionsAheadDistance: 200,
                                generalize: 0
                            }
                        }
                    }
                })
                .then(function (response) {
                    let {legs, locations: stations, locationSequence: sequences} = response.data.route;

                    return sequences.map((sequence, index) => {
                        let {street, adminArea5: city} = stations[sequence];
                        let arrivalTime   = timer.seconds((index != 0) ? legs[sequence - 1].time : 0).format(timeFormat);
                        let departureTime = timer.seconds(waitTime).format(timeFormat);
                        return {street, city, arrivalTime, departureTime};
                    });
                });
            }
        </script>
    @endpush
</div>
