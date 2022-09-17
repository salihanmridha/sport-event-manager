@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="card bg-blueGray-100">
        <div class="card-header">
            <div class="card-header-container">
                <h6 class="card-title">
                    {{ trans('global.view') }}
                    {{ trans('cruds.currency.title_singular') }}:
                    {{ trans('cruds.currency.fields.id') }}
                    {{ $currency->id }}
                </h6>
            </div>
        </div>

        <div class="card-body">
            <div class="pt-3">
                <table class="table table-view">
                    <tbody class="bg-white">
                        <tr>
                            <th>
                                {{ trans('cruds.currency.fields.id') }}
                            </th>
                            <td>
                                {{ $currency->id }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.currency.fields.name') }}
                            </th>
                            <td>
                                {{ $currency->name }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.currency.fields.country') }}
                            </th>
                            <td>
                                {{ $currency->country?->name }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.currency.fields.code') }}
                            </th>
                            <td>
                                {{ $currency->code }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.currency.fields.symbol') }}
                            </th>
                            <td>
                                {{ $currency->symbol }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.sport.fields.creator') }}
                            </th>
                            <td>
                               <span class="badge badge-relationship">{{ $currency->creator?->email }}</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="form-group">
                @can('currency_edit')
                    <a href="{{ route('admin.currencies.edit', $currency) }}" class="btn btn-indigo mr-2">
                        {{ trans('global.edit') }}
                    </a>
                @endcan
                <a href="{{ route('admin.currencies.index') }}" class="btn btn-secondary">
                    {{ trans('global.back') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
