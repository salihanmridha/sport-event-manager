<form wire:submit.prevent="submit" class="pt-3">

    <div class="form-group {{ $errors->has('user.email') ? 'invalid' : '' }}">
        <label class="form-label required" for="email">{{ trans('cruds.user.fields.email') }}</label>
        <input class="form-control" type="email" name="email" id="email" required wire:model.defer="user.email">
        <div class="validation-message">
            {{ $errors->first('user.email') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.user.fields.email_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('user.first_name') ? 'invalid' : '' }}">
        <label class="form-label required" for="first_name">{{ trans('cruds.user.fields.first_name') }}</label>
        <input class="form-control" type="text" name="first_name" id="first_name" wire:model.defer="user.first_name">
        <div class="validation-message">
            {{ $errors->first('user.first_name') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.user.fields.first_name_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('user.last_name') ? 'invalid' : '' }}">
        <label class="form-label required" for="last_name">{{ trans('cruds.user.fields.last_name') }}</label>
        <input class="form-control" type="text" name="last_name" id="last_name" wire:model.defer="user.last_name">
        <div class="validation-message">
            {{ $errors->first('user.last_name') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.user.fields.last_name_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('user.gender') ? 'invalid' : '' }}">
        <label class="form-label required">{{ trans('cruds.user.fields.gender') }}</label>
        <select class="form-control" wire:model="user.gender">
            <option value="null" disabled>{{ trans('global.pleaseSelect') }}...</option>
            @foreach($this->listsForFields['gender'] as $key => $value)
                <option value="{{ $value }}">{{ $value }}</option>
            @endforeach
        </select>
        <div class="validation-message">
            {{ $errors->first('user.gender') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.user.fields.gender_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('user.birth_date') ? 'invalid' : '' }}">
        <label class="form-label required" for="birth_date">{{ trans('cruds.user.fields.birth_date') }}</label>
        <x-date-picker class="form-control" required wire:model="user.birth_date" id="birth_date" name="birth_date" picker="date" />
        <div class="validation-message">
            {{ $errors->first('user.birth_date') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.user.fields.birth_date_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('user.phone') ? 'invalid' : '' }}">
        <label class="form-label required" for="phone">{{ trans('cruds.user.fields.phone') }}</label>
        <input class="form-control" type="text" name="phone" id="phone" required wire:model.defer="user.phone">
        <div class="validation-message">
            {{ $errors->first('user.phone') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.user.fields.phone_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('phone_code') ? 'invalid' : '' }}">
        <label class="form-label required">{{ trans('cruds.user.fields.phone_code') }}</label>
        <select class="form-control" wire:model="user.phone_code">
            <option value="null" disabled>{{ trans('global.pleaseSelect') }}...</option>
            @foreach($this->listsForFields['phone_code'] as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
        </select>
        <div class="validation-message">
            {{ $errors->first('user.phone_code') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.user.fields.phone_code_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('country_id') ? 'invalid' : '' }}">
        <label class="form-label required">{{ trans('cruds.user.fields.country_id') }}</label>
        <select class="form-control" wire:model="user.country_id">
            <option value="null" disabled>{{ trans('global.pleaseSelect') }}...</option>
            @foreach($this->listsForFields['country_id'] as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
        </select>
        <div class="validation-message">
            {{ $errors->first('user.country_id') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.user.fields.country_id_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('currency_id') ? 'invalid' : '' }}">
        <label class="form-label required">{{ trans('cruds.user.fields.currency_id') }}</label>
        <select class="form-control" wire:model="user.currency_id">
            <option value="null" disabled>{{ trans('global.pleaseSelect') }}...</option>
            @foreach($this->listsForFields['currency_id'] as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
        </select>
        <div class="validation-message">
            {{ $errors->first('user.currency_id') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.user.fields.currency_id_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('user.password') ? 'invalid' : '' }}">
        <label class="form-label required" for="password">{{ trans('cruds.user.fields.password') }}</label>
        <input class="form-control" type="password" name="password" id="password" required wire:model.defer="password">
        <div class="validation-message">
            {{ $errors->first('user.password') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.user.fields.password_helper') }}
        </div>
    </div>

    <div class="form-group {{ $errors->has('roles') ? 'invalid' : '' }}" >
        <label class="form-label required" for="roles">{{ trans('cruds.user.fields.roles') }}</label>
        <x-select-list class="form-control" required id="roles" name="roles" wire:model="roles" :options="$this->listsForFields['roles']" multiple />
        <div class="validation-message">
            {{ $errors->first('roles') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.user.fields.roles_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('user.bio') ? 'invalid' : '' }}">
        <label class="form-label" for="bio">{{ trans('cruds.user.fields.bio') }}</label>
        <textarea class="form-control" name="bio" id="bio" wire:model.defer="user.bio" rows="4"></textarea>
        <div class="validation-message">
            {{ $errors->first('user.bio') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.user.fields.bio_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('mediaCollections.user_avatar') ? 'invalid' : '' }}">
        <label class="form-label" for="avatar">{{ trans('cruds.user.fields.avatar') }}</label>
        <x-dropzone id="avatar" name="avatar" action="{{ route('admin.users.storeMedia') }}" collection-name="user_avatar" max-file-size="2" max-width="4096" max-height="4096" max-files="1" />
        <div class="validation-message">
            {{ $errors->first('mediaCollections.user_avatar') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.user.fields.avatar_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('mediaCollections.user_background_image') ? 'invalid' : '' }}">
        <label class="form-label" for="background_image">{{ trans('cruds.user.fields.background_image') }}</label>
        <x-dropzone id="background_image" name="background_image" action="{{ route('admin.users.storeMedia') }}" collection-name="user_background_image" max-file-size="2" max-width="4096" max-height="4096" max-files="1" />
        <div class="validation-message">
            {{ $errors->first('mediaCollections.user_background_image') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.user.fields.background_image_helper') }}
        </div>
    </div>
    <div style="display:none;" class="form-group {{ $errors->has('user.locale') ? 'invalid' : '' }}">
        <label class="form-label" for="locale">{{ trans('cruds.user.fields.locale') }}</label>
        <input class="form-control" type="text" name="locale" id="locale" wire:model.defer="user.locale">
        <div class="validation-message">
            {{ $errors->first('user.locale') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.user.fields.locale_helper') }}
        </div>
    </div>
    <div style="display:none;"  class="form-group {{ $errors->has('user.status') ? 'invalid' : '' }}">
        <label class="form-label required">{{ trans('cruds.user.fields.status') }}</label>
        <select class="form-control" wire:model="user.status">
            <option value="null" disabled>{{ trans('global.pleaseSelect') }}...</option>
            @foreach($this->listsForFields['status'] as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
        </select>
        <div class="validation-message">
            {{ $errors->first('user.status') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.user.fields.status_helper') }}
        </div>
    </div>

    @if ($this->type != 'cms')


        <div class="form-group {{ $errors->has('user.is_notify_email') ? 'invalid' : '' }}">
            <input class="form-control" type="checkbox" name="is_notify_email" id="is_notify_email" wire:model.defer="user.is_notify_email">
            <label class="form-label inline ml-1" for="is_notify_email">{{ trans('cruds.user.fields.is_notify_email') }}</label>
            <div class="validation-message">
                {{ $errors->first('user.is_notify_email') }}
            </div>
            <div class="help-block">
                {{ trans('cruds.user.fields.is_notify_email_helper') }}
            </div>
        </div>
        <div class="form-group {{ $errors->has('user.is_notify_sms') ? 'invalid' : '' }}">
            <input class="form-control" type="checkbox" name="is_notify_sms" id="is_notify_sms" wire:model.defer="user.is_notify_sms">
            <label class="form-label inline ml-1" for="is_notify_sms">{{ trans('cruds.user.fields.is_notify_sms') }}</label>
            <div class="validation-message">
                {{ $errors->first('user.is_notify_sms') }}
            </div>
            <div class="help-block">
                {{ trans('cruds.user.fields.is_notify_sms_helper') }}
            </div>
        </div>
        <div class="form-group {{ $errors->has('user.is_notify_push') ? 'invalid' : '' }}">
            <input class="form-control" type="checkbox" name="is_notify_push" id="is_notify_push" wire:model.defer="user.is_notify_push">
            <label class="form-label inline ml-1" for="is_notify_push">{{ trans('cruds.user.fields.is_notify_push') }}</label>
            <div class="validation-message">
                {{ $errors->first('user.is_notify_push') }}
            </div>
            <div class="help-block">
                {{ trans('cruds.user.fields.is_notify_push_helper') }}
            </div>
        </div>
        <div class="form-group {{ $errors->has('user.is_marketing') ? 'invalid' : '' }}">
            <input class="form-control" type="checkbox" name="is_marketing" id="is_marketing" wire:model.defer="user.is_marketing">
            <label class="form-label inline ml-1" for="is_marketing">{{ trans('cruds.user.fields.is_marketing') }}</label>
            <div class="validation-message">
                {{ $errors->first('user.is_marketing') }}
            </div>
            <div class="help-block">
                {{ trans('cruds.user.fields.is_marketing_helper') }}
            </div>
        </div>

        <div><p class="pt-5 text-2xl">Emergency contact section</p></div>
        <div class="form-group {{ $errors->has('user.ec_fullname') ? 'invalid' : '' }}">
            <label class="form-label" for="ec_fullname">{{ trans('cruds.user.fields.ec_fullname') }}</label>
            <input class="form-control" type="text" name="ec_fullname" id="ec_fullname" wire:model.defer="user.ec_fullname">
            <div class="validation-message">
                {{ $errors->first('user.ec_fullname') }}
            </div>
            <div class="help-block">
                {{ trans('cruds.user.fields.ec_fullname_helper') }}
            </div>
        </div>
        <div class="form-group {{ $errors->has('user.ec_relationship') ? 'invalid' : '' }}">
            <label class="form-label" for="ec_relationship">{{ trans('cruds.user.fields.ec_relationship') }}</label>
            <x-select-list class="form-control" id="ec_relationship" name="ec_relationship" :options="$this->listsForFields['ec_relationship']" wire:model="user.ec_relationship" />
            <div class="validation-message">
                {{ $errors->first('user.ec_relationship') }}
            </div>
            <div class="help-block">
                {{ trans('cruds.user.fields.ec_relationship_helper') }}
            </div>
        </div>
        <div class="form-group {{ $errors->has('user.ec_main_pcode') ? 'invalid' : '' }}">
            <label class="form-label ">{{ trans('cruds.user.fields.ec_main_pcode') }}</label>
            <select class="form-control" wire:model="user.ec_main_pcode">
                <option value="null">{{ trans('global.pleaseSelect') }}...</option>
                @foreach($this->listsForFields['phone_code'] as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
            <div class="validation-message">
                {{ $errors->first('user.ec_main_pcode') }}
            </div>
            <div class="help-block">
                {{ trans('cruds.user.fields.ec_main_pcode_helper') }}
            </div>
        </div>
        <div class="form-group {{ $errors->has('user.ec_main_pnum') ? 'invalid' : '' }}">
            <label class="form-label" for="ec_main_pnum">{{ trans('cruds.user.fields.ec_main_pnum') }}</label>
            <input class="form-control" type="text" name="ec_main_pnum" id="ec_main_pnum" wire:model.defer="user.ec_main_pnum">
            <div class="validation-message">
                {{ $errors->first('user.ec_main_pnum') }}
            </div>
            <div class="help-block">
                {{ trans('cruds.user.fields.ec_main_pnum_helper') }}
            </div>
        </div>
        <div class="form-group {{ $errors->has('user.ec_alt_pcode') ? 'invalid' : '' }}">
            <label class="form-label ">{{ trans('cruds.user.fields.ec_alt_pcode') }}</label>
            <select class="form-control" wire:model="user.ec_alt_pcode">
                <option value="null" >{{ trans('global.pleaseSelect') }}...</option>
                @foreach($this->listsForFields['phone_code'] as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
            <div class="validation-message">
                {{ $errors->first('user.ec_alt_pcode') }}
            </div>
            <div class="help-block">
                {{ trans('cruds.user.fields.ec_alt_pcode_helper') }}
            </div>
        </div>
        <div class="form-group {{ $errors->has('user.ec_alt_pnum') ? 'invalid' : '' }}">
            <label class="form-label" for="ec_alt_pnum">{{ trans('cruds.user.fields.ec_alt_pnum') }}</label>
            <input class="form-control" type="text" name="ec_alt_pnum" id="ec_alt_pnum" wire:model.defer="user.ec_alt_pnum">
            <div class="validation-message">
                {{ $errors->first('user.ec_alt_pnum') }}
            </div>
            <div class="help-block">
                {{ trans('cruds.user.fields.ec_alt_pnum_helper') }}
            </div>
        </div>
        <div class="form-group {{ $errors->has('user.ec_email') ? 'invalid' : '' }}">
            <label class="form-label" for="ec_email">{{ trans('cruds.user.fields.ec_email') }}</label>
            <input class="form-control" type="text" name="ec_email" id="ec_email" wire:model.defer="user.ec_email">
            <div class="validation-message">
                {{ $errors->first('user.ec_email') }}
            </div>
            <div class="help-block">
                {{ trans('cruds.user.fields.ec_email_helper') }}
            </div>
        </div>

    @endif

    
    <div class="form-group">
        <button class="btn btn-indigo mr-2" type="submit">
            {{ trans('global.save') }}
        </button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            {{ trans('global.cancel') }}
        </a>
    </div>
</form>
