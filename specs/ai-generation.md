# specs/ai-generation.md

> **Feature:** AI Question Generation
> **Epic:** IP — Epic 4
> **Branch:** `feature/ai-generation`
> **Tasks:** IP-18, IP-19, IP-20, IP-21, IP-22, IP-23, IP-24
> **Status:** To Do
> **AI Mode:** Plan → Build

---

## 1. Feature Goal

Allow authenticated users to generate **AI-powered interview questions** for any of their concepts using the **Groq API**.
Generated questions must be persisted to the database before being displayed.
Users can view their generation history per concept and delete old generations.
All API errors must be handled gracefully — no blank pages, no raw stack traces.

---

## 2. User Stories

| ID | As a... | I want to... | So that... |
|---|---|---|---|
| US-18 | User | Generate interview questions for a concept | I can practice answering them |
| US-19 | User | See my generation history for a concept | I can review past sets of questions |
| US-20 | User | Delete a generation | I can remove irrelevant question sets |
| US-21 | User | See a clear error if AI generation fails | I am not left with a blank page |
| US-22 | User | Only see my own generations | Other users' data stays private |

---

## 3. Routes

```php
// routes/web.php — inside auth middleware group
Route::post('concepts/{concept}/generations', [QuestionGenerationController::class, 'store'])
    ->name('generations.store');

Route::get('concepts/{concept}/generations', [QuestionGenerationController::class, 'index'])
    ->name('generations.index');

Route::delete('generations/{generation}', [QuestionGenerationController::class, 'destroy'])
    ->name('generations.destroy');
```

| Method | URI | Action | Name |
|---|---|---|---|
| POST | `/concepts/{concept}/generations` | `store` | `generations.store` |
| GET | `/concepts/{concept}/generations` | `index` | `generations.index` |
| DELETE | `/generations/{generation}` | `destroy` | `generations.destroy` |

---

## 4. Controller — QuestionGenerationController.php

```php
class QuestionGenerationController extends Controller
{
    public function __construct(private GroqService $groqService) {}

    public function index(Concept $concept)
    // Authorize: 'view', $concept (via ConceptPolicy — concept belongs to user)
    // Load generations with their questions: $concept->load('generations.questions')
    // Return generations/index.blade.php

    public function store(Concept $concept)
    // Authorize: 'create', QuestionGeneration::class (or check concept ownership)
    // Call: $this->groqService->generate($concept)
    // On success: save QuestionGeneration + Questions to DB
    // On failure: redirect back with error flash — never show blank page
    // Redirect: generations.index for this concept with success flash

    public function destroy(QuestionGeneration $generation)
    // Authorize: 'delete', $generation (via QuestionGenerationPolicy)
    // Delete generation (cascades to questions)
    // Redirect: back with success flash
}
```

---

## 5. Service — app/Services/GroqService.php

This is the **only place** where Groq API communication happens.

```php
class GroqService
{
    private string $apiKey;
    private string $apiUrl;
    private string $model = 'llama3-8b-8192';

    public function __construct()
    {
        $this->apiKey = config('services.groq.key');
        $this->apiUrl = config('services.groq.url');
    }

    /**
     * Generate interview questions for a concept.
     * Returns an array of question strings.
     * Throws \Exception on API failure.
     */
    public function generate(Concept $concept): array
    {
        $prompt = $this->buildPrompt($concept);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type'  => 'application/json',
        ])->post($this->apiUrl, [
            'model'    => $this->model,
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
            'max_tokens'  => 1000,
            'temperature' => 0.7,
        ]);

        if ($response->failed()) {
            throw new \Exception('Groq API request failed: ' . $response->status());
        }

        return $this->parseResponse($response->json());
    }

    private function buildPrompt(Concept $concept): string
    {
        return <<<PROMPT
You are a senior technical interviewer.
Generate exactly 5 interview questions for the following concept.
Return ONLY a JSON array of strings, no explanation, no markdown.

Concept: {$concept->title}
Domain: {$concept->domain->name}
Difficulty: {$concept->difficulty->value}
Explanation: {$concept->explanation}

Example output: ["Question 1?", "Question 2?", "Question 3?", "Question 4?", "Question 5?"]
PROMPT;
    }

    private function parseResponse(array $json): array
    {
        $content = $json['choices'][0]['message']['content'] ?? null;

        if (!$content) {
            throw new \Exception('Empty response from Groq API.');
        }

        $questions = json_decode(trim($content), true);

        if (!is_array($questions) || empty($questions)) {
            throw new \Exception('Could not parse questions from Groq API response.');
        }

        return $questions;
    }
}
```

