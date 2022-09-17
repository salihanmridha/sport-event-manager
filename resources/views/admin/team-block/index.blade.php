@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="card bg-white">
        <div class="card-header border-b border-blueGray-200">
            <div class="card-header-container">
                <h6 class="card-title">
                    {{ trans('global.list') }}
                    {{ trans('cruds.team-block.title_block') }}
                    {{ $team->name }}
                </h6>
                @can('team_show')
                <a href="{{ route('admin.teams.show', $team) }}" class="btn btn-indigo">
                    {{ trans('global.back') }}
                </a>
                @endcan
            </div>
        </div>

        @livewire('team-block.index', [$team])

    </div>
</div>
@endsection