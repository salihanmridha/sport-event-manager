<form wire:submit.prevent="submit" class="pt-3">

    <div class="form-group {{ $errors->has('orgRole.code') ? 'invalid' : '' }}">
        <label class="form-label" for="code">{{ trans('cruds.organizationRole.fields.code') }}</label>
        <input class="form-control" type="text" name="code" id="code" wire:model.defer="orgRole.code">
        <div class="validation-message">
            {{ $errors->first('orgRole.code') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.organizationRole.fields.code_helper') }}
        </div>
    </div>

    <div class="form-group {{ $errors->has('orgRole.name') ? 'invalid' : '' }}">
        <label class="form-label" for="name">{{ trans('cruds.organizationRole.fields.name') }}</label>
        <input class="form-control" type="text" name="name" id="name" wire:model.defer="orgRole.name">
        <div class="validation-message">
            {{ $errors->first('orgRole.name') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.organizationRole.fields.name_helper') }}
        </div>
    </div>

    <div class="form-group">
        <button class="btn btn-indigo mr-2" type="submit">
            {{ trans('global.save') }}
        </button>
        <a href="{{ route('admin.organization-role.index') }}" class="btn btn-secondary">
            {{ trans('global.cancel') }}
        </a>
    </div>
</form>