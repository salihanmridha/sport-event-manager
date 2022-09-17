<div class="row">
    <div class="card-controls sm:flex">
        <div class="w-full sm:w-1/2">
            @can('event_delete')
                <button class="btn btn-success ml-3 disabled:opacity-50 disabled:cursor-not-allowed" type="button"
                        wire:click="confirm('inviteSelected')"
                        wire:loading.attr="disabled" {{ $this->selectedCount ? '' : 'disabled' }}>
                    Invite selected {{ $this->event->application_type == 'team' ? 'teams' : 'players' }}
                </button>
            @endcan
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
            <div class="card-body">
                <div class="pt-3">
                    @if($this->event->application_type == 'individual')
                        <table class="table table-view">
                            <thead class="bg-white">
                            <tr>
                                <th></th>
                                <th>
                                    {{ trans('cruds.event.player.no') }}
                                    @include('components.table.sort', ['field' => 'id'])
                                </th>
                                <th>
                                    {{ trans('cruds.event.player.full_name') }}
                                </th>
                                <th>
                                    {{ trans('global.email') }}
                                    @include('components.table.sort', ['field' => 'email'])
                                </th>
                                <th>
                                    {{ trans('global.gender') }}
                                    @include('components.table.sort', ['field' => 'gender'])

                                </th>
                                <th>
                                    {{ trans('global.phone') }}
                                    @include('components.table.sort', ['field' => 'phone'])
                                </th>
                                <th>
                                    {{ trans('cruds.event.player.birth_date') }}
                                    @include('components.table.sort', ['field' => 'birth_date'])
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($players as $no => $player )
                                <tr>
                                    <td>
                                        <input type="checkbox" value="{{ $player->id }}" wire:model="selected">
                                    </td>
                                    <td>
                                        {{ $player->id }}
                                    </td>
                                    <td>
                                        {{ $player->first_name . ' ' . $player->last_name }}
                                    </td>
                                    <td>
                                        <span class="badge badge-relationship">{{ $player->email }}</span>
                                    </td>
                                    <td>
                                        {{ $player->gender }}
                                    </td>
                                    <td>
                                        {{ $player->phone }}
                                    </td>
                                    <td>
                                        {{ $player->birth_date }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">No entries found.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    @else
                        <table class="table table-view">
                            <thead class="bg-white">
                            <tr>
                                <th></th>
                                <th>
                                    {{ trans('cruds.event.player.no') }}
                                    @include('components.table.sort', ['field' => 'id'])
                                </th>
                                <th>
                                    Team name
                                    @include('components.table.sort', ['field' => 'name'])
                                </th>
                                <th>
                                    sport
                                    @include('components.table.sort', ['field' => 'sport:name'])
                                </th>
                                <th>
                                    Coach
                                </th>
                                <th>
                                    Owner
                                </th>
                                <th>
                                    No of members
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($players as $no => $team )
                                <tr>
                                    <td>
                                        <input type="checkbox" value="{{ $team->id }}" wire:model="selected">
                                    </td>
                                    <td>
                                        {{ $team->id }}
                                    </td>
                                    <td>
                                        {{ $team->name }}
                                    </td>
                                    <td>
                                        <span class="badge badge-relationship">{{ $team->sport?->name }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-relationship">{{ get_info_by_role($team->teamMember, 'Team Coach') }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-relationship">{{ get_info_by_role($team->teamMember, 'Team Owner') }}</span>
                                    </td>
                                    <td>
                                        {{ $team->teamMember?->count() }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">No entries found.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    @endif
                </div>
                <div class="form-group">
                    <a href="{{ route('admin.events.show',['event' => $this->event, 'eventType' => $eventType]) }}"
                       class="btn btn-secondary">
                        {{ trans('global.back') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="pt-3">
            {{ $players->appends(['eventType' => request('eventType')])->links() }}
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
