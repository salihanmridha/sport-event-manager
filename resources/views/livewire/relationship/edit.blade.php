<form wire:submit.prevent="submit" class="pt-3">

    <div class="form-group {{ $errors->has('contactRelationship.name') ? 'invalid' : '' }}">
        <label class="form-label" for="name">{{ trans('cruds.contactRelationship.fields.name') }}</label>
        <input class="form-control" type="text" name="name" id="name" wire:model.defer="contactRelationship.name">
        <div class="validation-message">
            {{ $errors->first('contactRelationship.name') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.contactRelationship.fields.name_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('contactRelationship.code') ? 'invalid' : '' }}">
        <label class="form-label" for="code">{{ trans('cruds.contactRelationship.fields.code') }}</label>
        <input class="form-control" type="text" name="code" id="code" wire:model.defer="contactRelationship.code">
        <div class="validation-message">
            {{ $errors->first('contactRelationship.code') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.contactRelationship.fields.code_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('contactRelationship.creator_id') ? 'invalid' : '' }}">
        <label class="form-label" for="creator_id">{{ trans('cruds.contactRelationship.fields.creator_id') }}</label>
        <input class="form-control" type="text" name="creator_id" id="creator_id" wire:model.defer="contactRelationship.creator_id" disabled>
        <div class="validation-message">
            {{ $errors->first('contactRelationship.creator_id') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.contactRelationship.fields.creator_id_helper') }}
        </div>
    </div>

    <div class="form-group">
        <button class="btn btn-indigo mr-2" type="submit">
            {{ trans('global.save') }}
        </button>
        <a href="{{ route('admin.relationship.index') }}" class="btn btn-secondary">
            {{ trans('global.cancel') }}
        </a>
    </div>
</form>
