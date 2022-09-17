@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="card bg-blueGray-100">
        <div class="card-header">
            <div class="card-header-container">
                <h6 class="card-title">
                    {{ trans('global.view') }}
                    {{ trans('cruds.sport.title_singular') }}:
                    {{ trans('cruds.sport.fields.id') }}
                    {{ $sport->id }}
                </h6>
            </div>
        </div>

        <div class="card-body">
            <div class="pt-3">
                <table class="table table-view">
                    <tbody class="bg-white">
                        <tr>
                            <th>
                                {{ trans('cruds.sport.fields.id') }}
                            </th>
                            <td>
                                {{ $sport->id }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.sport.fields.code') }}
                            </th>
                            <td>
                                {{ $sport->code }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.sport.fields.name') }}
                            </th>
                            <td>
                                {{ $sport->name }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.sport.fields.description') }}
                            </th>
                            <td>
                                {{ $sport->description }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.sport.fields.creator') }}
                            </th>
                            <td>
                                @if($sport->creator)
                                    <span class="badge badge-relationship">{{ $sport->creator->email ?? '' }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.sport.fields.max_player_per_team') }}
                            </th>
                            <td>
                                {{ $sport->max_player_per_team }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.sport.fields.min_player_per_team') }}
                            </th>
                            <td>
                                {{ $sport->min_player_per_team }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.sport.fields.is_require_choose_role') }}
                            </th>
                            <td>
                                {{ $sport->is_require_choose_role_label }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.sport.fields.icon') }}
                            </th>
                            <td>
                                <a class="link-photo" href="{{ $sport->icon }}">
                                    <img src="{{ $sport->icon }}"/>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="form-group">
                @can('sport_edit')
                    <a href="{{ route('admin.sports.edit', $sport) }}" class="btn btn-indigo mr-2">
                        {{ trans('global.edit') }}
                    </a>
                @endcan
                <a href="{{ route('admin.sports.index') }}" class="btn btn-secondary">
                    {{ trans('global.back') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection