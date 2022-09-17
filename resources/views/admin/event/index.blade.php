@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="card bg-white">
        <div class="card-header border-b border-blueGray-200">
            <div class="card-header-container">
                <h6 class="card-title">
                    {{ trans('cruds.event.'. request('eventType')  .'.title') }}
{{--                    {{ trans('global.list') }}--}}
                </h6>

                @can('event_create')
                    @if ( request('eventType') == 'league' || request('eventType') == 'sport' || request('eventType') == 'session')
                    <a class="btn btn-indigo" href="{{ route('admin.events.create', ['eventType' => request('eventType')]) }}">
                        {{ trans('cruds.event.' . request('eventType') . '.create') }}
                    </a>
                    @endif
                @endcan
            </div>
        </div>
        @livewire('event.index')

    </div>
</div>
@endsection
