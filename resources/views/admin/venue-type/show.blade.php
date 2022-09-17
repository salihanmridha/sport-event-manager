@extends('layouts.admin')
@section('content')
    <div class="row">
        <div class="card bg-blueGray-100">
            <div class="card-header">
                <div class="card-header-container">
                    <h6 class="card-title">
                        {{ trans('global.view') }}
                        {{ trans('cruds.venue-type.title_singular') }}
                    </h6>
                </div>
            </div>

            <div class="card-body">
                <div class="pt-3">
                    <table class="table table-view">
                        <tbody class="bg-white">
                        <tr>
                            <th>
                                {{ trans('cruds.venue-type.fields.id') }}
                            </th>

                            <td>
                                {{ $venueType->id }}
                            </td>

                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.venue-type.fields.name') }}
                            </th>
                            <td>
                                {{ $venueType->name }}
                            </td>


                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.venue-type.fields.code') }}
                            </th>
                            <td>
                                {{ $venueType->code }}
                            </td>


                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.venue-type.fields.creator') }}
                            </th>
                            <td>
                                @if($venueType->creator->email)
                                    <h1 class="badge badge-relationship">{{ $venueType->creator->email ?? '' }}</h1>
                                @endif
                            </td>


                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="form-group">
                    @can('venue_type_edit')
                        <a href="{{ route('admin.venue-types.edit', $venueType) }}" class="btn btn-indigo mr-2">
                            {{ trans('global.edit') }}
                        </a>
                    @endcan
                    
                      <a href="{{ route('admin.venue-types.index') }}" class="btn btn-secondary">
                          {{ trans('global.back') }}
                      </a>
                </div>
            </div>
        </div>
    </div>
@endsection
