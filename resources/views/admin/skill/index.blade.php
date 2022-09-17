@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="card bg-white">
        <div class="card-header border-b border-blueGray-200">
            <div class="text-right">
                {{-- <div class="card-header-container"> --}}
                <h6 class="card-title text-left">
                    {{ trans('cruds.skill.title_singular') }}
                    {{ trans('global.list') }}
                </h6>
                @can('skill_create')
                    <a class="btn btn-indigo" href="{{ route('admin.skills.create') }}">
                        {{ trans('global.add') }} {{ trans('cruds.skill.title_singular') }}
                    </a>
                @endcan

            </div>
        </div>
        @livewire('skill.index')

    </div>
</div>
@endsection
