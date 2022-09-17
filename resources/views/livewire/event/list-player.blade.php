<div class="row">
    <div class="overflow-hidden">
        <div class="overflow-x-auto">
            <div class="card-body">
                <div class="pt-3">
                    <table class="table table-view">
                        <thead class="bg-white">
                        <tr>
                            <th>
                                {{ trans('cruds.event.player.no') }}
                            </th>
                            <th>
                                {{ trans('cruds.event.player.player_name') }}
                            </th>
                            <th>
                                {{ trans('cruds.event.player.team') }}
                            </th>
                            <th>
                                {{ trans('cruds.event.player.position') }}
                            </th>
                            <th>
                                {{ trans('cruds.event.player.event') }}
                            </th>
                            <th>
                                {{ trans('cruds.event.player.joined') }}
                            </th>
                            <th></th>

                        </tr>
                        </thead>
                        <tbody>
                        @forelse($players as $no => $player )
                            <tr>
                                <td>
                                    {{ ++$no }}
                                </td>
                                <td>
                                    {{ $player->first_name . ' ' . $player->last_name }}
                                </td>
                                <td>
                                    {{ $player->squad_name }}
                                </td>
                                <td>
                                    {{ $player->position_name }}
                                </td>
                                <td>
                                    {{ $player->title }}
                                </td>
                                <td>
                                    {{ $player->created_at }}
                                </td>
                                <td>
                                    @can('list_player_delete')
                                        <button class="btn btn-sm btn-rose mr-2" type="button" wire:click="confirm('delete', {{ $player->event_id }}, {{ $player->user_id }})" wire:loading.attr="disabled">
                                            {{ trans('global.delete') }}
                                        </button>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">No entries found.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="form-group">
                    <a href="{{ route('admin.events.show', ['eventType' => $eventType, 'event' => $eventId]) }}"
                       class="btn btn-secondary">
                        {{ trans('global.back') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="pt-3">
            {{ $players->appends(['eventType' => $eventType])->links() }}
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
