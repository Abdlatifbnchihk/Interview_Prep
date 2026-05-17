<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDomainRequest;
use App\Http\Requests\UpdateDomainRequest;
use App\Models\Domain;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DomainController extends Controller
{
    public function index(): View
    {
        $domains = auth()->user()->domains()->withCount('concepts')->get();
        $trashCount = auth()->user()->domains()->onlyTrashed()->count();
        return view('domains.index', compact('domains', 'trashCount'));
    }

    public function create(): View
    {
        return view('domains.create');
    }

    public function store(StoreDomainRequest $request): RedirectResponse
    {
        $this->authorize('create', Domain::class);
        auth()->user()->domains()->create($request->validated());
        return redirect()->route('domains.index')->with('success', 'Domain created.');
    }

    public function show(Domain $domain): View
    {
        $this->authorize('view', $domain);
        $domain->load('concepts');
        return view('domains.show', compact('domain'));
    }

    public function edit(Domain $domain): View
    {
        $this->authorize('update', $domain);
        return view('domains.edit', compact('domain'));
    }

    public function update(UpdateDomainRequest $request, Domain $domain): RedirectResponse
    {
        $this->authorize('update', $domain);
        $domain->update($request->validated());
        return redirect()->route('domains.index')->with('success', 'Domain updated.');
    }

    public function destroy(Domain $domain): RedirectResponse
    {
        $this->authorize('delete', $domain);
        $domain->delete();
        return redirect()->route('domains.index')->with('success', 'Domain moved to trash.');
    }

    public function trash(): View
    {
        $domains = auth()->user()->domains()->onlyTrashed()->latest('deleted_at')->get();
        return view('domains.trash', compact('domains'));
    }

    public function restore(int $id): RedirectResponse
    {
        $domain = Domain::onlyTrashed()->findOrFail($id);
        $this->authorize('restore', $domain);
        $domain->restore();
        return redirect()->route('domains.index')->with('success', 'Domain restored.');
    }

    public function forceDelete(int $id): RedirectResponse
    {
        $domain = Domain::onlyTrashed()->findOrFail($id);
        $this->authorize('forceDelete', $domain);
        $domain->forceDelete();
        return redirect()->route('domains.trash')->with('success', 'Domain permanently deleted.');
    }
}