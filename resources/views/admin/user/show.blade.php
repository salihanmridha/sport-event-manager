@extends('layouts.admin')
@section('content')
    <div class="row">
        <div class="card bg-blueGray-100">
            <div class="card-header">
                <div class="card-header-container">
                    <h6 class="card-title">
                        {{ trans('global.view') }}
                        {{ trans('cruds.user.title_singular') }}:
                        {{ trans('cruds.user.fields.id') }}
                        {{ $user->id }}
                    </h6>
                </div>
            </div>

            <div class="card-body">
                <div class="grid flex">
                    <div class="pt-3 inline-grid grid-cols-3 gap-4">
                        <table class="table table-view">
                            <tbody class="bg-white">
                                <tr>
                                    <th>
                                        {{ trans('cruds.user.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $user->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.user.fields.email') }}
                                    </th>
                                    <td>
                                        <a class="link-light-blue" href="mailto:{{ $user->email }}">
                                            <i class="far fa-envelope fa-fw">
                                            </i>
                                            {{ $user->email }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.user.fields.email_verified_at') }}
                                    </th>
                                    <td>
                                        {{ $user->email_verified_at }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.user.fields.roles') }}
                                    </th>
                                    <td>
                                        @foreach ($user->roles as $key => $entry)
                                            <span class="badge badge-relationship">{{ $entry->title }}</span>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.user.fields.locale') }}
                                    </th>
                                    <td>
                                        {{ $user->locale }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.user.fields.avatar') }}
                                    </th>
                                    <td>
                                        <a class="link-photo" href="{{ $user->avatar }}">
                                            <img src="{{ $user->avatar }}" alt="" title="">
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.user.fields.first_name') }}
                                    </th>
                                    <td>
                                        {{ $user->first_name }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.user.fields.last_name') }}
                                    </th>
                                    <td>
                                        {{ $user->last_name }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.user.fields.gender') }}
                                    </th>
                                    <td>
                                        {{ $user->gender_label }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.user.fields.phone') }}
                                    </th>
                                    <td>
                                        {{ $user->phone }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.user.fields.birth_date') }}
                                    </th>
                                    <td>
                                        {{ $user->birth_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.user.fields.bio') }}
                                    </th>
                                    <td>
                                        {{ $user->bio }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.user.fields.phone_code') }}
                                    </th>
                                    <td>
                                        {{ $user->phone_code }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.user.fields.country_id') }}
                                    </th>
                                    <td>
                                        {{ $user->country?->name }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.user.fields.currency_id') }}
                                    </th>
                                    <td>
                                        {{ $user->currency?->name }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.user.fields.status') }}
                                    </th>
                                    <td>
                                        {{ $user->status_label }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.user.fields.background_image') }}
                                    </th>
                                    <td>
                                        <a class="link-photo" href="{{ $user->background_image }}">
                                            <img src="{{ $user->background_image }}" />
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.user.fields.is_notify_email') }}
                                    </th>
                                    <td>
                                        <input class="disabled:opacity-50 disabled:cursor-not-allowed" type="checkbox"
                                            disabled {{ $user->is_notify_email ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.user.fields.is_notify_sms') }}
                                    </th>
                                    <td>
                                        <input class="disabled:opacity-50 disabled:cursor-not-allowed" type="checkbox"
                                            disabled {{ $user->is_notify_sms ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.user.fields.is_notify_push') }}
                                    </th>
                                    <td>
                                        <input class="disabled:opacity-50 disabled:cursor-not-allowed" type="checkbox"
                                            disabled {{ $user->is_notify_push ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.user.fields.is_marketing') }}
                                    </th>
                                    <td>
                                        <input class="disabled:opacity-50 disabled:cursor-not-allowed" type="checkbox"
                                            disabled {{ $user->is_marketing ? 'checked' : '' }}>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="pt-3 inline-grid grid-cols-3 gap-4">
                        <table class="table table-view">
                            <tbody class="bg-white">
                                <tr>
                                    <th colspan="2">
                                        Emergency contact section
                                    </th>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.user.fields.ec_fullname') }}
                                    </th>
                                    <td>
                                        {{ $user->ec_fullname }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.user.fields.ec_relationship') }}
                                    </th>
                                    <td>
                                        {{ $user->ec_relationship }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.user.fields.ec_main_pcode') }}
                                    </th>
                                    <td>
                                        {{ $user->ec_main_pcode }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.user.fields.ec_main_pnum') }}
                                    </th>
                                    <td>
                                        {{ $user->ec_main_pnum }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.user.fields.ec_alt_pcode') }}
                                    </th>
                                    <td>
                                        {{ $user->ec_alt_pcode }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.user.fields.ec_alt_pnum') }}
                                    </th>
                                    <td>
                                        {{ $user->ec_alt_pnum }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.user.fields.ec_email') }}
                                    </th>
                                    <td>
                                        {{ $user->ec_email }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="form-group">
                    @can('user_edit')
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-indigo mr-2">
                            {{ trans('global.edit') }}
                        </a>
                    @endcan
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                        {{ trans('global.back') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
