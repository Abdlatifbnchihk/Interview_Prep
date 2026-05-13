# specs/domains.md

> **Feature:** Domains Management
> **Epic:** IP — Epic 2
> **Branch:** `feature/domains`
> **Tasks:** IP-5, IP-6, IP-7, IP-9, IP-10
> **Status:** To Do
> **AI Mode:** Plan → Build

---

## 1. Feature Goal

Allow authenticated users to create and manage **technical domains** (categories of knowledge).
Each domain belongs to the authenticated user and contains many concepts.
The domain list must display progress statistics (how many concepts are mastered vs in progress vs to review).

**Examples of domains:** Laravel ORM · PHP OOP · REST API · MySQL · Algorithms

---

## 2. User Stories

| ID | As a... | I want to... | So that... |
|---|---|---|---|
| US-5 | User | Create a domain with a name and color | I can categorize my technical knowledge |
| US-6 | User | See a list of all my domains | I get an overview of my learning areas |
| US-7 | User | Edit a domain's name or color | I can correct or update it |
| US-8 | User | Delete a domain | I can remove topics I no longer need |
| US-9 | User | See progress stats per domain | I know how many concepts I have mastered |
| US-10 | User | Only see my own domains | Other users' data stays private |

---

## 3. Routes

```php
// routes/web.php — inside auth middleware group
Route::resource('domains', DomainController::class);
```

Expanded:

| Method | URI | Action | Name |
|---|---|---|---|
| GET | `/domains` | `index` | `domains.index` |
| GET | `/domains/create` | `create` | `domains.create` |
| POST | `/domains` | `store` | `domains.store` |
| GET | `/domains/{domain}` | `show` | `domains.show` |
| GET | `/domains/{domain}/edit` | `edit` | `domains.edit` |
| PUT/PATCH | `/domains/{domain}` | `update` | `domains.update` |
| DELETE | `/domains/{domain}` | `destroy` | `domains.destroy` |

---

## 4. Controller — DomainController.php

```php
class DomainController extends Controller
{
    public function index()
    // Load all domains for auth user with concept counts by status
    // Query: auth()->user()->domains()->withCount(['concepts', ...])

    public function create()
    // Return domains/create.blade.php

    public function store(StoreDomainRequest $request)
    // Authorize: 'create', Domain::class
    // Create: auth()->user()->domains()->create($request->validated())
    // Redirect: domains.index with success flash

    public function show(Domain $domain)
    // Authorize: 'view', $domain
    // Load domain with its concepts
    // Return domains/show.blade.php

    public function edit(Domain $domain)
    // Authorize: 'update', $domain
    // Return domains/edit.blade.php

    public function update(UpdateDomainRequest $request, Domain $domain)
    // Authorize: 'update', $domain
    // Update domain
    // Redirect: domains.index with success flash

    public function destroy(Domain $domain)
    // Authorize: 'delete', $domain
    // Delete domain (cascades to concepts)
    // Redirect: domains.index with success flash
}
```

---

## 5. Model — Domain.php

```php
protected $fillable = ['name', 'color', 'user_id'];

// Relationships
public function user(): BelongsTo
{
    return $this->belongsTo(User::class);
}

public function concepts(): HasMany
{
    return $this->hasMany(Concept::class);
}

// Progress stats helper
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
```

---

## 6. Migration — create_domains_table

```php
Schema::create('domains', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->string('name');
    $table->string('color')->default('#6366F1');
    $table->timestamps();
});
```

---

## 7. Form Requests

### StoreDomainRequest
```php
public function authorize(): bool
{
    return true; // policy handles it in controller
}

public function rules(): array
{
    return [
        'name'  => ['required', 'string', 'max:255'],
        'color' => ['required', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
    ];
}
```

### UpdateDomainRequest
```php
public function rules(): array
{
    return [
        'name'  => ['required', 'string', 'max:255'],
        'color' => ['required', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
    ];
}
```

---

## 8. Policy — DomainPolicy.php

```php
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
```

---

## 9. Views

```
resources/views/domains/
├── index.blade.php     — grid of domain cards with color badge + stats
├── create.blade.php    — form: name + color picker
├── edit.blade.php      — pre-filled form
├── show.blade.php      — domain detail + list of its concepts
```

**Domain card UI elements:**
- Colored left border or badge using the `color` field
- Domain name
- Total concept count
- Progress bar: mastered / in_progress / to_review
- Edit and Delete action buttons

---

## 10. Edge Cases & Error States

| Scenario | Expected Behavior |
|---|---|
| User tries to view another user's domain | 403 Forbidden via DomainPolicy |
| User tries to delete another user's domain | 403 Forbidden via DomainPolicy |
| Domain name is empty | Validation error: "The name field is required." |
| Color is not a valid hex | Validation error: "The color field format is invalid." |
| Domain has concepts — deleted | Concepts cascade-deleted automatically |
| No domains yet | Empty state shown: "You have no domains yet. Create your first one." |

---

## 11. Laravel Commands

```bash
php artisan make:model Domain -m
php artisan make:controller DomainController --resource
php artisan make:request StoreDomainRequest
php artisan make:request UpdateDomainRequest
php artisan make:policy DomainPolicy --model=Domain
```

---

## 12. Commits for This Feature

```bash
feat(domains): create Domain model and migration — AI-assisted
feat(domains): implement resource controller with CRUD — AI-assisted workflow
feat(domains): add StoreDomainRequest and UpdateDomainRequest — AI-assisted
feat(domains): implement DomainPolicy with ownership checks — AI-assisted
feat(domains): add color badges and progress statistics — AI-assisted
feat(domains): build domain index and show views — AI-assisted
```