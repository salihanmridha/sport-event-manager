<div>
    <div class="card-controls sm:flex">
        <div class="w-full sm:w-1/2">
            Per page:
            <select wire:model="perPage" class="form-select w-full sm:w-1/6">
                @foreach($paginationOptions as $value)
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </select>

            @can('venue_type_delete')
                <button class="btn btn-rose ml-3 disabled:opacity-50 disabled:cursor-not-allowed" type="button"
                        wire:click="confirm('deleteSelected')"
                        wire:loading.attr="disabled" {{ $this->selectedCount ? '' : 'disabled' }}>
                    {{ __('Delete Selected') }}
                </button>
            @endcan

            @if(file_exists(app_path('Http/Livewire/ExcelExport.php')))
                <livewire:excel-export model="Country" format="csv"/>
                <livewire:excel-export model="Country" format="xlsx"/>
                <livewire:excel-export model="Country" format="pdf"/>
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
                        {{ trans('cruds.venue-type.fields.id') }}
                        @include('components.table.sort', ['field' => 'id'])
                    </th>
                    <th>
                        {{ trans('cruds.venue-type.fields.code') }}
                        @include('components.table.sort', ['field' => 'code'])
                    </th>                    
                    <th>
                        {{ trans('cruds.venue-type.fields.name') }}
                        @include('components.table.sort', ['field' => 'name'])
                    </th>
                    <th>
                        {{ trans('cruds.venue-type.fields.created_at') }}
                        @include('components.table.sort', ['field' => 'created_at'])
                    </th>
                    <th>
                        {{ trans('cruds.venue-type.fields.updated_at') }}
                        @include('components.table.sort', ['field' => 'updated_at'])
                    </th>
                    <th class="w-28">
                        {{ trans('cruds.venue-type.fields.creator') }}
                        @include('components.table.sort', ['field' => 'creator.email'])

                    </th>
                    <th>
                        {{ trans('cruds.venue-type.fields.action') }}

                    </th>
                    <th>
                    </th>
                </tr>
                </thead>
                <tbody>
                @forelse($venueTypes as $venueType)
                    <tr>
                        <td>
                            <input type="checkbox" value="{{ $venueType->id }}" wire:model="selected">
                        </td>
                        <td>
                            {{ $venueType->id }}
                        </td>
                        <td>
                            {{ $venueType->code }}
                        </td>
                        <td>
                            {{ $venueType->name }}
                        </td>
                        <td>
                            {{ $venueType->created_at }}
                        </td>
                        <td>
                            {{ $venueType->updated_at }}
                        </td>
                        <td>
                            @if(isset($venueType->creator->email))
                                <span class="badge badge-relationship">{{ $venueType->creator->email ?? '' }}</span>
                             @endif

                        </td>

                        <td>
                            <div class="flex justify-start">
                                @can('venue_type_show')
                                    <a class="btn btn-sm btn-info mr-2" href="{{ route('admin.venue-types.show', $venueType) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan
                                @can('venue_type_edit')
                                    <a class="btn btn-sm btn-success mr-2"
                                       href="{{ route('admin.venue-types.edit', $venueType) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan
                                @can('venue_type_delete')
                                    <button class="btn btn-sm btn-rose mr-2" type="button"
                                            wire:click="confirm('delete', {{ $venueType->id }})"
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
            {{--            {{ $skill->links() }}--}}
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
