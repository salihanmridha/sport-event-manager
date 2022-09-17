<form wire:submit.prevent="submit" class="pt-3">

    <div class="form-group {{ $errors->has('team.name') ? 'invalid' : '' }}">
        <label class="form-label" for="name">{{ trans('cruds.team.fields.name') }}</label>
        <input class="form-control" type="text" name="name" id="name" wire:model.defer="team.name">
        <div class="validation-message">
            {{ $errors->first('team.name') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.team.fields.name_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('team.sport_id') ? 'invalid' : '' }}">
        <label class="form-label" for="sport">{{ trans('cruds.team.fields.sport') }}</label>
        <x-select-list class="form-control" id="sport" name="sport" :options="$this->listsForFields['sport']"
            wire:model="team.sport_id" />
        <div class="validation-message">
            {{ $errors->first('team.sport_id') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.team.fields.sport_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('team.bio') ? 'invalid' : '' }}">
        <label class="form-label" for="bio">{{ trans('cruds.team.fields.bio') }}</label>
        <textarea class="form-control" name="bio" id="bio" wire:model.defer="team.bio" rows="4"></textarea>
        <div class="validation-message">
            {{ $errors->first('team.bio') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.team.fields.bio_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('mediaCollections.team_avatar_image') ? 'invalid' : '' }}">
        <label class="form-label" for="team_avatar_image">{{ trans('cruds.team.fields.team_avatar_image') }}</label>
        <x-dropzone id="team_avatar_image" name="team_avatar_image" action="{{ route('admin.teams.storeMedia') }}"
            collection-name="team_avatar_image" max-file-size="2" max-width="4096" max-height="4096" max-files="1" />
        <div class="validation-message">
            {{ $errors->first('mediaCollections.team_avatar_image') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.team.fields.team_avatar_image_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('mediaCollections.team_background') ? 'invalid' : '' }}">
        <label class="form-label" for="team_background">{{ trans('cruds.team.fields.team_background') }}</label>
        <x-dropzone id="team_background" name="team_background" action="{{ route('admin.teams.storeMedia') }}"
            collection-name="team_background" max-file-size="2" max-width="4096" max-height="4096" max-files="1" />
        <div class="validation-message">
            {{ $errors->first('mediaCollections.team_background') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.team.fields.team_background_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('team.creator_id') ? 'invalid' : '' }}">
        <label class="form-label" for="creator">{{ trans('cruds.team.fields.creator') }}</label>
        <x-select-list class="form-control" id="creator" name="creator" :options="$this->listsForFields['creator']"
            wire:model="team.creator_id" />
        <div class="validation-message">
            {{ $errors->first('team.creator_id') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.team.fields.creator_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('team.org_role_id') ? 'invalid' : '' }}">
        <label class="form-label" for="org_role_id">{{ trans('cruds.team.fields.org_role_id') }}</label>
        <x-select-list class="form-control" id="org_role_id" name="org_role_id" :options="$this->listsForFields['organization_role']" wire:model="team.org_role_id" />
        <div class="validation-message">
            {{ $errors->first('team.org_role_id') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.team.fields.org_role_id_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('team.gender') ? 'invalid' : '' }}">
        <label class="form-label">{{ trans('cruds.team.fields.gender') }}</label>
        <select class="form-control" wire:model="team.gender">
            <option value="null" disabled>{{ trans('global.pleaseSelect') }}...</option>
            @foreach ($this->listsForFields['gender'] as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
        </select>
        <div class="validation-message">
            {{ $errors->first('team.gender') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.team.fields.gender_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('team.level_id') ? 'invalid' : '' }}">
        <label class="form-label">{{ trans('cruds.team.fields.level_id') }}</label>
        <select class="form-control" wire:model="team.level_id">
            <option value="null" disabled>{{ trans('global.pleaseSelect') }}...</option>
            @foreach ($this->listsForFields['team_level'] as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
        </select>
        <div class="validation-message">
            {{ $errors->first('team.level_id') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.team.fields.level_id_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('team.oganization_name') ? 'invalid' : '' }}">
        <label class="form-label" for="oganization_name">{{ trans('cruds.team.fields.oganization_name') }}</label>
        <input class="form-control" type="text" name="oganization_name" id="oganization_name"
            wire:model.defer="team.oganization_name">
        <div class="validation-message">
            {{ $errors->first('team.oganization_name') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.team.fields.oganization_name_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('team.oganization_url') ? 'invalid' : '' }}">
        <label class="form-label" for="oganization_url">{{ trans('cruds.team.fields.oganization_url') }}</label>
        <input class="form-control" type="text" name="oganization_url" id="oganization_url"
            wire:model.defer="team.oganization_url">
        <div class="validation-message">
            {{ $errors->first('team.oganization_url') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.team.fields.oganization_url_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('team.division') ? 'invalid' : '' }}">
        <label class="form-label" for="division">{{ trans('cruds.team.fields.division') }}</label>
        <input class="form-control" type="text" name="division" id="division" wire:model.defer="team.division">
        <div class="validation-message">
            {{ $errors->first('team.division') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.team.fields.division_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('team.season') ? 'invalid' : '' }}">
        <label class="form-label" for="season">{{ trans('cruds.team.fields.season') }}</label>
        <input class="form-control" type="text" name="season" id="season" wire:model.defer="team.season">
        <div class="validation-message">
            {{ $errors->first('team.season') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.team.fields.season_helper') }}
        </div>
    </div>

    <div class="form-group">
        <button class="btn btn-indigo mr-2" type="submit">
            {{ trans('global.save') }}
        </button>
        <a href="{{ route('admin.teams.index') }}" class="btn btn-secondary">
            {{ trans('global.cancel') }}
        </a>
    </div>
</form>
