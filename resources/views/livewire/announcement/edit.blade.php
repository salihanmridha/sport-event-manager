<form wire:submit.prevent="submit" class="pt-3">

    <div class="form-group {{ $errors->has('announcement.title') ? 'invalid' : '' }}">
        <label class="form-label" for="title">{{ trans('cruds.announcement.fields.title') }}</label>
        <input class="form-control" type="text" name="title" id="title" wire:model.defer="announcement.title" step="1">
        <div class="validation-message">
            {{ $errors->first('announcement.title') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.announcement.fields.title_helper') }}
        </div>
    </div>

    <div style="display: none" class="form-group {{ $errors->has('announcement.type') ? 'invalid' : '' }}">
        <label class="form-label">{{ trans('cruds.announcement.fields.type') }}</label>
        <select class="form-control" wire:model="announcement.type" disabled>
            <option value="null" disabled>{{ trans('global.pleaseSelect') }}...</option>
            @foreach($this->listsForFields['type'] as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
        </select>
        <div class="validation-message">
            {{ $errors->first('announcement.type') }}
        </div>

        <div class="help-block">
            {{ trans('cruds.announcement.fields.announcement_type_helper') }}
        </div>
    </div>

    <div class="form-group {{ $errors->has('announcement.status') ? 'invalid' : '' }}">
        <label class="form-label">{{ trans('cruds.announcement.fields.status') }}</label>
        <select class="form-control" wire:model="announcement.status">
            <option value="null" disabled>{{ trans('global.pleaseSelect') }}...</option>
            @foreach($this->listsForFields['status'] as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
        </select>
        <div class="validation-message">
            {{ $errors->first('announcement.status') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.announcement.fields.status_helper') }}
        </div>
    </div>

    <div class="form-group {{ $errors->has('announcement.about') ? 'invalid' : '' }}">
        <label class="form-label" for="about">{{ trans('cruds.announcement.fields.about') }}</label>
        <textarea class="form-control" name="about" id="about" wire:model.defer="announcement.about" rows="4"  step="1"></textarea>
        <div class="validation-message">
            {{ $errors->first('announcement.about') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.announcement.fields.about') }}
        </div>
    </div>



    <div class="form-group {{ $errors->has('announcement.start_date') ? 'invalid' : '' }}">
        <label class="form-label" for="start_date">{{ trans('cruds.announcement.fields.start_date') }}</label>
        <x-date-picker class="form-control" wire:change="setEndDate($event.target.value)" wire:model="announcement.start_date" id="start_date" name="start_date" />
        <div class="validation-message">
            {{ $errors->first('announcement.start_date') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.announcement.fields.start_date_helper') }}
        </div>
    </div>

    <div class="form-group {{ $errors->has('announcement.end_date') ? 'invalid' : '' }}">
        <label class="form-label" for="end_date">{{ trans('cruds.announcement.fields.end_date') }}</label>
        <x-date-picker class="form-control" wire:model="announcement.end_date" id="end_date" name="end_date" required disabled/>
        <div class="validation-message">
            {{ $errors->first('announcement.end_date') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.announcement.fields.end_date_helper') }}
        </div>
    </div>

    <div class="form-group {{ $errors->has('mediaCollections.announcement_background_image') ? 'invalid' : '' }}">
        <label class="form-label" for="announcementImage">{{ trans('cruds.announcement.fields.announcementImage') }}</label>
        <x-dropzone id="background_image" name="background_image" action="{{ route('admin.announcements.storeMedia') }}" collection-name="announcement_background_image" max-file-size="2" max-width="4096" max-height="4096" max-files="10" />
        <div class="validation-message">
            {{ $errors->first('mediaCollections.announcement_background_image') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.user.fields.background_image_helper') }}
        </div>
    </div>


    <div class="form-group">
        <button  href="{{ route('admin.announcements.index') }}" class="btn btn-indigo mr-2" type="submit">
            {{ trans('global.save') }}
        </button>
        <a href="{{ route('admin.announcements.index') }}" class="btn btn-secondary">
            {{ trans('global.cancel') }}
        </a>
    </div>
</form>
