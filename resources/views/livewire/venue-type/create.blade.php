<form wire:submit.prevent="submit" class="pt-3">

    <div class="form-group {{ $errors->has('venueType.name') ? 'invalid' : '' }}">
        <label class="form-label" for="name">{{ trans('cruds.venue-type.fields.name') }}</label>
        <input class="form-control" type="text" name="venueType.name" id="venueType.name" wire:model.defer="venueType.name">
        <div class="validation-message">
            {{ $errors->first('venueType.name') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.venue-type.fields.name_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('venueType.code') ? 'invalid' : '' }}">
        <label class="form-label" for="code">{{ trans('cruds.venue-type.fields.code') }}</label>
        <input class="form-control" type="text" name="venueType.code" id="venueType.code" wire:model.defer="venueType.code">
        <div class="validation-message">
            {{ $errors->first('venueType.code') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.venue-type.fields.code_helper') }}
        </div>
    </div>
    <div class="form-group">
        <button class="btn btn-indigo mr-2" type="submit">
            {{ trans('global.save') }}
        </button>
        <a href="{{ route('admin.venue-types.index') }}" class="btn btn-secondary">
            {{ trans('global.cancel') }}
        </a>
    </div>
</form>
