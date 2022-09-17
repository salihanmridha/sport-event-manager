<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\ContactRelationship;
use Gate;
use Illuminate\Http\Response;


class ContactRelationshipController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('relationship_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.relationship.index');
    }

    public function create()
    {
        abort_if(Gate::denies('relationship_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.relationship.create');
    }

    public function show(ContactRelationship $relationship)
    {
        abort_if(Gate::denies('relationship_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.relationship.show', ['contactRelationship' => $relationship]);
    }

    public function edit(ContactRelationship $relationship)
    {

        abort_if(Gate::denies('relationship_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // compact('contactRelationship')  == > ['contactRelationship' => $contactRelationship]
        return view('admin.relationship.edit', ['contactRelationship' => $relationship]);
    }
}
