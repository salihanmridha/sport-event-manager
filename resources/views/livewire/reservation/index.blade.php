<div>
    <div class="card-controls sm:flex">
        <div class="w-full sm:w-1/4">
            Per page:
            <select wire:model="perPage" class="form-select w-full sm:w-1/6">
                @foreach($paginationOptions as $value)
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </select>

            @if(file_exists(app_path('Http/Livewire/ExcelExport.php')))
                <livewire:excel-export model="VenueBooking" format="csv"/>
                <livewire:excel-export model="VenueBooking" format="xlsx"/>
                <livewire:excel-export model="VenueBooking" format="pdf"/>
            @endif

        </div>
        <div class="w-full sm:w-1/6  sm:text-right align-items-center mr-2">
            <label class="mt-3">Date:</label>
        </div>
        <div class="w-full sm:w-1/5 sm:text-right">
            <x-date-picker picker="range" class="inline-block w-full" wire:model="filters.date_range" id="date_range"
                           name="date_range"/>
        </div>
        <div class="w-full sm:w-1/5 sm:text-right">
            Responding:
            <select class="form-control sm:w-1/2" wire:model="filters.response">
                <option value="">All</option>
                @foreach(\App\Enums\VenueBookingStatus::toArray() as $key => $value)
                    <option @if($key == 0) selected @endif value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
        </div>
        <div class="w-full sm:w-1/5 sm:text-right">
            Search:
            <input type="text" wire:model.debounce.300ms="search" class="w-full sm:w-1/3 inline-block"/>
        </div>
    </div>
    <div wire:loading.delay>
        Loading...
    </div>

    <div class="overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table table-index w-full">
                <thead>
                <tr>
                    <th class="w-9">
                    </th>
                    <th class="w-28">
                        {{ trans('cruds.venue.fields.id') }}
                        @include('components.table.sort', ['field' => 'id'])
                    </th>
                    <th>
                        {{ trans('cruds.event.title') }}
                        @include('components.table.sort', ['field' => 'event.title'])
                    </th>
                    <th>
                        {{ trans('cruds.reservation.fields.schedule') }}
                    </th>
                    <th>
                        {{ trans('cruds.reservation.fields.requester') }}
                    </th>
                    <th>
                    {{ trans('cruds.reservation.fields.requested_at') }}
                    @include('components.table.sort', ['field' => 'created_at'])
                    <th>
                        {{ trans('cruds.reservation.fields.responding') }}
                    </th>
                    <th>
                        {{ trans('cruds.reservation.fields.action') }}
                    </th>
                </tr>
                </thead>
                <tbody>
                @forelse($reservations as $key => $reservation)
                    <tr>
                        <td>
                            <label>
                                <input type="checkbox" value="{{ $reservation->id }}" wire:model="selected">
                            </label>
                        </td>
                        <td>
                            {{ $reservation->id }}
                        </td>

                        <td>
                            {{ $reservation->event?->title }}
                        </td>

                        <td>
                            {{ $reservation->event?->start_date_time}} <br>- {{$reservation->event?->end_date_time  }}
                        </td>
                        <td>
                            <span>{{ $reservation->event->user_create?->first_name. ' '. $reservation->event->user_create?->last_name }}</span>
                        </td>
                        <td>
                            {{ $reservation->created_at }}
                        </td>
                        <td>
                            {{ $reservation->response?->label }}
                        </td>
                        <td>
                            @if($reservation->response != \App\Enums\VenueBookingStatus::booked())
                                <a class="btn btn-sm btn-success mr-2" href="{{ route('admin.reservations.show', $reservation) }}">
                                    Response
                                </a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10">No entries found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-body">
        <div class="pt-3">
            @if($this->selectedCount)
                <p class="text-sm leading-5">
                    <span class="font-medium">
                        {{ $this->selectedCount }}
                    </span>
                    {{ __('Entries selected') }}
                </p>
            @endif
            {{ $reservations->links() }}
        </div>
    </div>
</div>

@push('scripts')
    <script>
        Livewire.on('confirm', e => {
            if (!confirm("{{ trans('global.areYouSure') }}")) {
                return
            }
        @this
            [e.callback](...e.argv)
        })
    </script>
@endpush

