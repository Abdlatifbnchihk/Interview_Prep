<?php

namespace App\Http\Controllers;

use App\Models\Concept;
use App\Models\QuestionGeneration;
use App\Services\GroqService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class QuestionGenerationController extends Controller
{
    public function __construct(private GroqService $groqService) {}

    public function index(Concept $concept): View
    {
        $this->authorize('view', $concept);
        $concept->load('generations.questions');
        return view('generations.index', compact('concept'));
    }

    public function store(Concept $concept): RedirectResponse
    {
        $this->authorize('view', $concept);
        $concept->load('domain');

        try {
            $questions = $this->groqService->generate($concept);

            $generation = $concept->generations()->create();
            foreach ($questions as $q) {
                $generation->questions()->create(['question' => $q]);
            }

            return redirect()
                ->route('generations.index', $concept)
                ->with('success', 'Questions generated successfully!');

        } catch (\Exception $e) {
            Log::error('Groq API Error: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'AI generation failed: ' . $e->getMessage());
        }
    }

    public function destroy(QuestionGeneration $generation): RedirectResponse
    {
        $this->authorize('delete', $generation);
        $generation->delete();
        return redirect()->back()->with('success', 'Generation deleted.');
    }
}