<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuditPoint;

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

    public function store(Request $request)
    {
        AuditPoint::create($request->all());
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

    public function update(Request $request, AuditPoint $auditPoint)
    {
        $auditPoint->update($request->all());
        return redirect()->route('audit-points.index')->with('success', 'Audit point updated successfully.');
    }

    public function destroy(AuditPoint $auditPoint)
    {
        $auditPoint->delete();
        return redirect()->route('audit-points.index')->with('success', 'Audit point deleted successfully.');
    }
}
