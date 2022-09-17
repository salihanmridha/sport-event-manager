@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="card bg-white">
        <div class="card-header border-b border-blueGray-200">
            <div class="card-header-container">
                <h6 class="card-title">
                    {{ trans('cruds.venue.title') }}
                    {{ trans('global.list') }}
                </h6>

                @can('currency_create')
                    <a class="btn btn-indigo" href="{{ route('admin.venues.create') }}">
                        {{ trans('global.add') }} {{ trans('cruds.venue.title') }}
                    </a>
                @endcan
            </div>
        </div>
        @livewire('venue.index')
    </div>
</div>
@endsection
