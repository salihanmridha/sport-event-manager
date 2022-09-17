@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="card bg-blueGray-100">
        <div class="card-header">
            <div class="card-header-container">
                <h6 class="card-title">
                    {{ trans('global.edit') }}
                    {{ trans('cruds.sport.title_singular') }}:
                    {{ trans('cruds.sport.fields.id') }}
                    {{ $sport->id }}
                </h6>
            </div>
        </div>

        <div class="card-body">
            @livewire('sport.edit', [$sport])
        </div>
    </div>
</div>
@endsection