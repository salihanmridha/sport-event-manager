@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="card bg-blueGray-100">
        <div class="card-header">
            <div class="card-header-container">
                <h6 class="card-title">
                    {{ trans('global.edit') }}
                    {{ trans('cruds.announcement.title_singular') }}:
                    {{ trans('cruds.announcement.fields.id') }}
                    {{ $announcement->id }}
                </h6>
            </div>
        </div>

        <div class="card-body">
            @livewire('announcement.edit', [$announcement])
        </div>
    </div>
</div>
@endsection
