@extends('layouts.admin')
@section('content')
    <div class="row">
        <div class="card bg-blueGray-100">
            <div class="card-header">
                <div class="card-header-container">
                    <h6 class="card-title">
                        {{ trans('global.view') }}
                        {{ trans('cruds.event.title_singular') }}:
                        {{ trans('cruds.event.fields.id') }}
                        {{ $event->id }}
                    </h6>
                </div>
            </div>
            <div class="card-body">
                <div class="pt-3">
                    <h3 class="card-title"> {{ trans('cruds.event.event_detail') }}</h3>
                    <table class="table table-view">
                        <tbody class="bg-white">
                        <tr>
                            <th>
                                {{ trans('cruds.event.fields.sport') }}
                            </th>
                            <td>
                                @if ($event->sport)
                                    <span class="badge badge-relationship">{{ $event->sport->name ?? '' }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.event.fields.event_owner') }}
                            </th>
                            <td>
                                {{ $event->event_ownership }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.event.fields.creator') }}
                            </th>
                            <td>
                                {{ $event->user_create?->email }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.event.fields.post_caption') }}
                            </th>
                            <td>
                                {{ $event->caption }}
                            </td>
                        </tr>
                        @if($event->event_type === 'pickup')
                            <tr>
                                <th>
                                    {{ trans('cruds.event.fields.number_of_team') }}
                                </th>
                                <td>
                                    {{ $event->max_team }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {{ trans('cruds.event.fields.no_player_per_team') }}
                                </th>
                                <td>
                                    {{ $event->max_player_per_team }}
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <th>
                                {{ trans('cruds.event.fields.upload_photo') }}
                            </th>
                            <td>
                                @foreach($event->upload_photo as $key => $entry)
                                    <a class="link-photo" href="{{ $entry['url'] }}">
                                        <img src="{{ $entry['file_name'] }}" alt="{{ $entry['name'] }}"
                                             title="{{ $entry['name'] }}">
                                    </a>
                                @endforeach
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="pt-3">
                    <h3 class="card-title">  {{ trans('cruds.event.application_setup') }}</h3>
                    <table class="table table-view">
                        <tbody class="bg-white">
                        <tr>
                            <th>
                                {{ trans('cruds.event.fields.application_type') }}
                            </th>
                            <td>
                                {{ $event->application_type_label }}
                            </td>
                        </tr>
                        @if($event->event_type == 'pickup' && $event->application_type == 'individual')
                            @if(!$event->is_set_role)
                                @foreach($event->event_position as $key => $position)
                                    <tr>
                                        <th>
                                            {{ trans('cruds.event.fields.player_role') }} {{ $key +1 }}
                                        </th>
                                        <td>
                                            {{$position->name}}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <th>
                                        {{ trans('cruds.event.fields.player_role') }}
                                    </th>
                                    <td>
                                        {{ trans('cruds.event.fields.is_set_role') }}
                                    </td>
                                </tr>
                            @endif
                        @else
                            <tr>
                                <th>
                                    {{ trans('cruds.event.fields.number_of_join') }}
                                </th>
                                <td>
                                    {{$position->is_unlimit_max ?? trans('cruds.event.fields.is_unlimit_max')}}
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
                <div class="pt-3">
                    <h3 class="card-title">{{ trans('cruds.event.game_detail') }}</h3>
                    <table class="table table-view">
                        <tbody class="bg-white">
                        <tr>
                            <th>
                                {{ trans('cruds.event.fields.about') }}
                            </th>
                            <td>
                                {{ $event->about }}
                            </td>
                        </tr>
                        @if($event->event_type == 'sport' || $event->event_type == 'league')
                        <tr>
                            <th>
                                {{ trans('cruds.event.event_mechanics') }}
                            </th>
                            <td>
                                {{ $event->mechanics }}
                            </td>
                        </tr>
                        @endif
                        <tr>
                            <th>
                                {{ trans('cruds.event.fields.gender') }}
                            </th>
                            <td>
                                {{ $event->gender }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.event.fields.age_group') }}
                            </th>
                            <td>
                                {{ $event->age_group }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.event.fields.start_date_and_time') }}
                            </th>
                            <td>
                                {{ Carbon\Carbon::parse($event->start_date_time)?->format('M-d-Y h:i:s A') ?? '__' }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.event.fields.end_date_and_time') }}
                            </th>
                            <td>
                                {{ Carbon\Carbon::parse($event->end_date_time)?->format('M-d-Y h:i:s A') ?? '__' }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.event.fields.location') }}
                            </th>
                            <td>
                                {{ $event->location }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.event.fields.fee') }}
                            </th>
                            <td>
                                {{ number_format($event->fee) }}
                            </td>
                        </tr>

                        </tbody>
                    </table>
                </div>
                <div class="pt-3">
                    <table class="table table-view">
                        <tbody class="bg-white">
                        <tr>
                            <th>
                                {{ trans('cruds.event.fields.game_privacy') }}
                            </th>
                            <td>
                                {{ $event->is_public ? 'Public' : 'Private' }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.event.fields.invitation_code') }}
                            </th>
                            <td>
                                {{ $event->private_code }}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="form-group">
                    @can('event_edit')
                    @if ( request('eventType') == 'league' || request('eventType') == 'sport' || request('eventType') == 'session')
                        <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-indigo mr-2">
                            {{ trans('global.edit') }}
                        </a>
                    @endif
                    @endcan
                    <a href="{{ route('admin.events.invite_player',  ['event' => $event, 'eventType' => $event->event_type]) }}" class="btn btn-indigo">
                        {{$event->application_type == 'team' ? trans('cruds.event.invite_team') :  trans('cruds.event.invite_player') }}
                    </a>
                    <a href="{{ route('admin.events.listPlayer', ['event' => $event->id, 'eventType' => $event->event_type]) }}" class="btn btn-indigo">
                        {{ trans('cruds.event.player_list') }}
                    </a>
                    <a href="{{ route('admin.events.index', ['eventType' => $event->event_type]) }}" class="btn btn-secondary">
                        {{ trans('global.back') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection


