<div class="mt-8">
    {{-- overflow-x-hidden overflow-y-auto fixed inset-0 z-50 outline-none focus:outline-none justify-center items-center flex --}}
    {{-- hidden overflow-x-hidden overflow-y-auto fixed inset-0 z-50 outline-none focus:outline-none justify-center items-center --}}
    <div class="{{$showInvite?"flex": "hidden"}} overflow-x-hidden overflow-y-auto fixed inset-0 z-50 outline-none focus:outline-none justify-center items-center"
        id="small-modal-id">
        
            <div class="relative my-6 mx-auto max-w-sm">
                <!--content-->
                <form wire:submit.prevent="invite" class="pt-3">
                <div
                    class="border-0 rounded-lg shadow-lg relative flex flex-col w-full bg-white outline-none focus:outline-none">
                    <!--header-->
                    <div
                        class="flex items-start justify-between p-4 border-b border-solid border-blueGray-200 rounded-t">
                        <h3 class="text-3xl font-semibold">
                            Invite Member
                        </h3>
                        <button
                            class="p-1 ml-auto bg-transparent border-0 text-black opacity-5 float-right text-3xl leading-none font-semibold outline-none focus:outline-none"
                            onclick="toggleModal('small-modal-id')">
                            <span
                                class="bg-transparent text-black opacity-5 h-6 w-6 text-2xl block outline-none focus:outline-none">
                                ×
                            </span>
                        </button>
                    </div>
                    <!--body-->
                    <div class="relative p-6 flex-auto">
                        <div class="form-group flex">
                            <label class="form-label mr-8" for="email">To</label>
                            <div class="form-group {{ $errors->has('invite.email') ? 'invalid' : '' }}" style="width: 400px">
                                <x-select-list class="form-control" id="email" name="email" :options="$this->listsForFields['users']" wire:model="invite.email" />
                                <div class="validation-message">
                                    {{ $errors->first('invite.email') }}
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <!--footer-->
                    <div class="flex items-center justify-end p-6 border-t border-solid border-blueGray-200 rounded-b">
                        <button
                            class="text-red-500 background-transparent font-bold uppercase px-6 py-2 text-sm outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
                            type="button" wire:click="toggleShowInvite()">
                            Discard
                        </button>
                        <button
                            class="btn btn-info bg-emerald-500 text-white active:bg-emerald-600 font-bold uppercase text-sm px-6 py-3 rounded shadow hover:shadow-lg outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
                            type="submit" >
                            Invite
                        </button>
                    </div>
                </div>
                </form>
            </div>
    </div>
    <div class="{{$showInvite?"flex": "hidden"}} opacity-25 fixed inset-0 z-40 bg-black" id="small-modal-id-backdrop" style="background: black; opacity: 25%;"></div>
    {{-- <script type="text/javascript">
        function toggleModal(modalID) {
            document.getElementById(modalID).classList.toggle("hidden");
            document.getElementById(modalID + "-backdrop").classList.toggle("hidden");
            document.getElementById(modalID).classList.toggle("flex");
            document.getElementById(modalID + "-backdrop").classList.toggle("flex");
        }
    </script> --}}
    <a wire:click="toggleShowInvite()" class="btn btn-success mr-2">
        {{ trans('global.invite_member') }}
    </a>
    <a href="{{ route('admin.teams.team_block.index', $team) }}" class="btn btn-info">
        {{ trans('global.team_block') }}
    </a>
    <a href="{{ route('admin.team.member_request', $team->id) }}" class="btn btn-rose">
        {{ trans('global.member_request') }}
    </a>
    {{-- <a href="{{ route('admin.teams.index') }}" class="btn btn-secondary">
        {{ trans('global.back') }}
    </a> --}}
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
                        @foreach ($team_member->member_role as $title)
                            {{ $title->title }}
                        @endforeach
                    </td>
                    <td> {{-- #10 Tiền đạo - 1 --}}
                        {{ $team_member->jersey_number ?? '' }} {{ $team_member->player_role }}
                    </td>
                    <td>
                        {{ $team_member->status }}
                    </td>
                    <td>
                        <div class="flex justify-end">
                            @can('team_member_set_owner')
                                <button class="btn btn-sm btn-info mr-2" type="button"
                                    wire:click="setOwner({{ $team_member->id }})" wire:loading.attr="disabled">
                                    Set owner
                                </button>
                            @endcan
                            @can('team_show')
                                <a class="btn btn-sm btn-info mr-2" href="{{ route('admin.teams.show', $team) }}">
                                    {{ trans('global.view') }}
                                </a>
                            @endcan
                            @can('team_edit')
                                <a class="btn btn-sm btn-success mr-2"
                                    href="{{ route('admin.teams.members.edit', [$team, 'member' => $team_member]) }}">
                                    {{ trans('global.edit') }}
                                </a>
                            @endcan
                            @can('team_edit')
                                <button class="btn btn-sm btn-rose mr-2" type="button"
                                    wire:click="confirm('delete', {{ $team_member->id }})" wire:loading.attr="disabled">
                                    {{ trans('global.delete') }}
                                </button>
                            @endcan
                            @can('team_edit')
                                <button class="btn btn-sm btn-info mr-2" type="button"
                                    onclick="confirm('Are you sure to block user this') || event.stopImmediatePropagation()"
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
