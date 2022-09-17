<div>
    <div class="card-controls sm:flex">
        <div class="w-full sm:w-1/2">
            Per page:
            <select wire:model="perPage" class="form-select w-full sm:w-1/6">
                @foreach($paginationOptions as $value)
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </select>

            @can('venue_delete')
                <button class="btn btn-rose ml-3 disabled:opacity-50 disabled:cursor-not-allowed" type="button"
                        wire:click="confirm('deleteSelected')"
                        wire:loading.attr="disabled" {{ $this->selectedCount ? '' : 'disabled' }}>
                    {{ __('Delete Selected') }}
                </button>
            @endcan

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
                    <th class="w-9">
                    </th>
                    <th class="w-28">
                        {{ trans('cruds.venue.fields.id') }}
                        @include('components.table.sort', ['field' => 'id'])
                    </th>
                    <th>
                        {{ trans('cruds.venue.fields.name') }}
                        @include('components.table.sort', ['field' => 'name'])
                    </th>
                    <th>
                        {{ trans('cruds.venue.fields.owner') }}
                        @include('components.table.sort', ['field' => 'owner.email'])
                    </th>
                    <th>
                        {{ trans('cruds.venue.fields.address') }}
                        @include('components.table.sort', ['field' => 'address'])
                    </th>
                    <th>
                        {{ trans('cruds.venue.fields.country') }}
                        @include('components.table.sort', ['field' => 'country.name'])
                    </th>
                    <th>
                        Contact info
{{--                        @include('components.table.sort', ['field' => 'created_at'])--}}
                    </th>
                    <th>
                        {{ trans('cruds.venue.fields.phone_code') }}
                        @include('components.table.sort', ['field' => 'phone_code'])
                    </th>
                    <th>
                        {{ trans('cruds.venue.fields.phone_number') }}
                        @include('components.table.sort', ['field' => 'phone_number'])
                    </th>
                    <th>
                        {{ trans('cruds.venue.fields.email') }}
                        @include('components.table.sort', ['field' => 'email'])
                    </th>
                    <th>
                        {{ trans('global.action') }}
                    </th>
                </tr>
                </thead>
                <tbody>
                @forelse($venues as $key => $venue)
                    <tr>
                        <td>
                            <label>
                                <input type="checkbox" value="{{ $venue->id }}" wire:model="selected">
                            </label>
                        </td>
                        <td>
                            {{ $venue->id }}
                        </td>
                        <td>
                           {{ $venue->name }}
                        </td>
                        <td>
                            <span class="badge badge-relationship"> {{ $venue->owner?->email }}</span>
                        </td>
                        <td>
                            {{ $venue->address }}
                        </td>
                        <td>
                            {{ $venue->country?->name }}
                        </td>
                        <td>
                            {{ $venue->phone_code }}
                        </td>
                        <td>
                            {{ $venue->phone_code }}
                        </td>
                        <td>
                            {{ $venue->updated_number }}
                        </td>
                        <td>
                            <span class="badge badge-relationship">{{ $venue->email }}</span>
                        </td>
                        <td>
                            <div class="flex justify-end">
                                @can('venue_show')
                                    <a class="btn btn-sm btn-info mr-2" href="{{ route('admin.venues.show', $venue) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                    <a class="btn btn-sm btn-info mr-2" href="{{ route('admin.venues.courts', $venue) }}">
                                        {{-- {{ trans('global.view') }} --}}
                                        Courts
                                    </a>
                                @endcan
                                @can('venue_edit')
                                    <a class="btn btn-sm btn-success mr-2"
                                       href="{{ route('admin.venues.edit', $venue) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan
                                @can('venue_delete')
                                    <button class="btn btn-sm btn-rose mr-2" type="button"
                                            wire:click="confirm('delete', {{ $venue->id }})"
                                            wire:loading.attr="disabled">
                                        {{ trans('global.delete') }}
                                    </button>
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
