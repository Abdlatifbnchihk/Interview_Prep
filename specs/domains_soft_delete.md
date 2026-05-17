# specs/domains_soft_delete.md

> **Feature:** Domains — Soft Delete
> **Epic:** IP — Epic 2 (extension)
> **Branch:** `feature/domains`
> **Status:** To Do
> **AI Mode:** Plan → Build

---

## 1. Feature Goal

Instead of permanently removing a domain from the database when a user clicks "Delete",
the application will **soft delete** it by setting a `deleted_at` timestamp.

Soft-deleted domains:
- Are invisible in all normal queries (`Domain::all()`, `auth()->user()->domains()`)
- Can be **restored** by the user from a dedicated Trash page
- Can be **permanently deleted** from the Trash page
- Cascade their soft delete concept records are NOT automatically soft-deleted — only the domain is hidden

---

## 2. User Stories

| ID | As a... | I want to... | So that... |
|---|---|---|---|
| US-SD-1 | User | Soft delete a domain | It goes to trash instead of being permanently lost |
| US-SD-2 | User | View my trashed domains | I can see what I deleted |
| US-SD-3 | User | Restore a trashed domain | I can bring it back if I deleted it by mistake |
| US-SD-4 | User | Permanently delete a domain | I can fully remove it when I am sure |
| US-SD-5 | User | See a trash count on the domain index | I know how many domains are in trash |

---

## 3. Migration

Do **not** modify the original `create_domains_table` migration.
Create a new migration:

```bash
php artisan make:migration add_soft_deletes_to_domains_table --table=domains
```

```php
// database/migrations/xxxx_xx_xx_add_soft_deletes_to_domains_table.php

public function up(): void
{
    Schema::table('domains', function (Blueprint $table) {
        $table->softDeletes(); // adds: deleted_at TIMESTAMP NULL DEFAULT NULL
    });
}

public function down(): void
{
    Schema::table('domains', function (Blueprint $table) {
        $table->dropSoftDeletes();
    });
}
```

Run it:

```bash
php artisan migrate
```

---

## 4. Model — Domain.php

Add the `SoftDeletes` trait:

```php
// app/Models/Domain.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Domain extends Model
{
    use SoftDeletes; // ← only change needed on the model

    protected $fillable = ['name', 'color', 'user_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function concepts(): HasMany
    {
        return $this->hasMany(Concept::class);
    }

    public function masteredCount(): int
    {
        return $this->concepts()->where('status', ConceptStatus::Mastered)->count();
    }

    public function inProgressCount(): int
    {
        return $this->concepts()->where('status', ConceptStatus::InProgress)->count();
    }

    public function toReviewCount(): int
    {
        return $this->concepts()->where('status', ConceptStatus::ToReview)->count();
    }
}
```

> From this point, `$domain->delete()` sets `deleted_at` instead of removing the row.
> All normal queries automatically exclude soft-deleted records.

---

## 5. Routes

```php
// routes/web.php — inside auth middleware group

// ⚠️ Trash route MUST be declared BEFORE Route::resource()
// Otherwise Laravel treats "trash" as the {domain} wildcard → domains.show
Route::get('domains/trash', [DomainController::class, 'trash'])
    ->name('domains.trash');

Route::patch('domains/{id}/restore', [DomainController::class, 'restore'])
    ->name('domains.restore');

Route::delete('domains/{id}/force-delete', [DomainController::class, 'forceDelete'])
    ->name('domains.forceDelete');

// Standard resource routes
Route::resource('domains', DomainController::class);
```

Full route table after adding soft delete:

| Method | URI | Action | Name |
|---|---|---|---|
| GET | `/domains` | `index` | `domains.index` |
| GET | `/domains/create` | `create` | `domains.create` |
| POST | `/domains` | `store` | `domains.store` |
| GET | `/domains/trash` | `trash` | `domains.trash` |
| GET | `/domains/{domain}` | `show` | `domains.show` |
| GET | `/domains/{domain}/edit` | `edit` | `domains.edit` |
| PUT/PATCH | `/domains/{domain}` | `update` | `domains.update` |
| DELETE | `/domains/{domain}` | `destroy` | `domains.destroy` (soft) |
| PATCH | `/domains/{id}/restore` | `restore` | `domains.restore` |
| DELETE | `/domains/{id}/force-delete` | `forceDelete` | `domains.forceDelete` |

