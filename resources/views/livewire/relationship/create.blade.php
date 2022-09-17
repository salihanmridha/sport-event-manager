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
            {{ request()->user() }}
        </div>
        <div class="help-block">
            {{ trans('cruds.contactRelationship.fields.code_helper') }}
        </div>
    </div>
    {{-- <div class="form-group {{ $errors->has('contactRelationship.') ? 'invalid' : '' }}">
        <label class="form-label" for="symbol">{{ trans('cruds.contactRelationshipcontactRelationship.fields.symbol') }}</label>
        <input class="form-control" type="text" name="symbol" id="phone_code" wire:model.defer="{{ $this->user()->id }}">
        <div class="validation-message">
            {{ $errors->first('contactRelationship.') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.contactRelationship.fields.') }}
        </div>
    </div> --}}

    <div class="form-group">
        <button class="btn btn-indigo mr-2" type="submit">
            {{ trans('global.save') }}
        </button>
        <a href="{{ route('admin.relationship.index') }}" class="btn btn-secondary">
            {{ trans('global.cancel') }}
        </a>
    </div>
</form>
