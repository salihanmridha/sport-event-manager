@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="card bg-blueGray-100">
        <div class="card-header">
            <div class="card-header-container">
                <h6 class="card-title">
                    {{ trans('global.view') }}
                    {{ trans('cruds.organizationRole.title') }}:
                    {{ trans('cruds.organizationRole.fields.no') }}
                    {{ $orgRole->id }}
                </h6>
            </div>
        </div>

        <div class="card-body">
            <div class="pt-3">
                <table class="table table-view">
                    <tbody class="bg-white">
                        <tr>
                            <th>
                                {{ trans('cruds.organizationRole.fields.no') }}
                            </th>
                            <td>
                                {{ $orgRole->id }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.organizationRole.fields.name') }}
                            </th>
                            <td>
                                {{ $orgRole->name }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.organizationRole.fields.code') }}
                            </th>
                            <td>
                                {{ $orgRole->code }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.organizationRole.fields.created_by') }}
                            </th>
                            <td>
                                @if($orgRole->creator->email)
                                    <h1 class="badge badge-relationship">{{ $orgRole->creator->email ?? '' }}</h1>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="form-group">
                @can('country_edit')
                    <a href="{{ route('admin.organization-role.edit', $orgRole) }}" class="btn btn-indigo mr-2">
                        {{ trans('global.edit') }}
                    </a>
                @endcan
                <a href="{{ route('admin.organization-role.index') }}" class="btn btn-secondary">
                    {{ trans('global.back') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
