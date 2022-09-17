<div>
    <div class="card-controls sm:flex">
        <div class="w-full sm:w-1/2">
            Per page:
            <select wire:model="perPage" class="form-select w-full sm:w-1/6">
                @foreach($paginationOptions as $value)
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </select>

            @can('announcements_delete')
                <button class="btn btn-rose ml-3 disabled:opacity-50 disabled:cursor-not-allowed" type="button"
                        wire:click="confirm('deleteSelected')"
                        wire:loading.attr="disabled" {{ $this->selectedCount ? '' : 'disabled' }}>
                    {{ __('Delete Selected') }}
                </button>
            @endcan

        </div>
        <div class="w-full sm:w-1/2 sm:text-right">
            Filter:
            <select wire:model="filter" class="form-select w-full sm:w-1/6">
                @foreach($filterArray as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>

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
                        {{ trans('cruds.announcement.fields.id') }}
                        @include('components.table.sort', ['field' => 'id'])
                    </th>
                    <th>
                        {{ trans('cruds.announcement.fields.title') }}
                        @include('components.table.sort', ['field' => 'title'])
                    </th>
                    <th class="w-28">
                        {{ trans('cruds.announcement.fields.creator') }}
                        @include('components.table.sort', ['field' => 'creator.email'])
                    </th>

                    <th>
                        {{ trans('cruds.announcement.fields.start_date') }}
                        @include('components.table.sort', ['field' => 'start_date'])
                    </th>
                    <th>
                        {{ trans('cruds.announcement.fields.end_date') }}
                        @include('components.table.sort', ['field' => 'end_date'])
                    </th>
                    <th>
                        {{ trans('cruds.announcement.fields.status') }}
                        @include('components.table.sort', ['field' => 'status'])
                    </th>
                    <th>
                        {{ trans('cruds.announcement.fields.action') }}
                    </th>
                    <th>
                    </th>
                </tr>
                </thead>
                <tbody>
                @forelse($announcements as $announcement)
                    <tr>
                        <td>
                            <input type="checkbox" value="{{ $announcement->id }}" wire:model="selected">
                        </td>

                        <td>
                            {{ $announcement->id }}
                        </td>
                        <td>
                            {{ $announcement->title }}
                        </td>
                        <td>
                            @if(isset($announcement->creator->email))
                                <span class="badge badge-relationship">{{ $announcement->creator->email ?? '' }}</span>
                            @endif

                        <td>
                            {{ $announcement->start_date?->format('M-d-Y h:i:s A') ?? '__'  }}
                        </td>
                        <td>
                            {{ $announcement->end_date?->format('M-d-Y h:i:s A') ?? '__'  }}
                        </td>
                        <td>
                            @if ($announcement->status == 'publish' and ($announcement->start_date <= now() and now() <= $announcement->end_date))
                                <span class="btn btn-sm btn-warning mr-2">{{ trans('global.ongoing') }}</span>
                            @elseif ($announcement->status == 'publish' &&  now() > $announcement->end_date)
                                <span class="btn btn-sm btn-danger mr-2">{{ trans('global.expired') }}</span>
                            @elseif ($announcement->status == 'publish' and $announcement->start_date > now())
                                <span class="btn btn-sm btn-info mr-2">{{ $this->listsForFields['status'][$announcement->status] }}</span>
                            @else
                                <span class="btn btn-sm btn-success mr-2">{{ $this->listsForFields['status'][$announcement->status] }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="row flex justify-statr mb-0 p-0">

                                        <div class="col-4">
                                            @can('announcement_show')
                                                <a class="btn btn-sm btn-info mr-2"
                                                href="{{ route('admin.announcements.show', $announcement) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan
                                        </div>

                                            @if ($announcement->status == 'publish' and now() > $announcement->end_date)
                                                <div class="col-5 w-12">
                                            @else
                                                <div class="col-4">
                                                @can('announcement_edit')
                                                    <a class="btn btn-sm btn-success mr-2"
                                                    href="{{ route('admin.announcements.edit', $announcement) }}">
                                                        {{ trans('global.edit') }}
                                                    </a>
                                                @endcan
                                            @endif
                                        </div>
                                        <div class="col-4">
                                            @can('announcement_delete')
                                                <button class="btn btn-sm btn-rose mr-2" type="button"
                                                        wire:click="confirm('delete', {{ $announcement->id }})"
                                                        wire:loading.attr="disabled">
                                                    {{ trans('global.delete') }}
                                                </button>
                                            @endcan
                                        </div>

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
        <!--  $announcement->links() }}-->
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
