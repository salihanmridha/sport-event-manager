<div>
    <div class="card-controls sm:flex">
        <div class="w-full sm:w-1/2">
            Per page:
            <select wire:model="perPage" class="form-select w-full sm:w-1/6">
                @foreach ($paginationOptions as $value)
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </select>

            @can('event_delete')
                <button class="btn btn-rose ml-3 disabled:opacity-50 disabled:cursor-not-allowed" type="button"
                    wire:click="confirm('deleteSelected')" wire:loading.attr="disabled"
                    {{ $this->selectedCount ? '' : 'disabled' }}>
                    {{ __('Delete Selected') }}
                </button>
            @endcan

            @if (file_exists(app_path('Http/Livewire/ExcelExport.php')) && 1 == 0)
                <livewire:excel-export model="Event" format="csv" />
                <livewire:excel-export model="Event" format="xlsx" />
                <livewire:excel-export model="Event" format="pdf" />
            @endif

        </div>
        <div class="w-full sm:w-1/2 sm:text-right">
            Search:
            <input type="text" wire:model.debounce.300ms="search" class="w-full sm:w-1/3 inline-block" />
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
                            {{ trans('cruds.event.fields.id') }}
                            @include('components.table.sort', ['field' => 'id'])
                        </th>
                        <th>
                            {{ trans('cruds.event.fields.title_management') }}
                            @include('components.table.sort', ['field' => 'title'])
                        </th>
                        <th>
                            {{ trans('cruds.event.fields.sport') }}
                            @include('components.table.sort', ['field' => 'sport.name'])
                        </th>
                        <th>
                            {{ trans('cruds.event.fields.owner') }}
                            @include('components.table.sort', ['field' => 'sport.event_ownership'])
                        </th>
                        <th>
                            {{ trans('cruds.event.fields.application_type') }}
                            @include('components.table.sort', ['field' => 'application_type'])
                        </th>
                        @if ($this->type == 'pickup')
                            <th>
                                {{ trans('cruds.event.fields.no_of_team') }}
                                @include('components.table.sort', ['field' => 'max_team'])
                            </th>
                        @endif
                        <th>
                            {{ trans('cruds.event.fields.no_of_member') }}
                            @include('components.table.sort', ['field' => 'max_player_per_team'])
                        </th>
                        <th>
                            {{ trans('cruds.event.fields.status') }}
                            @include('components.table.sort', ['field' => 'status'])
                        </th>
                        <th>
                            {{ trans('cruds.event.fields.fee') }}
                            @include('components.table.sort', ['field' => 'fee'])
                        </th>
                        <th>
                            {{ trans('cruds.event.fields.created_date') }}
                            @include('components.table.sort', ['field' => 'created_at'])
                        </th>
                        <th>
                            {{ trans('cruds.event.fields.created_by') }}
                            @include('components.table.sort', ['field' => 'creator_id'])
                        </th>
                        <th>
                            {{ trans('cruds.event.fields.last_update') }}
                            @include('components.table.sort', ['field' => 'updated_at'])
                        </th>
                        <th>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $event)
                        <tr>
                            <td>
                                <input type="checkbox" value="{{ $event->id }}" wire:model="selected">
                            </td>
                            <td>
                                {{ $event->id }}
                            </td>
                            <td>
                                {{ $event->title }}
                            </td>
                            <td>
                                @if ($event->sport)
                                    <span class="badge badge-relationship">{{ $event->sport->name ?? '' }}</span>
                                @endif
                            </td>
                            <td>
                                {{ $event->event_ownership }}
                            </td>
                            <td>
                                {{ $event->application_type_label }}
                            </td>
                            @if ($this->type == 'pickup')
                                <td>
                                    {{ $event->max_team }}
                                </td>
                            @endif
                            <td>
                                {{ $event->max_player_per_team }}
                            </td>
                            <td>
                                {{ $event->status?->label }}
                            </td>
                            <td>
                                {{ $event->fee }}
                            </td>
                            <td>
                                {{ $event->created_at?->format('M-d-Y h:i:s A') ?? '__' }}
                            </td>
                            <td>
                                {{ $event->user_create?->first_name . ' ' . $event->user_create?->last_name }}
                            </td>
                            <td>
                                {{ $event->updated_at?->format('M-d-Y h:i:s A') ?? '__' }}
                            </td>
                            <td>
                                <div class="flex justify-end">
                                    @can('event_show')
                                        <a class="btn btn-sm btn-info mr-2"
                                            href="{{ route('admin.events.show', ['eventType' => $event->event_type, $event]) }}">
                                            {{ trans('global.view') }}
                                        </a>
                                    @endcan
                                    @can('event_edit')
                                        @if (request('eventType') == 'league' || request('eventType') == 'sport' || request('eventType') == 'session')
                                            <a class="btn btn-sm btn-success mr-2"
                                                href="{{ route('admin.events.edit', ['eventType' => $event->event_type, $event]) }}">
                                                {{ trans('global.edit') }}
                                            </a>
                                        @endif
                                    @endcan
                                    @can('event_delete')
                                        <button class="btn btn-sm btn-rose mr-2" type="button"
                                            wire:click="confirm('delete', {{ $event->id }})"
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
            @if ($this->selectedCount)
                <p class="text-sm leading-5">
                    <span class="font-medium">
                        {{ $this->selectedCount }}
                    </span>
                    {{ __('Entries selected') }}
                </p>
            @endif
            {{ $events->appends(['eventType' => request('eventType')])->links() }}
        </div>
    </div>
</div>

@push('scripts')
    <script>
        Livewire.on('confirm', e => {
            if (!confirm("{{ trans('global.areYouSure') }}")) {
                return
            }
            @this[e.callback](...e.argv)
        })
    </script>
@endpush
