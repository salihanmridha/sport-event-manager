<form wire:submit.prevent="submit" class="pt-3">

    <div class="form-group {{ $errors->has('skill.name') ? 'invalid' : '' }}">
        <label class="form-label" for="name">{{ trans('cruds.skill.fields.name') }}</label>
        <input class="form-control" type="text" name="name" id="name" wire:model.defer="skill.name">
        <div class="validation-message">
            {{ $errors->first('skill.name') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.skill.fields.name_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('skill.code') ? 'invalid' : '' }}">
        <label class="form-label" for="code">{{ trans('cruds.skill.fields.code') }}</label>
        <input class="form-control" type="text" name="code" id="code" wire:model.defer="skill.code">
        <div class="validation-message">
            {{ $errors->first('skill.code') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.skill.fields.code_helper') }}
        </div>
    </div>
    <div class="form-group">
        <button class="btn btn-indigo mr-2" type="submit">
            {{ trans('global.save') }}
        </button>
        <a href="{{ route('admin.skills.index') }}" class="btn btn-secondary">
            {{ trans('global.cancel') }}
        </a>
    </div>
</form>
