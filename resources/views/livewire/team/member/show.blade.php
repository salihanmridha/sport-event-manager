<div>
    <table class="table table-index w-full mt-7">
        <thead>
            <tr>
                <th class="w-28">
                    {{ trans('cruds.team.fields.no') }}
                </th>
                <th>
                    {{ trans('cruds.team.fields.name_member') }}
                </th>
                <th>
                    {{ trans('cruds.team.fields.role') }}
                </th>
                <th>
                    {{ trans('cruds.team.fields.roster') }}
                </th>
                <th>
                    {{ trans('cruds.team.fields.status') }}
                </th>
                <th>
                </th>
            </tr>
        </thead>
        <tbody>
            @forelse($team_members as $team_member)
                <tr>
                    <td>
                        {{ $team_member->id }}
                    </td>
                    <td>
                        {{ $team_member->team_member?->name }}
                    </td>
                    <td>
                        @foreach ( $team_member->member_role as $title )
                        {{ $title->title }} 
                        @endforeach
                    </td>
                    <td> {{-- #10 Tiền đạo - 1--}}
                        {{ $team_member->jersey_number ?? '' }} {{ $team_member->player_role }}
                    </td>
                    <td>
                        {{ $team_member->status }}
                    </td>
                    <td>
                        <div class="flex justify-end">
                            @can('team_member_set_owner')
                                <button class="btn btn-sm btn-info mr-2" type="button" wire:click="setOwner({{ $team_member->id }})" wire:loading.attr="disabled">
                                    Set owner
                                </button>
                            @endcan
                            @can('team_show')
                                <a class="btn btn-sm btn-info mr-2" href="{{ route('admin.teams.show', $team) }}">
                                    {{ trans('global.view') }}
                                </a>
                            @endcan
                            @can('team_edit')
                                <a class="btn btn-sm btn-success mr-2" href="{{ route('admin.teams.members.edit', [$team, 'member' => $team_member]) }}">
                                    {{ trans('global.edit') }}
                                </a>
                            @endcan
                            @can('team_edit')
                                <button class="btn btn-sm btn-rose mr-2" type="button" wire:click="confirm('delete', {{ $team_member->id }})" wire:loading.attr="disabled">
                                    {{ trans('global.delete') }}
                                </button>
                            @endcan
                            @can('team_edit')
                                <button class="btn btn-sm btn-info mr-2" type="button" onclick="confirm('Are you sure to block user this') || event.stopImmediatePropagation()"
                                wire:click="block({{ $team_member->id }})" wire:loading.attr="disabled">
                                    {{ trans('global.block') }}
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