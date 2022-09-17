<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrganizatioRole;
use Gate;
use Illuminate\Http\Response;

class OrganizationRoleController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('organization_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.organization_role.index');
    }

    public function create()
    {
        abort_if(Gate::denies('organization_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.organization_role.create');
    }

    public function show($id)
    {
        abort_if(Gate::denies('organization_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $orgRole = OrganizatioRole::with('creator')->find($id);
        return view('admin.organization_role.show', compact('orgRole'));
    }

    public function edit($id)
    {
        abort_if(Gate::denies('organization_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $orgRole = OrganizatioRole::find($id);

        return view('admin.organization_role.edit', compact('orgRole'));
    }
}
