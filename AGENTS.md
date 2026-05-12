# AGENTS.md

> **InterviewPrep — AI-Assisted Development Workflow Rules**
> This file must be created at the project root on **Day 1, first commit**.
> Every developer and every coding agent working on this project must read and follow it entirely.

---

## Notce **.
Don't use table or anything have a reltion with QuestionGeneration.

## 1. Project Overview

**InterviewPrep** is a modern Laravel web application that helps developers prepare for technical interviews.
It allows users to organize knowledge by domain, manage concepts, track mastery progression, and generate AI-powered interview questions via the **Groq API**.

**Stack:** Laravel 11 · PHP 8.x · MySQL · Blade · Tailwind CSS · Groq API · Git/GitHub

---

## 2. AI-Assisted Workflow Rules

### 2.1 Plan Before Build — Non-Negotiable

Every feature must go through two phases **in order**:

1. **Plan mode** — use the coding agent to think through architecture, models, routes, and edge cases before writing a single line of code.
2. **Build mode** — only after the plan is reviewed and approved, proceed with implementation.

```
❌ DO NOT start coding before planning.
✅ Always open the agent in Plan mode first, review the output, then switch to Build mode.
```

### 2.2 Code Review Requirement

Every AI-generated code suggestion **must be reviewed manually** before being accepted or committed.

- Read every generated function, class, and migration.
- Verify logic against the project requirements.
- Never blindly accept large code blocks without understanding them.

### 2.3 AI Mention in Every Commit

All commits made with AI assistance **must explicitly mention it** in the commit message.

**Format:**
```
[type](scope): description — AI-assisted
```

**Examples:**
```bash
feat(auth): implement registration & login with Breeze — AI-assisted planning
feat(domains): create Domain CRUD with resource controller — AI-assisted workflow
feat(concepts): add enums ConceptDifficulty & ConceptStatus — AI-assisted
feat(ai): integrate Groq API via GroqService — AI-assisted architecture
feat(policies): implement DomainPolicy, ConceptPolicy, GenerationPolicy — AI-assisted
refactor(concepts): optimize query scopes for filtering — AI-assisted
docs(specs): add authentication.md spec file — AI-assisted
```

**Valid commit types:** `feat` · `fix` · `refactor` · `docs` · `style` · `test`

---

## 3. Security Rules

### 3.1 API Keys — Absolute Rule

```
❌ NEVER hardcode API keys, tokens, or secrets in any file.
❌ NEVER commit .env to Git — it must be in .gitignore.
✅ ALL sensitive values go in .env only.
✅ Access them in code via config() or env() helpers.
```

**Groq API key setup:**
```env
# .env
GROQ_API_KEY=your_key_here
GROQ_API_URL=https://api.groq.com/openai/v1/chat/completions
```

```php
// config/services.php
'groq' => [
    'key' => env('GROQ_API_KEY'),
    'url' => env('GROQ_API_URL'),
],
```

### 3.2 Authorization — Every Route Must Be Protected

- Every controller action that touches a Domain, Concept, or QuestionGeneration **must call `$this->authorize()`**.
- Users may only access **their own data** — never another user's domains, concepts, or generations.
- All web routes (except auth routes) must be wrapped in the `auth` middleware.

```php
// ✅ Correct — always authorize before acting
public function update(UpdateDomainRequest $request, Domain $domain)
{
    $this->authorize('update', $domain);
    $domain->update($request->validated());
    return redirect()->route('domains.index')->with('success', 'Domain updated.');
}
```

---

## 4. Architecture Rules

### 4.1 MVC — Thin Controllers

Controllers must stay **thin**. Their only responsibilities are:

1. Receive the request
2. Validate via Form Requests
3. Authorize via Policies
4. Call a Service or Model
5. Return a View or Redirect

```
❌ DO NOT put business logic, API calls, or heavy queries in controllers.
✅ Delegate complex logic to Services (e.g. GroqService) or Model scopes.
```

### 4.2 Service Layer — GroqService

All Groq API interaction must go through `app/Services/GroqService.php`.

**Responsibilities of GroqService:**
- Build the prompt from the Concept data
- Send the HTTP request using Laravel's native `Http::` facade
- Parse and validate the JSON response
- Handle API errors gracefully (throw exception or return error state)
- Return structured question data to the controller

```
❌ DO NOT use any external HTTP package (Guzzle directly, etc.).
✅ Use ONLY Laravel's native Http:: facade for all HTTP requests.
```

```php
// ✅ Correct usage
use Illuminate\Support\Facades\Http;

$response = Http::withHeaders([
    'Authorization' => 'Bearer ' . config('services.groq.key'),
])->post(config('services.groq.url'), [
    'model'    => 'llama3-8b-8192',
    'messages' => [['role' => 'user', 'content' => $prompt]],
]);
```

### 4.3 Policies — Required for All Resources

Three policies must be implemented and registered:

| Policy | Model | Protects |
|---|---|---|
| `DomainPolicy` | `Domain` | All domain routes |
| `ConceptPolicy` | `Concept` | All concept routes |
<!-- | `QuestionGenerationPolicy` | `QuestionGeneration` | All generation routes | -->

Each policy must verify that `$user->id === $model->user_id` (directly or through relationship) before allowing any action.

### 4.4 Form Requests — Required for All Mutations

Never validate inside the controller. Always use dedicated Form Request classes:

| Request | Used in |
|---|---|
| `StoreDomainRequest` | `DomainController@store` |
| `UpdateDomainRequest` | `DomainController@update` |
| `StoreConceptRequest` | `ConceptController@store` |
| `UpdateConceptRequest` | `ConceptController@update` |

### 4.5 Enums — Use PHP 8 Backed Enums

