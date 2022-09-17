@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="card bg-blueGray-100">
        <div class="card-header">
            <div class="card-header-container">
                <h6 class="card-title">
                    {{ trans('global.edit') }}
                    {{ trans('cruds.team.member.title') }}:
                    {{ trans('cruds.team.fields.id') }}
                    {{ $member->id }}
                </h6>
            </div>
        </div>

        <div class="card-body">
            @livewire('team.member.edit', [$team, $member])
        </div>
    </div>
</div>
@endsection