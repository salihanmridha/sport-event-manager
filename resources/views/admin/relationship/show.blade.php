@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="card bg-blueGray-100">
        <div class="card-header">
            <div class="card-header-container">
                <h6 class="card-title">
                    {{ trans('global.view') }}
                    {{ trans('cruds.contactRelationship.title_singular') }}:
                    {{ trans('cruds.contactRelationship.fields.id') }}
                    {{ $contactRelationship->id }}
                </h6>
            </div>
        </div>

        <div class="card-body">
            <div class="pt-3">
                <table class="table table-view">
                    <tbody class="bg-white">
                        <tr>
                            <th>
                                {{ trans('cruds.contactRelationship.fields.id') }}
                            </th>
                            <td>
                                {{ $contactRelationship->id }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.contactRelationship.fields.name') }}
                            </th>
                            <td>
                                {{ $contactRelationship->name }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.contactRelationship.fields.code') }}
                            </th>
                            <td>
                                {{ $contactRelationship->code }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.contactRelationship.fields.creator_id') }}
                            </th>
                            <td>
                                {{ $contactRelationship->user_create?->email }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="form-group">
                @can('relationship_edit')
                    <a href="{{ route('admin.relationship.edit', $contactRelationship) }}" class="btn btn-indigo mr-2">
                        {{ trans('global.edit') }}
                    </a>
                @endcan
                <a href="{{ route('admin.relationship.index') }}" class="btn btn-secondary">
                    {{ trans('global.back') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