---

## 6. Controller — DomainController.php

Full updated controller with all soft delete methods added:

```php
// app/Http/Controllers/DomainController.php

namespace App\Http\Controllers;

use App\Models\Domain;
use App\Http\Requests\StoreDomainRequest;
use App\Http\Requests\UpdateDomainRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DomainController extends Controller
{
    // -------------------------------------------------------
    // Existing methods (unchanged)
    // -------------------------------------------------------

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

        return redirect()->route('domains.index')
            ->with('success', 'Domain created successfully.');
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

        return redirect()->route('domains.index')
            ->with('success', 'Domain updated successfully.');
    }

    // -------------------------------------------------------
    // SOFT DELETE — replaces hard delete
    // -------------------------------------------------------

    /**
     * Soft delete: sets deleted_at, domain is hidden from normal queries.
     * Domain can be restored from the Trash page.
     */
    public function destroy(Domain $domain): RedirectResponse
    {
        $this->authorize('delete', $domain);
        $domain->delete(); // soft delete — does NOT remove from DB

        return redirect()->route('domains.index')
            ->with('success', 'Domain moved to trash.');
    }

    // -------------------------------------------------------
    // TRASH — list soft-deleted domains
    // -------------------------------------------------------

    /**
     * Show all soft-deleted domains for the authenticated user.
     */
    public function trash(): View
    {
        $domains = auth()->user()
            ->domains()
            ->onlyTrashed()
            ->latest('deleted_at')
            ->get();

        return view('domains.trash', compact('domains'));
    }

    // -------------------------------------------------------
    // RESTORE — bring a soft-deleted domain back
    // -------------------------------------------------------

    /**
     * Restore a soft-deleted domain.
     * Uses manual findOrFail with onlyTrashed() because route model binding
     * ignores soft-deleted records by default.
     */
    public function restore(int $id): RedirectResponse
    {
        $domain = Domain::onlyTrashed()->findOrFail($id);
        $this->authorize('restore', $domain);
        $domain->restore(); // sets deleted_at back to null

        return redirect()->route('domains.trash')
            ->with('success', 'Domain restored successfully.');
    }

    // -------------------------------------------------------
    // FORCE DELETE — permanent removal
    // -------------------------------------------------------

    /**
     * Permanently remove a soft-deleted domain from the database.
     * This also hard-deletes all related concepts (cascade).
     */
    public function forceDelete(int $id): RedirectResponse
    {
        $domain = Domain::onlyTrashed()->findOrFail($id);
        $this->authorize('forceDelete', $domain);
        $domain->forceDelete(); // permanently removes from DB

        return redirect()->route('domains.trash')
            ->with('success', 'Domain permanently deleted.');
    }
}
```

---

## 7. Policy — DomainPolicy.php

Add `restore` and `forceDelete` methods to the existing policy:

```php
// app/Policies/DomainPolicy.php

namespace App\Policies;

use App\Models\Domain;
use App\Models\User;

class DomainPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Domain $domain): bool
    {
        return $user->id === $domain->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Domain $domain): bool
    {
        return $user->id === $domain->user_id;
    }

    public function delete(User $user, Domain $domain): bool
    {
        return $user->id === $domain->user_id;
    }

    // -------------------------------------------------------
    // NEW — Soft Delete additions
    // -------------------------------------------------------

    /**
     * Only the owner can restore their trashed domain.
     */
    public function restore(User $user, Domain $domain): bool
    {
        return $user->id === $domain->user_id;
    }

    /**
     * Only the owner can permanently delete their domain.
     */
    public function forceDelete(User $user, Domain $domain): bool
    {
        return $user->id === $domain->user_id;
    }
}
```

---

## 8. Views

### 8.1 domains/index.blade.php — add trash link

Add a trash counter link near the top of the index page:

```blade
{{-- Trash counter link --}}
@if ($trashCount > 0)
    <a href="{{ route('domains.trash') }}">
        🗑 Trash ({{ $trashCount }})
    </a>
@endif

{{-- Delete button inside each domain card --}}
<form action="{{ route('domains.destroy', $domain) }}" method="POST">
    @csrf
    @method('DELETE')
    <button type="submit"
        onclick="return confirm('Move this domain to trash?')">
        Delete
    </button>
</form>
```

