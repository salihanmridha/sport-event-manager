{{-- <div class="w-full sm:w-1/2 sm:text-right">
    Search:
    <input type="text" wire:model.debounce.300ms="search" class="w-full sm:w-1/3 inline-block" />
</div> --}}

<div class="overflow-hidden">
    <div class="overflow-x-auto">
        <table class="table table-index w-full">
            <thead>
                <tr>
                    <th class="w-28">
                        {{ trans('cruds.team-block.fields.no') }}
                    </th>
                    <th>
                        {{ trans('cruds.team-block.fields.account') }}
                    </th>
                    <th>
                        {{ trans('cruds.team-block.fields.email') }}
                    </th>
                    <th>
                        {{ trans('cruds.team-block.fields.blocked_at') }}
                    </th>
                    <th>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($usersBlocked as $userBlocked)
                    <tr>
                        <td>
                            {{ $userBlocked->id }}
                        </td>
                        <td>
                            {{ $userBlocked->name }}
                        </td>
                        <td>
                            {{ $userBlocked->email }}
                        </td>
                        <td>
                            {{ $userBlocked->created_at }}
                        </td>
                        <td>
                            <div class="flex justify-end">
                                @can('team_edit')
                                    <button class="btn btn-sm btn-rose mr-2" type="button" onclick="confirm('Are you sure to delete this') || event.stopImmediatePropagation()"
                                        wire:click="delete({{ $userBlocked->id }})" wire:loading.attr="disabled">
                                        {{ trans('global.delete') }}
                                    </button>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    {{-- @empty --}}
                        {{-- <tr>
                            <td colspan="10">No entries found.</td>
                        </tr> --}}
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