---

## 6. Models

### QuestionGeneration.php
```php
protected $fillable = ['concept_id'];

public function concept(): BelongsTo
{
    return $this->belongsTo(Concept::class);
}

public function questions(): HasMany
{
    return $this->hasMany(Question::class);
}
```

### Question.php
```php
protected $fillable = ['question_generation_id', 'question'];

public function generation(): BelongsTo
{
    return $this->belongsTo(QuestionGeneration::class);
}
```

---

## 7. Migrations

### create_question_generations_table
```php
Schema::create('question_generations', function (Blueprint $table) {
    $table->id();
    $table->foreignId('concept_id')->constrained()->onDelete('cascade');
    $table->timestamps();
});
```

### create_questions_table
```php
Schema::create('questions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('question_generation_id')
          ->constrained('question_generations')
          ->onDelete('cascade');
    $table->text('question');
    $table->timestamps();
});
```

---

## 8. Policy — QuestionGenerationPolicy.php

```php
public function view(User $user, QuestionGeneration $generation): bool
{
    return $user->id === $generation->concept->domain->user_id;
}

public function create(User $user): bool
{
    return true; // concept ownership checked in controller
}

public function delete(User $user, QuestionGeneration $generation): bool
{
    return $user->id === $generation->concept->domain->user_id;
}
```

---

## 9. Persist Before Display — Controller Store Logic

```php
public function store(Concept $concept)
{
    $this->authorize('view', $concept);

    try {
        // 1. Call AI service
        $questions = $this->groqService->generate($concept);

        // 2. Persist to DB BEFORE rendering
        $generation = $concept->generations()->create();
        foreach ($questions as $q) {
            $generation->questions()->create(['question' => $q]);
        }

        // 3. Redirect to history
        return redirect()
            ->route('generations.index', $concept)
            ->with('success', 'Questions generated successfully!');

    } catch (\Exception $e) {
        // 4. Graceful error — never blank page
        return redirect()
            ->back()
            ->with('error', 'AI generation failed. Please try again later.');
    }
}
```

---

## 10. Environment Configuration

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

> ❌ Never hardcode the API key. ❌ Never commit `.env` to Git.

---

## 11. Views

```
resources/views/generations/
├── index.blade.php     — list of all generations for a concept, newest first
```

**Generation history card UI elements:**
- Generation date (`created_at`)
- List of questions (numbered)
- Delete generation button (with confirmation)
- "Generate new questions" button → POST to `generations.store`

---

## 12. Edge Cases & Error States

| Scenario | Expected Behavior |
|---|---|
| Groq API is down / unreachable | Catch exception → redirect back with error flash message |
| Groq API returns malformed JSON | Catch parse exception → redirect back with error flash |
| Groq API returns empty content | Throw exception in `parseResponse()` → caught in controller |
| User deletes another user's generation | 403 Forbidden via QuestionGenerationPolicy |
| User generates for another user's concept | 403 Forbidden — `$this->authorize('view', $concept)` fails |
| API key missing from .env | `config('services.groq.key')` returns null → HTTP 401 → caught and flashed |
| No generations yet for concept | Empty state: "No questions generated yet. Click 'Generate' to start." |

---

## 13. Laravel Commands

```bash
php artisan make:model QuestionGeneration -m
php artisan make:model Question -m
php artisan make:controller QuestionGenerationController
php artisan make:policy QuestionGenerationPolicy --model=QuestionGeneration
mkdir app/Services
# manually create app/Services/GroqService.php
```

---

## 14. Commits for This Feature

```bash
feat(ai): create QuestionGeneration and Question models with migrations — AI-assisted
feat(ai): build GroqService with Http facade, prompt builder, JSON parser — AI-assisted architecture
feat(ai): implement QuestionGenerationController store with persist-before-display — AI-assisted
feat(ai): add graceful error handling for Groq API failures — AI-assisted
feat(ai): implement QuestionGenerationPolicy with ownership check — AI-assisted
feat(ai): build generation history view with delete action — AI-assisted
```