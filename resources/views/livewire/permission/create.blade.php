<form wire:submit.prevent="submit" class="pt-3">

    <div class="form-group {{ $errors->has('permission.title') ? 'invalid' : '' }}">
        <label class="form-label required" for="title">{{ trans('cruds.permission.fields.title') }}</label>
        <input class="form-control" type="text" name="title" id="title" required wire:model.defer="permission.title">
        <div class="validation-message">
            {{ $errors->first('permission.title') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.permission.fields.title_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('permission.type') ? 'invalid' : '' }}">
        <label class="form-label required">{{ trans('cruds.permission.fields.type') }}</label>
        <select class="form-control" wire:model="permission.type">
            <option value="null" disabled>{{ trans('global.pleaseSelect') }}...</option>
            @foreach($this->listsForFields['type'] as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
        </select>
        <div class="validation-message">
            {{ $errors->first('permission.type') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.permission.fields.type_helper') }}
        </div>
    </div>

    <div class="form-group">
        <button class="btn btn-indigo mr-2" type="submit">
            {{ trans('global.save') }}
        </button>
        <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">
            {{ trans('global.cancel') }}
        </a>
    </div>
</form>