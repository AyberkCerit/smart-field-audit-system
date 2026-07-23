<?php

namespace App\Http\Controllers;

use App\Models\AuditPoint;
use App\Http\Requests\StoreAuditPointRequest;
use App\Http\Requests\UpdateAuditPointRequest;

class AuditPointController extends Controller
{
    public function index()
    {
        $auditPoints = AuditPoint::all();
        return view('audit-points.index', compact('auditPoints'));
    }

    public function create()
    {
        return view('audit-points.create');
    }

    public function store(StoreAuditPointRequest $request)
    {
        AuditPoint::create($request->validated());
        return redirect()->route('audit-points.index')->with('success', 'Audit point created successfully.');
    }

    public function show(AuditPoint $auditPoint)
    {
        return view('audit-points.show', compact('auditPoint'));
    }

    public function edit(AuditPoint $auditPoint)
    {
        return view('audit-points.edit', compact('auditPoint'));
    }

    public function update(UpdateAuditPointRequest $request, AuditPoint $auditPoint)
    {
        $auditPoint->update($request->validated());
        return redirect()->route('audit-points.index')->with('success', 'Audit point updated successfully.');
    }

    public function destroy(AuditPoint $auditPoint)
    {
        $auditPoint->delete();
        return redirect()->route('audit-points.index')->with('success', 'Audit point deleted successfully.');
    }
}
