@extends('layouts.admin')
@section('content')
    <div class="row">
        <div class="card bg-blueGray-100">
            <div class="card-header">
                <div class="card-header-container">
                    <h6 class="card-title">
                        {{ trans('global.view') }}
                        {{ trans('cruds.venue.title') }}:
                        {{ trans('cruds.currency.fields.id') }}
                        {{ $venue->id }}
                    </h6>
                </div>
            </div>

            <div class="card-body">
                <div class="pt-3">
                    <table class="table table-view">
                        <tbody class="bg-white">
                        <tr>
                            <th>
                                {{ trans('cruds.currency.fields.id') }}
                            </th>
                            <td>
                                {{ $venue->id }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.venue.fields.name') }}
                            </th>
                            <td>
                                {{ $venue->name }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.venue.fields.owner') }}
                            </th>
                            <td>
                                <span class="badge badge-relationship">{{ $venue->owner?->email }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.venue.fields.address') }}
                            </th>
                            <td>
                                {{ $venue->address }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.venue.fields.name') }}
                            </th>
                            <td>
                                {{ $venue->country?->name }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.venue.fields.sport') }}
                            </th>
                            <td>
                                @foreach($venue->sports as $sport)
                                    <span class="badge badge-relationship"> {{ $sport->name }}</span>
                                @endforeach

                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.venue.fields.phone_code') }}
                            </th>
                            <td>
                                {{ $venue->phone_code }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.venue.fields.phone_number') }}
                            </th>
                            <td>
                                {{ $venue->phone_number }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.venue.fields.email') }}
                            </th>
                            <td>
                                <span class="badge badge-relationship">{{ $venue->email }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.user.fields.background_image') }}
                            </th>
                            <td>
                                @forelse($venue->upload_photo as $photo)
                                    <a class="link-photo" href="{{ $photo['original_url'] }}">
                                        <img src="{{ $photo['original_url'] }}"/>
                                    </a>
                                @empty
                                @endforelse
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.venue.fields.bio') }}
                            </th>
                            <td>
                                {{ $venue->bio }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.venue.fields.status') }}
                            </th>
                            <td>
                                {{ $venue->status?->label }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.sport.fields.creator') }}
                            </th>
                            <td>
                                <span class="badge badge-relationship">{{ $venue->creator?->email }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.currency.fields.created_at') }}
                            </th>
                            <td>
                                {{ $venue->created_at }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.currency.fields.updated_at') }}
                            </th>
                            <td>
                                {{ $venue->updated_at }}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="form-group">
                    @if(Gate::check('venue_edit') || Gate::check('venue_edit_own'))
                        <a href="{{ route('admin.venues.edit', $venue) }}" class="btn btn-indigo mr-2">
                            {{ trans('global.edit') }}
                        </a>
                    @endif
                    @if(Gate::check('venue_edit'))
                        <a href="{{ route('admin.venues.index') }}" class="btn btn-secondary">
                            {{ trans('global.back') }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
