<form wire:submit.prevent="submit" class="pt-3">
    <div>
        @if (session()->has('message'))
            <div class="p-3 bg-red-400 text-red-800 rounded shadow-sm">
                {{ session('message') }}
            </div>
        @endif
    </div>
    <div class="form-group {{ $errors->has('event.event_type') ? 'invalid' : '' }}">
        <label class="form-label">{{ trans('cruds.event.fields.event_type') }}</label>
        <select class="form-control" wire:model="event.event_type" disabled>
            <option value="null" disabled>{{ trans('global.pleaseSelect') }}...</option>
            @foreach ($this->listsForFields['event_type'] as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
        </select>
        <div class="validation-message">
            {{ $errors->first('event.event_type') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.event.fields.event_type_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('event.event_ownership') ? 'invalid' : '' }}">
        <label class="form-label">{{ trans('cruds.event.fields.event_ownership') }}</label>
        <select class="form-control" wire:model="event.event_ownership">
            <option value="null" disabled>{{ trans('global.pleaseSelect') }}...</option>
            @foreach ($this->listsForFields['event_ownership'] as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
        </select>
        <div class="validation-message">
            {{ $errors->first('event.event_ownership') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.event.fields.event_ownership_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('event.creator_id') ? 'invalid' : '' }}">
        <label class="form-label" for="creator">{{ trans('cruds.event.fields.creator') }}</label>
        <x-select-list class="form-control" id="creator" name="creator" :options="$this->listsForFields['creator']" wire:model="event.creator_id" disabled/>
        <div class="validation-message">
            {{ $errors->first('event.creator_id') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.event.fields.creator_helper') }}
        </div>
    </div>
    @if ($show_title)
        <div class="form-group {{ $errors->has('event.title') ? 'invalid' : '' }}">
            @if ($show_field_event)
                <label class="form-label" for="title">{{ trans('cruds.event.fields.title') }}</label>
            @endif
            @if ($show_field_session)
                <label class="form-label" for="title">{{ trans('cruds.event.fields.title_session') }}</label>
            @endif
            <input class="form-control" type="text" name="location" id="title" wire:model.defer="event.title">
            <div class="validation-message">
                {{ $errors->first('event.title') }}
            </div>
            <div class="help-block">
                {{ trans('cruds.event.fields.title_helper') }}
            </div>
        </div>
    @endif
    @if ($show_sport)
        <div class="form-group {{ $errors->has('event.sport_id') ? 'invalid' : '' }}">
            <label class="form-label" for="sport">{{ trans('cruds.event.fields.sport') }}</label>
            <x-select-list class="form-control" id="sport" name="sport" :options="$this->listsForFields['sport']"
                wire:model="event.sport_id" />
            <div class="validation-message">
                {{ $errors->first('event.sport_id') }}
            </div>
            <div class="help-block">
                {{ trans('cruds.event.fields.sport_helper') }}
            </div>
        </div>
    @endif
    <div class="form-group {{ $errors->has('event.caption') ? 'invalid' : '' }}">
        <label class="form-label" for="caption">{{ trans('cruds.event.fields.caption') }}</label>
        <textarea class="form-control" name="caption" id="caption" wire:model.defer="event.caption" rows="4"></textarea>
        <div class="validation-message">
            {{ $errors->first('event.caption') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.event.fields.caption_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('mediaCollections.upload_photo') ? 'invalid' : '' }}">
        <label class="form-label" for="upload_photo">{{ trans('cruds.event.fields.upload_photo') }}</label>
        <x-dropzone id="upload_photo" name="upload_photo" action="{{ route('admin.events.storeMedia') }}"
            collection-name="upload_photo" max-file-size="2" max-width="4096" max-height="4096" max-files="1" />
        <div class="validation-message">
            {{ $errors->first('mediaCollections.upload_photo') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.event.fields.upload_photo_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('event.application_type') ? 'invalid' : '' }}">
        <label class="form-label">{{ trans('cruds.event.fields.application_type') }}</label>
        <select class="form-control" wire:model="event.application_type">
            <option value="null" disabled>{{ trans('global.pleaseSelect') }}...</option>
            @foreach ($this->listsForFields['application_type'] as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
        </select>
        <div class="validation-message">
            {{ $errors->first('event.application_type') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.event.fields.application_type_helper') }}
        </div>
    </div>
    @if ($show_application_type)
        <div class="form-group {{ $errors->has('event.is_set_role') ? 'invalid' : '' }}">
            <input class="form-control" type="checkbox" name="is_set_role" id="is_set_role"
                wire:model.defer="event.is_set_role">
            @if ($show_is_set_role_pickup)
                <label class="form-label inline ml-1"
                    for="is_set_role">{{ trans('cruds.event.fields.is_set_role') }}</label>
            @endif
            @if ($show_is_set_role)
                <label class="form-label inline ml-1"
                    for="is_set_role">{{ trans('cruds.event.fields.is_set_role_sport') }}</label>
            @endif
            <div class="validation-message">
                {{ $errors->first('event.is_set_role') }}
            </div>
            <div class="help-block">
                {{ trans('cruds.event.fields.is_set_role_helper') }}
            </div>
        </div>
        @if ($showApplicationType)
            <div class="form-group {{ $errors->has('event.max_player_per_team') ? 'invalid' : '' }}">
                @if ($show_field_max_player_per_team)
                    <label class="form-label"
                        for="max_player_per_team">{{ trans('cruds.event.fields.max_player_per_team') }}</label>
                @endif
                @if ($max_number_join)
                    <label class="form-label"
                        for="max_player_per_team">{{ trans('cruds.event.fields.max_number_join') }}</label>
                @endif
                <input class="form-control" type="number" name="max_player_per_team" id="max_player_per_team"
                    wire:model.defer="event.max_player_per_team" step="1">
                <div class="validation-message">
                    {{ $errors->first('event.max_player_per_team') }}
                </div>
                <div class="help-block">
                    {{ trans('cruds.event.fields.max_player_per_team_helper') }}
                </div>
            </div>
        @endif
    @endif
    @if ($showApplicationTeam)
        <div class="form-group {{ $errors->has('event.is_set_role') ? 'invalid' : '' }}">
            <input class="form-control" type="checkbox" name="is_set_role" id="is_set_role"
                wire:model.defer="event.is_set_role">
            @if ($show_is_set_role_pickup)
                <label class="form-label inline ml-1"
                    for="is_set_role">{{ trans('cruds.event.fields.is_set_role') }}</label>
            @endif
            @if ($show_is_set_role)
                <label class="form-label inline ml-1"
                    for="is_set_role">{{ trans('cruds.event.fields.is_set_role_sport') }}</label>
            @endif
            <div class="validation-message">
                {{ $errors->first('event.is_set_role') }}
            </div>
            <div class="help-block">
                {{ trans('cruds.event.fields.is_set_role_helper') }}
            </div>
        </div>
        @if ($showApplicationType)
            <div class="form-group {{ $errors->has('event.max_team') ? 'invalid' : '' }}">
                <label class="form-label" for="max_team">{{ trans('cruds.event.fields.max_team') }}</label>
                <input class="form-control" type="number" name="max_team" id="max_team"
                    wire:model.defer="event.max_team" step="1">
                <div class="validation-message">
                    {{ $errors->first('event.max_team') }}
                </div>
                <div class="help-block">
                    {{ trans('cruds.event.fields.max_team_helper') }}
                </div>
            </div>
        @endif
    @endif
    @if ($show_mechanics)
        <div class="form-group {{ $errors->has('event.mechanics') ? 'invalid' : '' }}">
            <label class="form-label" for="mechanics">{{ trans('cruds.event.fields.mechanics') }}</label>
            <textarea class="form-control" name="mechanics" id="mechanics" wire:model.defer="event.mechanics" rows="4"></textarea>
            <div class="validation-message">
                {{ $errors->first('event.mechanics') }}
            </div>
            <div class="help-block">
                {{ trans('cruds.event.fields.mechanics_helper') }}
            </div>
        </div>
    @endif
    <div class="form-group {{ $errors->has('event.about') ? 'invalid' : '' }}">
        @if ($show_field_pickup)
            <label class="form-label" for="about">{{ trans('cruds.event.fields.about') }}</label>
        @endif
        @if ($show_field_event)
            <label class="form-label" for="about">{{ trans('cruds.event.fields.about_event') }}</label>
        @endif
        @if ($show_field_session)
            <label class="form-label" for="about">{{ trans('cruds.event.fields.about_session') }}</label>
        @endif
        <textarea class="form-control" name="about" id="about" wire:model.defer="event.about" rows="4"></textarea>
        <div class="validation-message">
            {{ $errors->first('event.about') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.event.fields.about_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('event.gender') ? 'invalid' : '' }}">
        <label class="form-label">{{ trans('cruds.event.fields.gender') }}</label>
        <select class="form-control" wire:model="event.gender">
            <option value="null" disabled>{{ trans('global.pleaseSelect') }}...</option>
            @foreach ($this->listsForFields['gender'] as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
        </select>
        <div class="validation-message">
            {{ $errors->first('event.gender') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.event.fields.gender_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('event.age_group') ? 'invalid' : '' }}">
        <label class="form-label">{{ trans('cruds.event.fields.age_group') }}</label>
        <select class="form-control" wire:model="event.age_group">
            <option value="null" disabled>{{ trans('global.pleaseSelect') }}...</option>
            @foreach ($this->listsForFields['age_group'] as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
        </select>
        <div class="validation-message">
            {{ $errors->first('event.age_group') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.event.fields.age_group_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('event.start_date_time') ? 'invalid' : '' }}">
        <label class="form-label" for="start_date_time">{{ trans('cruds.event.fields.start_date_time') }}</label>
        <x-date-picker class="form-control" wire:model="event.start_date_time" id="start_date_time"
            name="start_date_time" />
        <div class="validation-message">
            {{ $errors->first('event.start_date_time') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.event.fields.start_date_time_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('event.end_date_time') ? 'invalid' : '' }}">
        <label class="form-label" for="end_date_time">{{ trans('cruds.event.fields.end_date_time') }}</label>
        <x-date-picker class="form-control" wire:model="event.end_date_time" id="end_date_time"
            name="end_date_time" />
        <div class="validation-message">
            {{ $errors->first('event.end_date_time') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.event.fields.end_date_time_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('event.location') ? 'invalid' : '' }}">
        <label class="form-label" for="location">{{ trans('cruds.event.fields.location') }}</label>
        <input class="form-control" type="text" name="location" id="location"
            wire:model.defer="event.location">
        <div class="validation-message">
            {{ $errors->first('event.location') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.event.fields.location_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('event.lat') ? 'invalid' : '' }}">
        <label class="form-label" for="lat">{{ trans('cruds.event.fields.lat') }}</label>
        <input class="form-control" type="text" name="lat" id="lat" wire:model.defer="event.lat">
        <div class="validation-message">
            {{ $errors->first('event.lat') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.event.fields.lat_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('event.long') ? 'invalid' : '' }}">
        <label class="form-label" for="long">{{ trans('cruds.event.fields.long') }}</label>
        <input class="form-control" type="text" name="long" id="long" wire:model.defer="event.long">
        <div class="validation-message">
            {{ $errors->first('event.long') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.event.fields.long_helper') }}
        </div>
    </div>
    <x-map lat="event.lat" lng="event.long" height="500px" place_name="event.location" data-long="{{$event->long}}" data-lat="{{$event->lat}}"></x-map>
    <div class="form-group {{ $errors->has('event.is_paid') ? 'invalid' : '' }}">
        <input class="form-control" type="checkbox" name="is_paid" id="is_paid" wire:model.defer="event.is_paid">
        <label class="form-label inline ml-1" for="is_paid">{{ trans('cruds.event.fields.is_paid') }}</label>
        <div class="validation-message">
            {{ $errors->first('event.is_paid') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.event.fields.is_paid_helper') }}
        </div>
    </div>
    @if ($showChargeFee)
        <div class="form-group {{ $errors->has('event.fee') ? 'invalid' : '' }}">
            <label class="form-label" for="fee">{{ trans('cruds.event.fields.fee') }}</label>
            <input class="form-control" type="text" name="fee" id="fee" wire:model.defer="event.fee">
            <div class="validation-message">
                {{ $errors->first('event.fee') }}
            </div>
            <div class="help-block">
                {{ trans('cruds.event.fields.fee_helper') }}
            </div>
        </div>
    @endif
    <div class="form-group {{ $errors->has('event.is_public') ? 'invalid' : '' }}">
        <label class="form-label">{{ trans('cruds.event.fields.is_public') }}</label>
        <select class="form-control" wire:model="event.is_public">
            <option value="null" disabled>{{ trans('global.pleaseSelect') }}...</option>
            @foreach ($this->listsForFields['is_public'] as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
        </select>
        <div class="validation-message">
            {{ $errors->first('event.is_public') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.event.fields.is_public_helper') }}
        </div>
    </div>
    <div class="form-group">
        <button class="btn btn-indigo mr-2" type="submit">
            {{ trans('global.save') }}
        </button>
        <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">
            {{ trans('global.cancel') }}
        </a>
    </div>
</form>
