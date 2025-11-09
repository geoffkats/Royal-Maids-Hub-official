<x-layouts.app :title="__('Weekly Board Review')">
<div class="min-h-screen bg-gradient-to-br from-[#512B58] via-[#3B0A45] to-[#2D1B69]">
    <div class="container mx-auto px-4 py-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-white">Weekly Board Review</h1>
                <p class="text-gray-300 mt-1">
                    Trainer: <span class="font-semibold">{{ optional($board->trainer?->user)->name ?? '—' }}</span> · Week of {{ \Carbon\Carbon::parse($board->start_of_week)->format('M d, Y') }}
                </p>
            </div>
            <div class="space-x-3">
                <a href="{{ route('admin.weekly-boards.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">Back</a>
                @if($board->status === 'submitted')
                <button id="reviewBtn" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg" onclick="markReviewed()">
                    Mark as Reviewed
                </button>
                @else
                <span class="px-3 py-1 bg-green-700 text-white rounded-lg text-sm">Reviewed on {{ $board->reviewed_at ? \Carbon\Carbon::parse($board->reviewed_at)->format('M d, Y g:i A') : '' }}</span>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="grid grid-cols-5 gap-0">
                @foreach(['monday','tuesday','wednesday','thursday','friday'] as $day)
                <div class="border-r border-gray-200 last:border-r-0">
                    <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                        <h3 class="font-semibold text-gray-800 capitalize">{{ $day }}</h3>
                    </div>
                    <div class="p-4 space-y-3 min-h-[400px]">
                        @forelse(($tasksByDay[$day] ?? collect()) as $task)
                            <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                                <div class="flex items-start space-x-3">
                                    <input type="checkbox" disabled {{ $task->is_complete ? 'checked' : '' }} class="mt-1">
                                    <div class="flex-1">
                                        <div class="text-sm {{ $task->is_complete ? 'line-through text-gray-500' : 'text-gray-800' }}">{{ $task->content }}</div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-sm text-gray-400">No tasks.</div>
                        @endforelse
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
function markReviewed() {
    if (!confirm('Mark this weekly board as reviewed?')) return;

    fetch('{{ route('admin.weekly-boards.review', $board->id) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + (data.error || 'Unknown error'));
        }
    })
    .catch(() => alert('Request failed'));
}
</script>
</x-layouts.app>
