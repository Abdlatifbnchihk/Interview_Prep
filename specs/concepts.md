# specs/concepts.md

> **Feature:** Concepts Management
> **Epic:** IP — Epic 3
> **Branch:** `feature/concepts`
> **Tasks:** IP-11, IP-12, IP-13, IP-14, IP-15, IP-16, IP-17
> **Status:** To Do
> **AI Mode:** Plan → Build

---

## 1. Feature Goal

Allow authenticated users to create and manage **technical concepts** within their domains.
Each concept has a title, explanation, difficulty level (Junior / Mid / Senior), and a mastery status (To Review / In Progress / Mastered).
Users can filter concepts by difficulty or status, and update a concept's status inline without navigating away.

**Examples of concepts:** Eloquent Relationships · N+1 Problem · Dependency Injection · SOLID Principles

---

## 2. User Stories

| ID | As a... | I want to... | So that... |
|---|---|---|---|
| US-11 | User | Create a concept under a domain | I can document what I know |
| US-12 | User | Edit a concept's title, explanation, difficulty | I can improve my notes |
| US-13 | User | Delete a concept | I can remove irrelevant topics |
| US-14 | User | View a concept's full detail | I can read my explanation and see my generations |
| US-15 | User | Filter concepts by status or difficulty | I can focus on what needs review |
| US-16 | User | Update a concept's status with one click | I can track progress without opening the edit form |
| US-17 | User | Only access my own concepts | Other users' data stays private |

---

## 3. Routes

```php
// routes/web.php — inside auth middleware group
Route::resource('concepts', ConceptController::class);

// Quick status update — separate route for inline toggle
Route::patch('concepts/{concept}/status', [ConceptController::class, 'updateStatus'])
    ->name('concepts.updateStatus');
```

Expanded resource routes:

| Method | URI | Action | Name |
|---|---|---|---|
| GET | `/concepts` | `index` | `concepts.index` |
| GET | `/concepts/create` | `create` | `concepts.create` |
| POST | `/concepts` | `store` | `concepts.store` |
| GET | `/concepts/{concept}` | `show` | `concepts.show` |
| GET | `/concepts/{concept}/edit` | `edit` | `concepts.edit` |
| PUT/PATCH | `/concepts/{concept}` | `update` | `concepts.update` |
| DELETE | `/concepts/{concept}` | `destroy` | `concepts.destroy` |
| PATCH | `/concepts/{concept}/status` | `updateStatus` | `concepts.updateStatus` |

---

## 4. Controller — ConceptController.php

```php
class ConceptController extends Controller
{
    public function index(Request $request)
    // Authorize: 'viewAny', Concept::class
    // Load concepts for auth user with filters applied
    // Filters: domain_id, status, difficulty (from query string)
    // Return concepts/index.blade.php with $concepts, $domains (for filter dropdowns)

    public function create()
    // Return concepts/create.blade.php
    // Pass: $domains (for domain select), $difficulties, $statuses (from enums)

    public function store(StoreConceptRequest $request)
    // Authorize: 'create', Concept::class
    // Verify domain belongs to auth user before creating
    // Create concept
    // Redirect: concepts.show with success flash

    public function show(Concept $concept)
    // Authorize: 'view', $concept
    // Eager load: $concept->load('domain', 'generations.questions')
    // Return concepts/show.blade.php

    public function edit(Concept $concept)
    // Authorize: 'update', $concept
    // Return concepts/edit.blade.php with $domains, $difficulties, $statuses

    public function update(UpdateConceptRequest $request, Concept $concept)
    // Authorize: 'update', $concept
    // Update concept
    // Redirect: concepts.show with success flash

    public function destroy(Concept $concept)
    // Authorize: 'delete', $concept
    // Delete concept (cascades to generations and questions)
    // Redirect: concepts.index with success flash

    public function updateStatus(Request $request, Concept $concept)
    // Authorize: 'update', $concept
    // Validate: status must be a valid ConceptStatus enum value
    // Update only the status field
    // Redirect back with success flash
}
```

---

## 5. Model — Concept.php

```php
protected $fillable = ['domain_id', 'title', 'explanation', 'difficulty', 'status'];

protected $casts = [
    'difficulty' => ConceptDifficulty::class,
    'status'     => ConceptStatus::class,
];

// Relationships
public function domain(): BelongsTo
{
    return $this->belongsTo(Domain::class);
}

public function generations(): HasMany
{
    return $this->hasMany(QuestionGeneration::class);
}

// Query Scopes
public function scopeByStatus(Builder $query, string $status): Builder
{
    return $query->where('status', $status);
}

public function scopeByDifficulty(Builder $query, string $difficulty): Builder
{
    return $query->where('difficulty', $difficulty);
}

public function scopeByDomain(Builder $query, int $domainId): Builder
{
    return $query->where('domain_id', $domainId);
}

public function scopeForUser(Builder $query, int $userId): Builder
{
    return $query->whereHas('domain', fn($q) => $q->where('user_id', $userId));
}
```

---

## 6. Enums