```php
// app/Enums/ConceptDifficulty.php
enum ConceptDifficulty: string
{
    case Junior = 'junior';
    case Mid    = 'mid';
    case Senior = 'senior';
}

// app/Enums/ConceptStatus.php
enum ConceptStatus: string
{
    case ToReview   = 'to_review';
    case InProgress = 'in_progress';
    case Mastered   = 'mastered';
}
```

---

## 5. Database Rules

### 5.1 Migrations — Always Use Artisan

```bash
php artisan make:model Domain           -m
php artisan make:model Concept          -m
# php artisan make:model QuestionGeneration -m
php artisan make:model Question         -m
```

### 5.2 Foreign Keys & Cascade Delete

All foreign key constraints must include `onDelete('cascade')`:

```php
$table->foreignId('user_id')->constrained()->onDelete('cascade');
$table->foreignId('domain_id')->constrained()->onDelete('cascade');
$table->foreignId('concept_id')->constrained()->onDelete('cascade');
// $table->foreignId('question_generation_id')->constrained('question_generations')->onDelete('cascade');
```

### 5.3 Mass Assignment Protection

Always define `$fillable` on every model. Never use `$guarded = []`.

---

## 6. AI Generation Rules

### 6.1 Persist Before Display

```
❌ NEVER display AI-generated questions directly from the API response.
✅ ALWAYS save QuestionGeneration + Questions to the database FIRST, then display from DB.
```

### 6.2 Graceful Error Handling

If the Groq API is unavailable or returns an error:

```
❌ DO NOT show a blank page.
❌ DO NOT show a raw stack trace.
✅ Catch the exception in GroqService or the controller.
✅ Redirect back with a clear flash error message.
```

```php
// ✅ Correct error handling pattern
try {
    $questions = $this->groqService->generate($concept);
} catch (\Exception $e) {
    return redirect()->back()->with('error', 'AI generation failed. Please try again.');
}
```

### 6.3 No External HTTP Packages

```
❌ DO NOT install Guzzle, Symfony HttpClient, or any HTTP package.
✅ USE Laravel Http:: facade exclusively.
```

---

## 7. Git & GitHub Workflow

### 7.1 Branch Strategy

```
main
 └── develop
      ├── feature/authentication
      ├── feature/domains
      ├── feature/concepts
      ├── feature/policies
      ├── feature/ai-generation
      ├── feature/ui-ux
      └── feature/presentation
```

**Rules:**
- `main` — production only, no direct commits
- `develop` — integration branch, all features merge here
- `feature/*` — one branch per feature, always created from `develop`

### 7.2 Branch Naming Convention

```bash
feature/authentication
feature/domains
feature/concepts
feature/policies
feature/ai-generation
feature/ui-ux
feature/presentation
```

### 7.3 Workflow Per Feature

```bash
# 1. Start from develop
git checkout develop
git pull origin develop

# 2. Create feature branch
git checkout -b feature/domains

# 3. Work and commit (with AI mention)
git add .
git commit -m "feat(domains): create Domain model & migration — AI-assisted"

# 4. Push and open Pull Request
git push origin feature/domains
# Open PR: feature/domains → develop

# 5. After merge, clean up
git checkout develop
git pull origin develop
git branch -d feature/domains
```

### 7.4 Pull Request Checklist

Before merging any PR into `develop`, verify:

- [ ] All routes protected by `auth` middleware
- [ ] All controller actions call `$this->authorize()`
- [ ] Validation done via Form Request classes
- [ ] No hardcoded API keys or secrets
- [ ] No business logic inside controllers
- [ ] AI generation results saved to DB before display
- [ ] Error handling returns a flash message, not a blank page
- [ ] Commit messages include AI mention

---

## 8. specs/ Folder Structure

Every feature must have a spec file written **before** implementation begins.

```
specs/
├── authentication.md
├── domains.md
├── concepts.md
├── ai-generation.md
├── policies.md
└── presentation.md
```

Each spec file must include:
- Feature goal
- Routes and controller methods
- Models involved
- Validation rules
- Authorization logic
- Edge cases and error states

---

## 9. Project Folder Structure Reference

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/
│   │   ├── DomainController.php
│   │   ├── ConceptController.php
│   │   └── QuestionGenerationController.php
│   ├── Requests/
│   │   ├── StoreDomainRequest.php
│   │   ├── UpdateDomainRequest.php
│   │   ├── StoreConceptRequest.php
│   │   └── UpdateConceptRequest.php
│   └── Middleware/
├── Models/
│   ├── User.php
│   ├── Domain.php
│   ├── Concept.php
│   ├── QuestionGeneration.php
│   └── Question.php
├── Policies/
│   ├── DomainPolicy.php
│   ├── ConceptPolicy.php
│   └── QuestionGenerationPolicy.php
├── Services/
│   └── GroqService.php
└── Enums/
    ├── ConceptDifficulty.php
    └── ConceptStatus.php
```

---

## 10. Summary Checklist

| Rule | Status |
|---|---|
| AGENTS.md created on Day 1, first commit | ✅ Required |
| specs/ file written before each feature | ✅ Required |
| Plan mode used before Build mode | ✅ Required |
| Every commit mentions AI assistance | ✅ Required |
| API keys in .env only, never committed | ✅ Required |
| Http:: facade only — no external HTTP packages | ✅ Required |
| All routes protected by auth middleware | ✅ Required |
| All resource routes use Policies | ✅ Required |
| All mutations use Form Request validation | ✅ Required |
| AI results saved to DB before display | ✅ Required |
| Graceful error handling — no blank pages | ✅ Required |
| Feature branches created from develop | ✅ Required |
| PRs target develop, not main | ✅ Required |

---

*InterviewPrep — Built with Laravel · Powered by Groq AI · Developed with AI-assisted workflow*