---

### 8.2 domains/trash.blade.php — new view

```blade
{{-- resources/views/domains/trash.blade.php --}}

@extends('layouts.app')

@section('content')
<div>
    <div>
        <h1>Trash</h1>
        <a href="{{ route('domains.index') }}">← Back to Domains</a>
    </div>

    @if (session('success'))
        <div>{{ session('success') }}</div>
    @endif

    @if ($domains->isEmpty())
        <p>Your trash is empty.</p>
    @else
        @foreach ($domains as $domain)
            <div>
                {{-- Domain info --}}
                <div>
                    <span style="background: {{ $domain->color }}">
                        {{ $domain->name }}
                    </span>
                    <span>Deleted {{ $domain->deleted_at->diffForHumans() }}</span>
                </div>

                {{-- Restore button --}}
                <form action="{{ route('domains.restore', $domain->id) }}"
                      method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit">Restore</button>
                </form>

                {{-- Permanent delete button --}}
                <form action="{{ route('domains.forceDelete', $domain->id) }}"
                      method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        onclick="return confirm('This will permanently delete the domain and ALL its concepts. Are you sure?')">
                        Delete Forever
                    </button>
                </form>
            </div>
        @endforeach
    @endif
</div>
@endsection
```

---

## 9. Eloquent Soft Delete Reference

| Method | Description |
|---|---|
| `$domain->delete()` | Soft delete — sets `deleted_at` to now |
| `$domain->restore()` | Restore — sets `deleted_at` back to `null` |
| `$domain->forceDelete()` | Permanently removes the row from DB |
| `$domain->trashed()` | Returns `true` if the domain is soft-deleted |
| `Domain::all()` | Excludes soft-deleted records automatically |
| `Domain::withTrashed()->get()` | Returns all records including trashed |
| `Domain::onlyTrashed()->get()` | Returns only soft-deleted records |
| `Domain::onlyTrashed()->findOrFail($id)` | Find a trashed domain by ID |

---

## 10. Important Notes

### Route order matters
```php
// ✅ Correct — trash before resource
Route::get('domains/trash', ...)->name('domains.trash');
Route::resource('domains', DomainController::class);

// ❌ Wrong — resource captures "trash" as {domain}
Route::resource('domains', DomainController::class);
Route::get('domains/trash', ...)->name('domains.trash');
```

### Route model binding ignores trashed records
```php
// ❌ This will 404 for soft-deleted domains
public function restore(Domain $domain) { ... }

// ✅ This works correctly — fetch manually
public function restore(int $id)
{
    $domain = Domain::onlyTrashed()->findOrFail($id);
    $this->authorize('restore', $domain);
    $domain->restore();
}
```

### forceDelete cascades hard-deletes concepts
When `forceDelete()` is called on a Domain, all related Concepts are permanently removed from the DB via the `onDelete('cascade')` foreign key constraint — regardless of whether Concept uses SoftDeletes or not.

---

## 11. Edge Cases & Error States

| Scenario | Expected Behavior |
|---|---|
| User tries to restore another user's domain | `DomainPolicy@restore` returns false → 403 |
| User tries to force-delete another user's domain | `DomainPolicy@forceDelete` returns false → 403 |
| `forceDelete` on a domain with concepts | All concepts cascade hard-deleted from DB |
| User visits `/domains/trash` with empty trash | Empty state: "Your trash is empty." |
| User soft-deletes a domain | Concepts still exist in DB, just domain is hidden |
| Trashed domain queried via normal `Domain::find()` | Returns `null` — excluded by SoftDeletes |
| Route model binding with trashed `{domain}` | Returns 404 — use `onlyTrashed()->findOrFail()` manually |

---

## 12. Commit for This Feature

```bash
feat(domains): add soft delete migration for domains table — AI-assisted
feat(domains): add SoftDeletes trait to Domain model — AI-assisted
feat(domains): add trash, restore, forceDelete controller methods — AI-assisted
feat(domains): add restore and forceDelete to DomainPolicy — AI-assisted
feat(domains): add soft delete routes (trash, restore, force-delete) — AI-assisted
feat(domains): build trash view with restore and permanent delete actions — AI-assisted
```