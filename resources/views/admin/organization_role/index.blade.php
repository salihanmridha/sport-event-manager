@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="card bg-white">
        <div class="card-header border-b border-blueGray-200">
            <div class="card-header-container">
                <h6 class="card-title">
                    {{ trans('cruds.organizationRole.title') }}
                    {{ trans('global.list') }}
                </h6>

                @can('country_create')
                    <a class="btn btn-indigo" href="{{ route('admin.organization-role.create') }}">
                        {{ trans('global.add') }} {{ trans('cruds.organizationRole.title') }}
                    </a>
                @endcan
            </div>
        </div>
        @livewire('organization-role.index')

    </div>
</div>
@endsection