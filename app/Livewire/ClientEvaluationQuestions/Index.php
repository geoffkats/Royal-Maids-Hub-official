<?php

namespace App\Livewire\ClientEvaluationQuestions;

use App\Models\ClientEvaluationQuestion;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    #[Url]
    public string $search = '';

    public string $question = '';
    public string $type = 'rating';
    public int $sort_order = 0;
    public bool $is_required = true;
    public bool $is_active = true;
    public ?int $editingId = null;

    protected function rules(): array
    {
        return [
            'question' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:rating,text,yes_no'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'is_required' => ['boolean'],
            'is_active' => ['boolean'],
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function edit(int $id): void
    {
        $question = ClientEvaluationQuestion::findOrFail($id);

        $this->editingId = $question->id;
        $this->question = $question->question;
        $this->type = $question->type;
        $this->sort_order = $question->sort_order;
        $this->is_required = $question->is_required;
        $this->is_active = $question->is_active;
    }

    public function cancelEdit(): void
    {
        $this->reset(['editingId', 'question', 'type', 'sort_order', 'is_required', 'is_active']);
        $this->type = 'rating';
        $this->sort_order = 0;
        $this->is_required = true;
        $this->is_active = true;
    }

    public function save(): void
    {
        $this->authorize('viewAny', ClientEvaluationQuestion::class);

        $data = $this->validate();

        if ($this->editingId) {
            $question = ClientEvaluationQuestion::findOrFail($this->editingId);
            $question->update($data);
            session()->flash('success', __('Question updated successfully.'));
        } else {
            ClientEvaluationQuestion::create($data);
            session()->flash('success', __('Question added successfully.'));
        }

        $this->cancelEdit();
    }

    public function delete(int $id): void
    {
        $this->authorize('viewAny', ClientEvaluationQuestion::class);

        ClientEvaluationQuestion::findOrFail($id)->delete();

        session()->flash('success', __('Question deleted successfully.'));
    }

    public function render()
    {
        $this->authorize('viewAny', ClientEvaluationQuestion::class);

        $term = trim($this->search);
        $questions = ClientEvaluationQuestion::query()
            ->when($term !== '', function ($query) use ($term) {
                $like = '%' . str_replace(['%', '_'], ['\\%', '\\_'], $term) . '%';
                $query->where('question', 'like', $like);
            })
            ->orderBy('sort_order')
            ->orderBy('id')
            ->paginate(15);

        return view('livewire.client-evaluation-questions.index', [
            'questions' => $questions,
        ])->layout('components.layouts.app', ['title' => __('Client Evaluation Questions')]);
    }
}
