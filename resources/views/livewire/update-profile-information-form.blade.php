<div>
    <h6 class="text-blueGray-400 text-sm mt-3 mb-6 font-bold uppercase">
        {{ __('global.profile_information') }}
    </h6>

    <div class="flex flex-wrap">
        <form wire:submit.prevent="updateProfileInformation" class="w-full">
            <div class="form-group px-4">
                <label class="form-label" for="email">{{ __('global.login_email') }}</label>
                <input class="form-control" id="email" type="text" wire:model.defer="user.email" autocomplete="email">
                @error('user.email')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group px-4 {{ $errors->has('user.first_name') ? 'invalid' : '' }}">
                <label class="form-label required" for="first_name">{{ trans('cruds.user.fields.first_name') }}</label>
                <input class="form-control" type="text" name="first_name" id="first_name" required wire:model.defer="user.first_name">
                <div class="validation-message">
                    {{ $errors->first('user.first_name') }}
                </div>
                <div class="help-block">
                    {{ trans('cruds.user.fields.first_name_helper') }}
                </div>
            </div>
            <div class="form-group px-4 {{ $errors->has('user.last_name') ? 'invalid' : '' }}">
                <label class="form-label required" for="last_name">{{ trans('cruds.user.fields.last_name') }}</label>
                <input class="form-control" type="text" name="last_name" id="last_name" required wire:model.defer="user.last_name">
                <div class="validation-message">
                    {{ $errors->first('user.last_name') }}
                </div>
                <div class="help-block">
                    {{ trans('cruds.user.fields.last_name_helper') }}
                </div>
            </div>
            <div class="form-group px-4 {{ $errors->has('user.gender') ? 'invalid' : '' }}">
                <label class="form-label required">{{ trans('cruds.user.fields.gender') }}</label>
                <select class="form-control" wire:model="user.gender">
                    <option value="null" disabled>{{ trans('global.pleaseSelect') }}...</option>
                    @foreach($this->listsForFields['gender'] as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
                <div class="validation-message">
                    {{ $errors->first('user.gender') }}
                </div>
                <div class="help-block">
                    {{ trans('cruds.user.fields.gender_helper') }}
                </div>
            </div>
            <div class="form-group px-4 {{ $errors->has('user.birth_date') ? 'invalid' : '' }}">
                <label class="form-label required" for="birth_date">{{ trans('cruds.user.fields.birth_date') }}</label>
                <x-date-picker class="form-control" required wire:model="user.birth_date" id="birth_date" name="birth_date" picker="date" />
                <div class="validation-message">
                    {{ $errors->first('user.birth_date') }}
                </div>
                <div class="help-block">
                    {{ trans('cruds.user.fields.birth_date_helper') }}
                </div>
            </div>
            <div class="form-group px-4 {{ $errors->has('user.phone') ? 'invalid' : '' }}">
                <label class="form-label required" for="phone">{{ trans('cruds.user.fields.phone') }}</label>
                <input class="form-control" type="text" name="phone" id="phone" required wire:model.defer="user.phone">
                <div class="validation-message">
                    {{ $errors->first('user.phone') }}
                </div>
                <div class="help-block">
                    {{ trans('cruds.user.fields.phone_helper') }}
                </div>
            </div>
            <div class="form-group px-4 {{ $errors->has('user.phone_code') ? 'invalid' : '' }}">
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
            <div class="form-group px-4 {{ $errors->has('user.country_id') ? 'invalid' : '' }}">
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
            <div class="form-group px-4 {{ $errors->has('user.currency_id') ? 'invalid' : '' }}">
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
            <div class="form-group px-4 {{ $errors->has('user.bio') ? 'invalid' : '' }}">
                <label class="form-label" for="bio">{{ trans('cruds.user.fields.bio') }}</label>
                <textarea class="form-control" name="bio" id="bio" wire:model.defer="user.bio" rows="4"></textarea>
                <div class="validation-message">
                    {{ $errors->first('user.bio') }}
                </div>
                <div class="help-block">
                    {{ trans('cruds.user.fields.bio_helper') }}
                </div>
            </div>
            <div class="form-group px-4 {{ $errors->has('mediaCollections.user_avatar') ? 'invalid' : '' }}">
                <label class="form-label" for="avatar">{{ trans('cruds.user.fields.avatar') }}</label>
                <x-dropzone id="avatar" name="avatar" action="{{ route('admin.users.storeMedia') }}" collection-name="user_avatar" max-file-size="2" max-width="4096" max-height="4096" max-files="1" />
                <div class="validation-message">
                    {{ $errors->first('mediaCollections.user_avatar') }}
                </div>
                <div class="help-block">
                    {{ trans('cruds.user.fields.avatar_helper') }}
                </div>
            </div>
            <div class="form-group px-4 {{ $errors->has('mediaCollections.user_background_image') ? 'invalid' : '' }}">
                <label class="form-label" for="background_image">{{ trans('cruds.user.fields.background_image') }}</label>
                <x-dropzone id="background_image" name="background_image" action="{{ route('admin.users.storeMedia') }}" collection-name="user_background_image" max-file-size="2" max-width="4096" max-height="4096" max-files="1" />
                <div class="validation-message">
                    {{ $errors->first('mediaCollections.user_background_image') }}
                </div>
                <div class="help-block">
                    {{ trans('cruds.user.fields.background_image_helper') }}
                </div>
            </div>
            <div class="form-group px-4 {{ $errors->has('user.is_notify_push') ? 'invalid' : '' }}">
                <input class="form-control" type="checkbox" name="is_notify_push" id="is_notify_push" wire:model.defer="user.is_notify_push">
                <label class="form-label inline ml-1" for="is_notify_push">{{ trans('cruds.user.fields.is_notify_push') }}</label>
                <div class="validation-message">
                    {{ $errors->first('user.is_notify_push') }}
                </div>
                <div class="help-block">
                    {{ trans('cruds.user.fields.is_notify_push_helper') }}
                </div>
            </div>
            <div class="form-group px-4 {{ $errors->has('user.is_notify_email') ? 'invalid' : '' }}">
                <input class="form-control" type="checkbox" name="is_notify_email" id="is_notify_email" wire:model.defer="user.is_notify_email">
                <label class="form-label inline ml-1" for="is_notify_email">{{ trans('cruds.user.fields.is_notify_email') }}</label>
                <div class="validation-message">
                    {{ $errors->first('user.is_notify_email') }}
                </div>
                <div class="help-block">
                    {{ trans('cruds.user.fields.is_notify_email_helper') }}
                </div>
            </div>
            <div class="form-group px-4 {{ $errors->has('user.is_notify_sms') ? 'invalid' : '' }}">
                <input class="form-control" type="checkbox" name="is_notify_sms" id="is_notify_sms" wire:model.defer="user.is_notify_sms">
                <label class="form-label inline ml-1" for="is_notify_sms">{{ trans('cruds.user.fields.is_notify_sms') }}</label>
                <div class="validation-message">
                    {{ $errors->first('user.is_notify_sms') }}
                </div>
                <div class="help-block">
                    {{ trans('cruds.user.fields.is_notify_sms_helper') }}
                </div>
            </div>
            <div class="form-group px-4 {{ $errors->has('user.is_marketing') ? 'invalid' : '' }}">
                <input class="form-control" type="checkbox" name="is_marketing" id="is_marketing" wire:model.defer="user.is_marketing">
                <label class="form-label inline ml-1" for="is_marketing">{{ trans('cruds.user.fields.is_marketing') }}</label>
                <div class="validation-message">
                    {{ $errors->first('user.is_marketing') }}
                </div>
                <div class="help-block">
                    {{ trans('cruds.user.fields.is_marketing_helper') }}
                </div>
            </div>

            <div class="form-group px-4 flex items-center">
                <button class="btn btn-indigo mr-3">
                    {{ __('global.save') }}
                </button>

                <div x-data="{ shown: false, timeout: null }" x-init="@this.on('saved', () => { clearTimeout(timeout); shown = true; timeout = setTimeout(() => { shown = false }, 2000);  })" x-show.transition.out.opacity.duration.1500ms="shown" x-transition:leave.opacity.duration.1500ms class="text-sm" style="display: none;">
                    {{ __('global.saved') }}
                </div>

            </div>
        </form>
    </div>
</div>
