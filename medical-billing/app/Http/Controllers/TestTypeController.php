<?php

namespace App\Http\Controllers;

use App\Models\TestType;
use Illuminate\Http\Request;

class TestTypeController extends Controller
{
    public function index()
    {
        $testTypes = TestType::withCount('invoices')->orderBy('name')->get();

        return view('test-types.index', compact('testTypes'));
    }

    public function create()
    {
        return view('test-types.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:test_types,name'],
            'fee' => ['required', 'numeric', 'min:0'],
        ]);

        $validated['is_active'] = true;

        TestType::create($validated);

        return redirect()
            ->route('test-types.index')
            ->with('success', 'Test type added successfully.');
    }

    public function edit(TestType $testType)
    {
        return view('test-types.edit', compact('testType'));
    }

    public function update(Request $request, TestType $testType)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:test_types,name,' . $testType->id],
            'fee' => ['required', 'numeric', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $testType->update($validated);

        return redirect()
            ->route('test-types.index')
            ->with('success', 'Test type updated successfully.');
    }

    public function destroy(TestType $testType)
    {
        if ($testType->invoices()->exists()) {
            return redirect()
                ->route('test-types.index')
                ->with('error', 'This test type has invoices linked to it and cannot be deleted. You can deactivate it instead.');
        }

        $testType->delete();

        return redirect()
            ->route('test-types.index')
            ->with('success', 'Test type deleted.');
    }
}
