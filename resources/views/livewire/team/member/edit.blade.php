<form wire:submit.prevent="submit" class="pt-3">

    <div class="form-group {{ $errors->has('team.name') ? 'invalid' : '' }}">
        <label class="form-label">Username: {{$member->team_member?->name}}</label>
    </div>
    <div class="form-group flex">
        <label class="form-label mr-8" for="name">{{ trans('cruds.team.member.fields.player_role') }}</label>
        <div class="form-group">
            @foreach($roles as $role)
                <div class="mr-8">
                        <label class="form-label">
                        <input type="checkbox" value="{{ $role->id }}" class="form-control" wire:model="rolesSelected"> 
                            <span class="ml-3 text-sm">{{ $role->title }}</span>
                        </label>
                    </div>
            @endforeach
        </div>
    </div>
    <div class="form-group {{ $errors->has('member.player_role') ? 'invalid' : '' }}">
        <label class="form-label" for="name">{{ trans('cruds.team.member.fields.player_role') }}</label>
        <input class="form-control" type="text" name="player_role" id="player_role" wire:model.defer="member.player_role">
        <div class="validation-message">
            {{ $errors->first('member.player_role') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('member.jersey_number') ? 'invalid' : '' }}">
        <label class="form-label" for="name">{{ trans('cruds.team.member.fields.jersey_number') }}</label>
        <input class="form-control" type="text" name="jersey_number" id="jersey_number" wire:model.defer="member.jersey_number">
        <div class="validation-message">
            {{ $errors->first('member.jersey_number') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('member.status') ? 'invalid' : '' }}">
        <label class="form-label required">{{ trans('cruds.team.member.fields.status') }}</label>
        {{-- <select class="form-control" wire:model="member.status">
            <option value="null" disabled>{{ trans('global.pleaseSelect') }}...</option>
            @foreach($this->listsForFields['status'] as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
        </select> --}}
        @foreach($this->listsForFields['status'] as $key => $value)
            <input wire:model="member.status" name="status" type="radio" value="{{$key}}"> {{$value}}
        @endforeach
        <div class="validation-message">
            {{ $errors->first('member.status') }}
        </div>
    </div>
    <div class="form-group">
        <button class="btn btn-indigo mr-2" type="submit">
            {{ trans('global.save') }}
        </button>
        <a href="{{ route('admin.teams.show', $team) }}" class="btn btn-secondary">
            {{ trans('global.cancel') }}
        </a>
    </div>
</form>
