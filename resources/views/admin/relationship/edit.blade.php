@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="card bg-blueGray-100">
        <div class="card-header">
            <div class="card-header-container">
                <h6 class="card-title">
                    {{ trans('global.edit') }}
                    {{ trans('cruds.contactRelationship.title_singular') }}:
                    {{ trans('cruds.contactRelationship.fields.id') }}
                    {{ $contactRelationship->id }}
                </h6>
            </div>
        </div>

        <div class="card-body">
            @livewire('relationship.edit', [$contactRelationship])
        </div>
    </div>
</div>
@endsection
