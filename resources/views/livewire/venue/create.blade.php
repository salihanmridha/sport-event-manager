<form wire:submit.prevent="submit" class="pt-3">
    {{--        Name--}}
    <div class="form-group {{ $errors->has('venue.name') ? 'invalid' : '' }}">
        <label class="form-label required" for="name">{{ trans('cruds.venue.fields.name') }}</label>
        <input class="form-control" type="text" name="name" id="name" wire:model.defer="venue.name">
        <div class="validation-message">
            {{ $errors->first('venue.name') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.venue.fields.name_helper') }}
        </div>
    </div>

    {{--         venue type --}}
    <div class="form-group {{ $errors->has('type') ? 'invalid' : '' }}">
        <label class="form-label required" for="type">Type</label>
        <x-select-list class="form-control" id="type" name="type" :options="$this->listsForFields['venueType']"
                       wire:model="type" />
        <div class="validation-message">
            {{ $errors->first('type') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.currency.fields.country_helper') }}
        </div>
    </div>


    {{-- Workdays --}}
    <div class="form-group {{ $errors->has('workdays') ? 'invalid' : '' }}">
        <label class="form-label required" for="days">{{ trans('cruds.venue.fields.work_day') }}</label>
        <x-select-list class="form-control" id="workdays" name="workdays" :options="$this->listsForFields['workdays']"
                       wire:model="workdays" multiple="" />
        <div class="validation-message">
            {{ $errors->first('workdays') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.venue.fields.work_day_helper') }}
        </div>
    </div>

    {{--Start time - end time--}}
    <div class="flex form-group">
        <div class="mr-8">
            <div class="form-group {{ $errors->has('venue.start_open_time') ? 'invalid' : '' }}">
                <label class="form-label required" for="name">Start open time</label>
                <x-date-picker picker="time" class="inline-block w-full" wire:model="venue.start_open_time" id="start_open_time"
                               name="start_open_time"></x-date-picker>
                <div class="validation-message">
                    {{ $errors->first('venue.start_open_time') }}
                </div>
                <div class="help-block">
                    {{ trans('cruds.venue.fields.lat_helper') }}
                </div>
            </div>
        </div>
        <div class="ml-5">
            <div class="form-group {{ $errors->has('venue.end_open_time') ? 'invalid' : '' }}">
                <label class="form-label required" for="name">End open time</label>
                <x-date-picker picker="time" class="inline-block w-full" wire:model="venue.end_open_time" id="end_open_time"
                               name="end_open_time"></x-date-picker>
                <div class="validation-message">
                    {{ $errors->first('venue.end_open_time') }}
                </div>
                <div class="help-block">
                    {{ trans('cruds.venue.fields.long_helper') }}
                </div>
            </div>
        </div>
    </div>

    {{--         Adress --}}
    <div class="form-group {{ $errors->has('venue.address') ? 'invalid' : '' }}">
        <label class="form-label required" for="name">{{ trans('cruds.venue.fields.address') }}</label>
        <input class="form-control" type="text" name="address" id="address" wire:model.defer="venue.address">
        <div class="validation-message">
            {{ $errors->first('venue.address') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.venue.fields.address_helper') }}
        </div>
    </div>

    {{--location--}}
    <div class="form-group {{ $errors->has('venue.location') ? 'invalid' : '' }}">
        <label class="form-label required" for="location">{{ trans('cruds.event.fields.location') }}</label>
        <input class="form-control" type="text" name="location" id="location"
               wire:model.defer="venue.location">
        <div class="validation-message">
            {{ $errors->first('venue.location') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.event.fields.location_helper') }}
        </div>
    </div>

    {{--         Lat-Long --}}
    <div class="flex form-group">
        <div class="mr-8">
            <div class="form-group {{ $errors->has('venue.lat') ? 'invalid' : '' }}">
                <label class="form-label required" for="name">{{ trans('cruds.venue.fields.lat') }}</label>
                <input class="form-control location" type="text" name="lat" id="lat" wire:model.defer="venue.lat">
                <div class="validation-message">
                    {{ $errors->first('venue.lat') }}
                </div>
                <div class="help-block">
                    {{ trans('cruds.venue.fields.lat_helper') }}
                </div>
            </div>
        </div>
        <div class="ml-5">
            <div class="form-group {{ $errors->has('venue.long') ? 'invalid' : '' }}">
                <label class="form-label required" for="name">{{ trans('cruds.venue.fields.long') }}</label>
                <input class="form-control location" type="text" name="long" id="long" wire:model.defer="venue.long">
                <div class="validation-message">
                    {{ $errors->first('venue.long') }}
                </div>
                <div class="help-block">
                    {{ trans('cruds.venue.fields.long_helper') }}
                </div>
            </div>
        </div>
    </div>

    <x-map place_name="venue.location" lat="venue.lat" lng="venue.long" height="500px"></x-map>
    {{--         Country --}}
    <div class="form-group {{ $errors->has('venue.country_id') ? 'invalid' : '' }}">
        <label class="form-label required" for="sport">{{ trans('cruds.country.title') }}</label>
        <x-select-list class="form-control" id="country" name="country_id" :options="$this->listsForFields['countries']"
                       wire:model="venue.country_id" />
        <div class="validation-message">
            {{ $errors->first('venue.country_id') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.currency.fields.country_helper') }}
        </div>
    </div>

    {{--         Phone code --}}
    <div class="form-group {{ $errors->has('venue.phone_code') ? 'invalid' : '' }}">
        <label class="form-label">{{ trans('cruds.user.fields.phone_code') }}</label>
        <x-select-list class="form-control" id="phone_code" name="phone_code" :options="$this->listsForFields['phone_code']"
                       wire:model="venue.phone_code" />
        <div class="validation-message">
            {{ $errors->first('user.phone_code') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.venue.fields.phone_code_helper') }}
        </div>
    </div>

    {{--         Phone number --}}
    <div class="form-group {{ $errors->has('venue.phone_number') ? 'invalid' : '' }}">
        <label class="form-label" for="phone_number">{{ trans('cruds.venue.fields.phone_number') }}</label>
        <input class="form-control" type="text" name="phone_number" id="phone_number"
               wire:model.defer="venue.phone_number">
        <div class="validation-message">
            {{ $errors->first('venue.phone_number') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.venue.fields.phone_number_helper') }}
        </div>
    </div>

    {{--         Email --}}
    <div class="form-group {{ $errors->has('venue.email') ? 'invalid' : '' }}">
        <label class="form-label" for="email">{{ trans('cruds.venue.fields.email') }}</label>
        <input class="form-control" type="text" name="email" id="email" wire:model.defer="venue.email">
        <div class="validation-message">
            {{ $errors->first('venue.email') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.venue.fields.email_helper') }}
        </div>
    </div>

    {{--         Bio --}}
    <div class="form-group {{ $errors->has('venue.bio') ? 'invalid' : '' }}">
        <label class="form-label" for="bio">{{ trans('cruds.venue.fields.bio') }}</label>
        <input class="form-control" type="text" name="bio" id="bio" wire:model.defer="venue.bio">
        <div class="validation-message">
            {{ $errors->first('venue.bio') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.venue.fields.bio_helper') }}
        </div>
    </div>

    {{--         Rules --}}
    <div class="form-group {{ $errors->has('venue.rules') ? 'invalid' : '' }}">
        <label class="form-label" for="bio">{{ trans('cruds.venue.fields.rule') }}</label>
        <input class="form-control" type="text" name="rules" id="rules" wire:model.defer="venue.rules">
        <div class="validation-message">
            {{ $errors->first('venue.rules') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.venue.fields.bio_helper') }}
        </div>
    </div>

    {{--         Safety --}}
    <div class="form-group {{ $errors->has('venue.safety') ? 'invalid' : '' }}">
        <label class="form-label" for="bio">{{ trans('cruds.venue.fields.safety') }}</label>
        <input class="form-control" type="text" name="safety" id="safety" wire:model.defer="venue.safety">
        <div class="validation-message">
            {{ $errors->first('venue.safety') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.venue.fields.bio_helper') }}
        </div>
    </div>

    {{--         Images--}}
    <div class="form-group {{ $errors->has('mediaCollections.upload_photo') ? 'invalid' : '' }}">
        <label class="form-label" for="upload_photo">{{ trans('cruds.event.fields.upload_photo') }}</label>
        <x-dropzone id="upload_photo" name="upload_photo" action="{{ route('admin.venues.storeMedia') }}"
                    collection-name="upload_photo" max-file-size="2" max-width="4096" max-height="4096" max-files="2" />
        <div class="validation-message">
            {{ $errors->first('mediaCollections.upload_photo') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.event.fields.upload_photo_helper') }}
        </div>
    </div>

    {{--         Banner--}}
    <div class="form-group {{ $errors->has('mediaCollections.upload_photo') ? 'invalid' : '' }}">
        <label class="form-label" for="upload_banner">{{ trans('cruds.venue.fields.banner') }}</label>
        <x-dropzone id="upload_banner" name="upload_baner" action="{{ route('admin.venues.storeMedia') }}"
                    collection-name="banner" max-file-size="1" max-width="4096" max-height="4096" max-files="1" />
        <div class="validation-message">
            {{ $errors->first('mediaCollections.upload_photo') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.event.fields.upload_photo_helper') }}
        </div>
    </div>

    {{--Owner info--}}
    <div class="card-title mt-8"> {{ trans('cruds.venue.fields.owner_info') }}</div>
    <div class="form-group {{ $errors->has('user.email') ? 'invalid' : '' }}">
        <label class="form-label required" for="email">{{ trans('cruds.user.fields.email') }}</label>
        <input class="form-control" type="text" name="email" id="email" wire:model.defer="user.email">
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
    <div class="form-group {{ $errors->has('user.phone') ? 'invalid' : '' }}">
        <label class="form-label required" for="phone">{{ trans('cruds.user.fields.phone') }}</label>
        <input class="form-control" type="text" name="user.phone" id="phone" wire:model.defer="user.phone">
        <div class="validation-message">
            {{ $errors->first('user.phone') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.user.fields.phone_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('user.phone_code') ? 'invalid' : '' }}">
        <label class="form-label required">{{ trans('cruds.user.fields.phone_code') }}</label>
        <x-select-list class="form-control" id="user_phone_code" name="user_phone_code" :options="$this->listsForFields['phone_code']"
                       wire:model="user.phone_code" />
        <div class="validation-message">
            {{ $errors->first('user.phone_code') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.user.fields.phone_code_helper') }}
        </div>
    </div>

    <div class="form-group">
        <button class="btn btn-indigo mr-2" type="submit">
            {{ trans('global.save') }}
        </button>
        <a href="{{ route('admin.venues.index') }}" class="btn btn-secondary">
            {{ trans('global.cancel') }}
        </a>
    </div>
</form>
