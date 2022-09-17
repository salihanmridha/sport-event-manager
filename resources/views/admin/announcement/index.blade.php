@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="card bg-white">
        <div class="card-header border-b border-blueGray-200">
            <div class="card-header-container">
                <h6 class="card-title">
                    {{ trans('cruds.announcement.title_singular') }}
                    {{ trans('global.list') }}
                </h6>

                @can('announcement_access')
                    <a class="btn btn-indigo" href="{{ route('admin.announcements.create') }}">
                        {{ trans('global.add') }} {{ trans('cruds.announcement.title_singular') }}
                    </a>
                @endcan
            </div>
        </div>

        @if(session('error'))
            <x-alert message="{{ session('error') }}" variant="indigo" role="alert" />
        @endif

        @livewire('announcement.index')

    </div>
</div>
@endsection
