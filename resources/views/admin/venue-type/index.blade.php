@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="card bg-white">
        <div class="card-header border-b border-blueGray-200">
            <div class="text-right">
                {{-- <div class="card-header-container"> --}}
                <h6 class="card-title text-left">
                    {{ trans('cruds.venue-type.title_singular') }}
                    {{ trans('global.list') }}
                </h6>
                @can('venue_type_create')
                    <a class="btn btn-indigo" href="{{ route('admin.venue-types.create') }}">
                        {{ trans('global.add') }} {{ trans('cruds.venue-type.title_singular') }}
                    </a>
                @endcan

            </div>
        </div>
        @livewire('venue-type.index')

    </div>
</div>
@endsection
