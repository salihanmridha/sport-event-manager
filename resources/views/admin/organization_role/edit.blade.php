@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="card bg-blueGray-100">
        <div class="card-header">
            <div class="card-header-container">
                <h6 class="card-title">
                    {{ trans('global.edit') }}
                    {{ trans('cruds.organizationRole.title') }}:
                    {{ trans('cruds.organizationRole.fields.no') }}
                    {{ $orgRole->id }}
                </h6>
            </div>
        </div>

        <div class="card-body">
            @livewire('organization-role.edit', [$orgRole])
        </div>
    </div>
</div>
@endsection