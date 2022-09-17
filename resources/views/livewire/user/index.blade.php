<div>
    <div class="card-controls sm:flex">
        <div class="w-full sm:w-1/2">
            Per page:
            <select wire:model="perPage" class="form-select w-full sm:w-1/6">
                @foreach ($paginationOptions as $value)
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </select>

            @can('user_delete')
                <button class="btn btn-rose ml-3 disabled:opacity-50 disabled:cursor-not-allowed" type="button"
                    wire:click="confirm('deleteSelected')" wire:loading.attr="disabled"
                    {{ $this->selectedCount ? '' : 'disabled' }}>
                    {{ __('Delete Selected') }}
                </button>
            @endcan
           
            <!-- Tạm ẩn theo yêu cầu của QA, do client chưa cần chức năng export -->                  
            @if (file_exists(app_path('Http/Livewire/ExcelExport.php')) && 1 == 0)         
                <livewire:excel-export model="User" format="csv" />
                <livewire:excel-export model="User" format="xlsx" />
                <livewire:excel-export model="User" format="pdf" /> 
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
                            {{ trans('cruds.user.fields.id') }}
                            @include('components.table.sort', ['field' => 'id'])
                        </th>
                        <th>
                            {{ trans('cruds.user.fields.first_name') }}
                            @include('components.table.sort', ['field' => 'first_name'])
                        </th>
                        <th>
                            {{ trans('cruds.user.fields.last_name') }}
                            @include('components.table.sort', ['field' => 'last_name'])
                        </th>
                        <th>
                            {{ trans('cruds.user.fields.email') }}
                            @include('components.table.sort', ['field' => 'email'])
                        </th>
                        <!-- <th>
                            {{ trans('cruds.user.fields.email_verified_at') }}
                            @include('components.table.sort', ['field' => 'email_verified_at'])
                        </th> -->
                        <th>
                            {{ trans('cruds.user.fields.roles') }}
                        </th>
                        <!-- <th>
                            {{ trans('cruds.user.fields.locale') }}
                            @include('components.table.sort', ['field' => 'locale'])
                        </th> -->
                        <th>
                            {{ trans('cruds.user.fields.status') }}
                            @include('components.table.sort', ['field' => 'status'])
                        </th>
                        <th>
                            {{ trans('cruds.user.fields.created_at') }}
                            @include('components.table.sort', ['field' => 'created_at'])
                        </th>
                        <th>
                            {{ trans('cruds.user.fields.updated_at') }}
                            @include('components.table.sort', ['field' => 'updated_at'])
                        </th>
                        <th>
                            {{ trans('Action ') }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>
                                <input type="checkbox" value="{{ $user->id }}" wire:model="selected">
                            </td>
                            <td>
                                {{ $user->id ?? '__' }}
                            </td>
                            <td>
                                {{ $user->first_name ?? '__' }}
                            </td>
                            <td>
                                {{ $user->last_name ?? '__' }}
                            </td>
                            <td>
                                <a class="link-light-blue" href="mailto:{{ $user->email ?? '__' }}">
                                    <i class="far fa-envelope fa-fw">
                                    </i>
                                    {{ $user->email ?? '__' }}
                                </a>
                            </td>
                            <!-- <td>
                                {{ $user->email_verified_at ?? '__' }}
                            </td> -->
                            <td>
                                @foreach ($user->roles as $key => $entry)
                                    <span class="badge badge-relationship">{{ $entry->title ?? '__' }}</span>
                                @endforeach
                            </td>
                            <!-- <td>
                                {{ $user->locale ?? '__' }}
                            </td> -->
                            <td>
                                {{ $user->status ?? '__' }}
                            </td>
                            <td>
                                {{ $user->created_at?->format('M-d-Y h:i:s A') ?? '__' }}
                            </td>
                            <td>
                                {{ $user->updated_at?->format('M-d-Y h:i:s A') ?? '__' }}
                            </td>
                            <td>
                                <div class="flex justify-end">
                                    @can('user_show')
                                        <a class="btn btn-sm btn-info mr-2" href="{{ route('admin.users.show', ['type' => request('type'), $user]) }}">
                                            {{ trans('global.view') }}
                                        </a>
                                    @endcan
                                    @can('user_edit')
                                        <a class="btn btn-sm btn-success mr-2"
                                            href="{{ route('admin.users.edit', ['type' => request('type'), $user]) }}">
                                            {{ trans('global.edit') }}
                                        </a>
                                    @endcan
                                    @can('user_delete')
                                        <button class="btn btn-sm btn-rose mr-2" type="button"
                                            wire:click="confirm('delete', {{ $user->id }})"
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
            {{ $users->links() }}
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
