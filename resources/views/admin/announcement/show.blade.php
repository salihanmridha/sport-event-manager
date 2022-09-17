@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="card bg-blueGray-100">
        <div class="card-header">
            <div class="card-header-container">
                <h6 class="card-title">
                    {{ trans('global.view') }}
                    {{ trans('cruds.announcement.title_singular') }}:
                    {{ trans('cruds.announcement.fields.id') }}
                    {{ $announcement->id }}
                </h6>
            </div>
        </div>

        <div class="card-body">
            <div class="pt-3">
                <table class="table table-view">
                    <tbody class="bg-white">
                        <tr>
                            <th>
                                {{ trans('cruds.announcement.fields.id') }}
                            </th>
                            <td>
                                {{ $announcement->id }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.announcement.fields.title') }}
                            </th>
                            <td>
                                {{ empty($announcement->title) ? "" : ($announcement->title) }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.announcement.fields.about') }}
                            </th>
                            <td>
                                {{ empty($announcement->about) ? "" : ($announcement->about) }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.announcement.fields.start_date') }}
                            </th>
                            <td>
                                {{ Carbon\Carbon::parse($announcement->start_date)?->format('M-d-Y h:i:s A') ?? '__' }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.announcement.fields.end_date') }}
                            </th>
                            <td>
                                {{ Carbon\Carbon::parse($announcement->end_date)?->format('M-d-Y h:i:s A') ?? '__' }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.announcement.fields.creator') }}
                            </th>
                            <td>
                               <span class="badge badge-relationship">{{ $announcement->creator->email ?? '' }}    <br>   At:   {{ Carbon\Carbon::parse($announcement->start_date?->format('M-d-Y h:i:s A') ?? '__')}}</span>
                            </td>
                        </tr>

                        <tr>
                            <th>
                                {{ trans('cruds.announcement.fields.announcementImage') }}
                            </th>
                            <td>
                            @foreach($announcement->background_image as $key => $entry)
                                <div>
                                    <a class="link-photo" href="{{ $entry['url'] }}">
                                        <img  style="width: 10%" src="{{ $entry['url'] }}" alt="{{ $entry['name'] }}" title="{{ $entry['name'] }}">
                                    </a>
                                </div>
                            @endforeach
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.announcement.fields.status') }}
                            </th>
                            <td>
                            @if ($announcement->status == 'publish' && ($announcement->start_date <= now() && now() <= $announcement->end_date))
                                <span class="btn btn-sm btn-warning mr-2">{{ trans('global.ongoing') }}</span>
                            @elseif ($announcement->status == 'publish' &&  now() > $announcement->end_date)
                                <span class="btn btn-sm btn-danger mr-2">{{ trans('global.expired') }}</span>
                            @elseif ($announcement->status == 'publish')
                                <span class="btn btn-sm btn-info mr-2">{{ $statusArray[$announcement->status] }}</span>
                            @else
                                <span class="btn btn-sm btn-success mr-2">{{ $statusArray[$announcement->status] }}</span>
                            @endif
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
            <div class="form-group">
                @can('announcement_edit')
                    <a href="{{ route('admin.announcements.edit', $announcement) }}" class="btn btn-indigo mr-2">
                        {{ trans('global.edit') }}
                    </a>
                @endcan
                <a href="{{ route('admin.announcements.index') }}" class="btn btn-secondary">
                    {{ trans('global.back') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
