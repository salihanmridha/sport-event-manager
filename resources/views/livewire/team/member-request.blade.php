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
                                {{ trans('cruds.member_request.fields.full_name') }}
                            </th>
                            <th>
                                {{ trans('cruds.member_request.fields.email') }}
                            </th>
                            <th>
                                {{ trans('cruds.member_request.fields.sports') }}
                            </th>
                            <th>
                                {{ trans('cruds.member_request.fields.member_of_team') }}
                            </th>
                            <th>
                                {{ trans('cruds.member_request.fields.request_date') }}
                            </th>
                            <th>
                                {{ trans('cruds.member_request.fields.action') }}
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($members as $member )
                            <tr>
                                <td>
                                    {{ $member->id }}
                                </td>
                                <td>
                                    <a class="bg-info link-light-blue" href="{{ route('admin.users.show', $member->user?->id) }}">{{$member->user?->first_name . ' ' . $member->user?->last_name}}</a>
                                </td>
                                <td>
                                    <span class="badge badge-relationship"> {{ $member->user?->email }}</span>
                                </td>
                                <td style="min-width: 250px">
                                    @if($member->user)
                                        @foreach($member->user->sports as $sport)
                                            <span class="badge badge-relationship"> {{ $sport->name }}</span>
                                        @endforeach
                                    @endif
                                </td>
                                <td>
                                    {{ $member->user?->member_teams_count }}
                                </td>
                                <td>
                                    {{ $member->created_at }}
                                </td>
                                <td>
                                    <div class="flex justify-end">
                                        <button class="btn btn-sm btn-success mr-2" type="button"
                                                wire:click="confirm('updateResponse', {{ $member->id }}, {{ 1 }})"
                                                wire:loading.attr="disabled">
                                            Accept
                                        </button>
                                        <button class="btn btn-sm btn-rose mr-2" type="button"
                                                wire:click="confirm('updateResponse', {{ $member->id }}, {{ 0 }})"
                                                wire:loading.attr="disabled">
                                            Declined
                                        </button>
                                    </div>
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
                    <a href="{{ route('admin.teams.show', $this->teamId) }}"
                       class="btn btn-secondary">
                        {{ trans('global.back') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="pt-3">
            {{ $members->links() }}
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
