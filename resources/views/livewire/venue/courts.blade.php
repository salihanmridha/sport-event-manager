<div>
    <div class="card-controls sm:flex">
        <div class="w-full sm:w-1/2">
            Per page:
            <select wire:model="perPage" class="form-select w-full sm:w-1/6">
                @foreach($paginationOptions as $value)
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </select>


            @if(file_exists(app_path('Http/Livewire/ExcelExport.php')))
                <livewire:excel-export model="Venue" format="csv"/>
                <livewire:excel-export model="Venue" format="xlsx"/>
                <livewire:excel-export model="Venue" format="pdf"/>
            @endif

        </div>
        <div class="w-full sm:w-1/2 sm:text-right">
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

                    <th class="w-28">
                        {{ trans('cruds.venue.courts.id') }}
                        @include('components.table.sort', ['field' => 'id'])
                    </th>
                    <th>
                        {{ trans('cruds.venue.courts.name') }}
                        @include('components.table.sort', ['field' => 'name'])
                    </th>
                    <th>
                        {{ trans('cruds.venue.courts.sport') }}
                        {{-- @include('components.table.sort', ['field' => 'owner.email']) --}}
                    </th>
                    <th>
                        {{ trans('cruds.venue.courts.price') }}
                        @include('components.table.sort', ['field' => 'price'])
                    </th>
                    <th>
                        {{ trans('cruds.venue.courts.status') }}
                        @include('components.table.sort', ['field' => 'status'])
                    </th>
                    <th>
                        {{ trans('cruds.venue.courts.created_at') }}
                       @include('components.table.sort', ['field' => 'created_at'])
                    </th>
                    <th>
                        {{ trans('global.action') }}
                    </th>
                </tr>
                </thead>
                <tbody>
                @forelse($venues as $court)
                    <tr>
                        <td>
                            {{ $court->id }}
                        </td>
                        <td>
                           {{ $court->name }}
                        </td>
                        <td>
                          {{ $court->sports->name }}
                            {{-- <span class="badge badge-relationship"> {{ $court->owner?->email }}</span> --}}
                        </td>
                        <td>
                            {{ $court->price }}
                        </td>
                        <td>
                            {{ $court->status }}
                        </td>
                        <td>
                            {{ $court->created_at }}
                        </td>
                        <td>
                            <div class="flex justify-end">
                                @can('venue_show')
                                    <a class="btn btn-sm btn-info mr-2" href="{{ route('admin.venues.courts', $court) }}">
                                        {{-- {{ trans('global.view') }} --}}
                                        View
                                    </a>
                                @endcan
                            </div>
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
            {{ $venues->links() }}
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
