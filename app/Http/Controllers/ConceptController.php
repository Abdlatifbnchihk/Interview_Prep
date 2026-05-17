<?php

namespace App\Http\Controllers;

use App\Enums\ConceptStatus;
use App\Http\Requests\StoreConceptRequest;
use App\Http\Requests\UpdateConceptRequest;
use App\Http\Requests\UpdateConceptStatusRequest;
use App\Models\Concept;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConceptController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Concept::class);

        $concepts = Concept::query()
            ->forUser(auth()->id())
            ->when($request->domain_id, fn($q) => $q->byDomain($request->domain_id))
            ->when($request->status, fn($q) => $q->byStatus($request->status))
            ->when($request->difficulty, fn($q) => $q->byDifficulty($request->difficulty))
            ->with('domain')
            ->latest()
            ->paginate(15);

        $domains = auth()->user()->domains()->get();
        $trashCount = \App\Models\Concept::onlyTrashed()->count();

        return view('concepts.index', compact('concepts', 'domains', 'trashCount'));
    }

    public function create(): View
    {
        $this->authorize('create', Concept::class);

        $domains = auth()->user()->domains()->get();

        return view('concepts.create', compact('domains'));
    }

    public function store(StoreConceptRequest $request): RedirectResponse
    {
        $this->authorize('create', Concept::class);

        Concept::create($request->validated());

        return redirect()->route('concepts.show', Concept::latest()->first())
            ->with('success', 'Concept created.');
    }

    public function show(Concept $concept): View
    {
        $this->authorize('view', $concept);

        return view('concepts.show', compact('concept'));
    }

    public function edit(Concept $concept): View
    {
        $this->authorize('update', $concept);

        $domains = auth()->user()->domains()->get();

        return view('concepts.edit', compact('concept', 'domains'));
    }

    public function update(UpdateConceptRequest $request, Concept $concept): RedirectResponse
    {
        $this->authorize('update', $concept);

        $concept->update($request->validated());

        return redirect()->route('concepts.show', $concept)
            ->with('success', 'Concept updated.');
    }

    public function destroy(Concept $concept): RedirectResponse
    {
        $this->authorize('delete', $concept);

        $concept->delete();

        return redirect()->route('concepts.index')
            ->with('success', 'Concept moved to trash.');
    }

    public function updateStatus(UpdateConceptStatusRequest $request, Concept $concept): RedirectResponse
    {
        $this->authorize('update', $concept);

        $concept->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status updated.');
    }

    public function trash(): View
    {
        $this->authorize('viewAny', Concept::class);

        $concepts = Concept::onlyTrashed()
            ->with('domain')
            ->latest('deleted_at')
            ->paginate(20);

        return view('concepts.trash', compact('concepts'));
    }

    public function restore(int $id): RedirectResponse
    {
        $concept = Concept::onlyTrashed()->findOrFail($id);
        $this->authorize('restore', $concept);
        $concept->restore();

        return redirect()->route('concepts.index')
            ->with('success', 'Concept restored.');
    }

    public function forceDelete(int $id): RedirectResponse
    {
        $concept = Concept::onlyTrashed()->findOrFail($id);
        $this->authorize('forceDelete', $concept);
        $concept->forceDelete();

        return redirect()->route('concepts.trash')
            ->with('success', 'Concept permanently deleted.');
    }
}