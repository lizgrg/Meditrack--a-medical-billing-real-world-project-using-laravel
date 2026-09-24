<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CandidateController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $candidates = Candidate::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('passport_no', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('candidates.index', compact('candidates', 'search'));
    }

    public function create()
    {
        return view('candidates.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'passport_no' => ['required', 'string', 'max:50', 'unique:candidates,passport_no'],
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string'],
            'profession' => ['required', 'string', 'max:255'],
            'reference' => ['nullable', 'string', 'max:255'],
        ], [
            'passport_no.unique' => 'This passport number is already registered. Each candidate must have a unique passport number.',
        ]);

        $validated['created_by'] = $request->user()->id;

        $candidate = Candidate::create($validated);

        // Straight into billing next — matches the flowchart (candidate -> select test type -> bill)
        return redirect()
            ->route('candidates.index')
            ->with('success', "Candidate \"{$candidate->name}\" registered successfully. Passport: {$candidate->passport_no}");
    }

    public function edit(Candidate $candidate)
    {
        return view('candidates.edit', compact('candidate'));
    }

    public function update(Request $request, Candidate $candidate)
    {
        $validated = $request->validate([
            'passport_no' => [
                'required', 'string', 'max:50',
                Rule::unique('candidates', 'passport_no')->ignore($candidate->id),
            ],
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string'],
            'profession' => ['required', 'string', 'max:255'],
            'reference' => ['nullable', 'string', 'max:255'],
        ], [
            'passport_no.unique' => 'This passport number is already registered to another candidate.',
        ]);

        $candidate->update($validated);

        return redirect()
            ->route('candidates.index')
            ->with('success', "Candidate \"{$candidate->name}\" updated successfully.");
    }

    public function destroy(Candidate $candidate)
    {
        $candidate->delete();

        return redirect()
            ->route('candidates.index')
            ->with('success', 'Candidate record deleted.');
    }
}