### app/Enums/ConceptDifficulty.php
```php
enum ConceptDifficulty: string
{
    case Junior = 'junior';
    case Mid    = 'mid';
    case Senior = 'senior';

    public function label(): string
    {
        return match($this) {
            self::Junior => 'Junior',
            self::Mid    => 'Mid-level',
            self::Senior => 'Senior',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Junior => 'green',
            self::Mid    => 'amber',
            self::Senior => 'red',
        };
    }
}
```

### app/Enums/ConceptStatus.php
```php
enum ConceptStatus: string
{
    case ToReview   = 'to_review';
    case InProgress = 'in_progress';
    case Mastered   = 'mastered';

    public function label(): string
    {
        return match($this) {
            self::ToReview   => 'To Review',
            self::InProgress => 'In Progress',
            self::Mastered   => 'Mastered',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::ToReview   => 'gray',
            self::InProgress => 'amber',
            self::Mastered   => 'green',
        };
    }
}
```

---

## 7. Migration — create_concepts_table

```php
Schema::create('concepts', function (Blueprint $table) {
    $table->id();
    $table->foreignId('domain_id')->constrained()->onDelete('cascade');
    $table->string('title');
    $table->text('explanation');
    $table->enum('difficulty', ['junior', 'mid', 'senior'])->default('junior');
    $table->enum('status', ['to_review', 'in_progress', 'mastered'])->default('to_review');
    $table->timestamps();
});
```

---

## 8. Form Requests

### StoreConceptRequest
```php
public function rules(): array
{
    return [
        'domain_id'   => ['required', 'exists:domains,id'],
        'title'       => ['required', 'string', 'max:255'],
        'explanation' => ['required', 'string'],
        'difficulty'  => ['required', Rule::enum(ConceptDifficulty::class)],
        'status'      => ['required', Rule::enum(ConceptStatus::class)],
    ];
}
```

### UpdateConceptRequest
```php
public function rules(): array
{
    return [
        'domain_id'   => ['required', 'exists:domains,id'],
        'title'       => ['required', 'string', 'max:255'],
        'explanation' => ['required', 'string'],
        'difficulty'  => ['required', Rule::enum(ConceptDifficulty::class)],
        'status'      => ['required', Rule::enum(ConceptStatus::class)],
    ];
}
```

---

## 9. Policy — ConceptPolicy.php

```php
public function viewAny(User $user): bool
{
    return true;
}

public function view(User $user, Concept $concept): bool
{
    return $user->id === $concept->domain->user_id;
}

public function create(User $user): bool
{
    return true;
}

public function update(User $user, Concept $concept): bool
{
    return $user->id === $concept->domain->user_id;
}

public function delete(User $user, Concept $concept): bool
{
    return $user->id === $concept->domain->user_id;
}
```

> Note: ownership is verified via the parent domain (`$concept->domain->user_id`), since concepts don't have a direct `user_id` column.

---

## 10. Filtering Logic

```php
// ConceptController@index
$concepts = Concept::query()
    ->scopeForUser(auth()->id())
    ->when($request->domain_id, fn($q) => $q->byDomain($request->domain_id))
    ->when($request->status,    fn($q) => $q->byStatus($request->status))
    ->when($request->difficulty, fn($q) => $q->byDifficulty($request->difficulty))
    ->with('domain')
    ->latest()
    ->paginate(15);
```

---

## 11. Views

```
resources/views/concepts/
├── index.blade.php     — filterable list with badges and status toggles
├── create.blade.php    — form: domain select, title, explanation, difficulty, status
├── edit.blade.php      — pre-filled form
├── show.blade.php      — full concept detail + generation history
```

**Index view UI elements:**
- Filter bar: dropdown for domain, status, difficulty
- Concept card: title, domain badge, difficulty badge (colored), status badge
- Quick status toggle button (PATCH to `concepts.updateStatus`)
- Edit / Delete action buttons

---

## 12. Edge Cases & Error States

| Scenario | Expected Behavior |
|---|---|
| User tries to create concept for another user's domain | Validation fails: `domain_id` ownership check in `store()` |
| User tries to view another user's concept | 403 Forbidden via ConceptPolicy |
| Invalid status value on quick toggle | Validation error returned |
| Concept deleted | All QuestionGenerations and Questions cascade-deleted |
| No concepts match current filters | Empty state: "No concepts found for the selected filters." |
| No concepts at all | Empty state: "You have no concepts yet. Start by creating one." |

---

## 13. Laravel Commands

```bash
php artisan make:model Concept -m
php artisan make:controller ConceptController --resource
php artisan make:request StoreConceptRequest
php artisan make:request UpdateConceptRequest
php artisan make:policy ConceptPolicy --model=Concept
mkdir app/Enums
# manually create ConceptDifficulty.php and ConceptStatus.php
```

---

## 14. Commits for This Feature

```bash
feat(concepts): create Concept model and migration — AI-assisted
feat(concepts): add ConceptDifficulty and ConceptStatus enums — AI-assisted
feat(concepts): implement CRUD resource controller — AI-assisted workflow
feat(concepts): add StoreConceptRequest and UpdateConceptRequest — AI-assisted
feat(concepts): implement ConceptPolicy with domain ownership check — AI-assisted
feat(concepts): add query scopes for filtering by status and difficulty — AI-assisted
feat(concepts): add quick inline status update route and action — AI-assisted
feat(concepts): build index, create, edit, show views with badges — AI-assisted
```