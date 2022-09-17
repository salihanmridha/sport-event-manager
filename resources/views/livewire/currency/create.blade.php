<form wire:submit.prevent="submit" class="pt-3">

    <div class="form-group {{ $errors->has('currency.name') ? 'invalid' : '' }}">
        <label class="form-label" for="name">{{ trans('cruds.currency.fields.name') }}</label>
        <input class="form-control" type="text" name="name" id="name" wire:model.defer="currency.name">
        <div class="validation-message">
            {{ $errors->first('currency.name') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.currency.fields.name_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('currency.country_id') ? 'invalid' : '' }}">
        <label class="form-label">{{ trans('cruds.currency.fields.country') }}</label>
        <select class="form-control" wire:model="currency.country_id">
            <option value="null" selected disabled>{{ trans('global.pleaseSelect') }}...</option>
            @foreach ($countries as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
        </select>
        <div class="validation-message">
            {{ $errors->first('currency.country_id') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.currency.fields.country_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('currency.code') ? 'invalid' : '' }}">
        <label class="form-label" for="code">{{ trans('cruds.currency.fields.code') }}</label>
        <input class="form-control" type="text" name="code" id="code" wire:model.defer="currency.code">
        <div class="validation-message">
            {{ $errors->first('currency.code') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.currency.fields.code_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('currency.symbol') ? 'invalid' : '' }}">
        <label class="form-label" for="symbol">{{ trans('cruds.currency.fields.symbol') }}</label>
        <input class="form-control" type="text" name="symbol" id="phone_code" wire:model.defer="currency.symbol">
        <div class="validation-message">
            {{ $errors->first('currency.symbol') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.currency.fields.symbol_helper') }}
        </div>
    </div>

    <div class="form-group">
        <button class="btn btn-indigo mr-2" type="submit">
            {{ trans('global.save') }}
        </button>
        <a href="{{ route('admin.currencies.index') }}" class="btn btn-secondary">
            {{ trans('global.cancel') }}
        </a>
    </div>
</form>
