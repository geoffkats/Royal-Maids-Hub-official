<x-layouts.app :title="__('Weekly Task Board')">
<div class="min-h-screen bg-gradient-to-br from-[#512B58] via-[#3B0A45] to-[#2D1B69]">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-white mb-2">Weekly Task Board</h1>
            <p class="text-gray-300">Week of {{ $currentWeekStart->format('M d, Y') }}</p>
            <div class="flex items-center justify-between mt-4">
                <div class="flex items-center space-x-4">
                    <span class="px-3 py-1 bg-blue-600 text-white rounded-full text-sm">
                        {{ ucfirst($board->status) }}
                    </span>
                    @if($board->submitted_at)
                        <span class="text-green-400 text-sm">
                            Submitted: {{ \Carbon\Carbon::parse($board->submitted_at)->format('M d, Y g:i A') }}
                        </span>
                    @endif
                </div>
                <div class="flex items-center space-x-3">
                    @if($board->status === 'draft')
                        <button 
                            id="submitBtn" 
                            class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition-colors"
                            onclick="submitForReview()"
                        >
                            Submit for Review
                        </button>
                    @endif
                    <button 
                        id="nextWeekBtn" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors"
                        onclick="startNextWeek()"
                    >
                        Start Next Week
                    </button>
                </div>
            </div>
        </div>

        <!-- Task Board -->
        <div class="bg-white rounded-lg shadow-xl overflow-hidden">
            <div class="grid grid-cols-5 gap-0 min-h-[600px]">
                @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday'] as $day)
                    <div class="border-r border-gray-200 last:border-r-0">
                        <!-- Day Header -->
                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                            <h3 class="font-semibold text-gray-800 capitalize">{{ $day }}</h3>
                            @if($board->status === 'draft')
                                <button 
                                    class="mt-2 text-blue-600 hover:text-blue-800 text-sm font-medium"
                                    onclick="addTask('{{ $day }}')"
                                >
                                    + Add Task
                                </button>
                            @endif
                        </div>
                        
                        <!-- Tasks for this day -->
                        <div class="p-4 space-y-3 min-h-[500px]" id="tasks-{{ $day }}">
                            @if(isset($tasksByDay[$day]))
                                @foreach($tasksByDay[$day] as $task)
                                    <div class="task-item bg-gray-50 rounded-lg p-3 border border-gray-200" data-task-id="{{ $task->id }}">
                                        <div class="flex items-start space-x-3">
                                            <input 
                                                type="checkbox" 
                                                class="mt-1 task-checkbox" 
                                                {{ $task->is_complete ? 'checked' : '' }}
                                                @if($board->status !== 'draft') disabled @else onchange="toggleTask({{ $task->id }}, this.checked)" @endif
                                            >
                                            <div class="flex-1">
                                                <input 
                                                    type="text" 
                                                    class="w-full bg-transparent border-none p-0 task-content {{ $task->is_complete ? 'line-through text-gray-500' : 'text-gray-900' }} placeholder-gray-400"
                                                    value="{{ $task->content }}"
                                                    @if($board->status !== 'draft') disabled @else onblur="updateTask({{ $task->id }}, this.value)" onkeypress="if(event.key==='Enter') this.blur()" @endif
                                                >
                                            </div>
                                            @if($board->status === 'draft')
                                                <button 
                                                    class="text-red-500 hover:text-red-700 text-sm"
                                                    onclick="deleteTask({{ $task->id }})"
                                                >
                                                    ×
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Weeks -->
        <div class="mt-8">
            <h2 class="text-xl font-semibold text-white mb-3">Recent Weeks</h2>
            <div class="bg-white rounded-lg shadow divide-y divide-gray-200">
                @foreach($recentBoards as $recent)
                    <a href="{{ route('weekly-board.index', ['week' => \Carbon\Carbon::parse($recent->start_of_week)->toDateString()]) }}" class="flex items-center justify-between px-4 py-3 hover:bg-gray-50">
                        <div class="text-gray-800">Week of {{ \Carbon\Carbon::parse($recent->start_of_week)->format('M d, Y') }}</div>
                        <div>
                            <span class="px-2 py-1 text-xs rounded-full {{ $recent->status === 'draft' ? 'bg-blue-100 text-blue-800' : ($recent->status === 'submitted' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                {{ ucfirst($recent->status) }}
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Add Task Modal -->
<div id="addTaskModal" class="fixed inset-0 bg-transparent hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-96">
        <h3 class="text-lg font-semibold mb-4">Add New Task</h3>
        <form id="addTaskForm">
            <input type="hidden" id="selectedDay" value="">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Task Description</label>
                <textarea 
                    id="taskContent" 
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white text-gray-900 placeholder-gray-400"
                    rows="3"
                    placeholder="Enter task description..."
                    required
                ></textarea>
            </div>
            <div class="flex justify-end space-x-3">
                <button 
                    type="button" 
                    class="px-4 py-2 text-gray-600 hover:text-gray-800"
                    onclick="closeAddTaskModal()"
                >
                    Cancel
                </button>
                <button 
                    type="submit" 
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                >
                    Add Task
                </button>
            </div>
        </form>
    </div>
</div>

<script>
const currentWeekStart = '{{ $currentWeekStart->toDateString() }}';

// Add Task Modal Functions
function addTask(day) {
    document.getElementById('selectedDay').value = day;
    document.getElementById('taskContent').value = '';
    document.getElementById('addTaskModal').classList.remove('hidden');
    document.getElementById('addTaskModal').classList.add('flex');
    document.getElementById('taskContent').focus();
}

function closeAddTaskModal() {
    document.getElementById('addTaskModal').classList.add('hidden');
    document.getElementById('addTaskModal').classList.remove('flex');
}

// Add Task Form Submission
document.getElementById('addTaskForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const day = document.getElementById('selectedDay').value;
    const content = document.getElementById('taskContent').value;
    
    if (!content.trim()) return;
    
    fetch('{{ route("weekly-board.tasks.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            day_of_week: day,
            content: content,
            week_start: currentWeekStart
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            addTaskToUI(day, data.task);
            closeAddTaskModal();
        } else {
            alert('Error adding task: ' + (data.error || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error adding task');
    });
});

// Add Task to UI
function addTaskToUI(day, task) {
    const tasksContainer = document.getElementById(`tasks-${day}`);
    const taskHtml = `
        <div class="task-item bg-gray-50 rounded-lg p-3 border border-gray-200" data-task-id="${task.id}">
            <div class="flex items-start space-x-3">
                <input 
                    type="checkbox" 
                    class="mt-1 task-checkbox" 
                    onchange="toggleTask(${task.id}, this.checked)"
                >
                <div class="flex-1">
                    <input 
                        type="text" 
                        class="w-full bg-transparent border-none p-0 task-content text-gray-900 placeholder-gray-400"
                        value="${task.content}"
                        onblur="updateTask(${task.id}, this.value)"
                        onkeypress="if(event.key==='Enter') this.blur()"
                    >
                </div>
                <button 
                    class="text-red-500 hover:text-red-700 text-sm"
                    onclick="deleteTask(${task.id})"
                >
                    ×
                </button>
            </div>
        </div>
    `;
    tasksContainer.insertAdjacentHTML('beforeend', taskHtml);
}

// Toggle Task Completion
function toggleTask(taskId, isComplete) {
    fetch(`/weekly-board/tasks/${taskId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            is_complete: isComplete
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const taskItem = document.querySelector(`[data-task-id="${taskId}"]`);
            const contentInput = taskItem.querySelector('.task-content');
            if (isComplete) {
                contentInput.classList.add('line-through', 'text-gray-500');
            } else {
                contentInput.classList.remove('line-through', 'text-gray-500');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating task');
    });
}

// Update Task Content
function updateTask(taskId, content) {
    fetch(`/weekly-board/tasks/${taskId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            content: content
        })
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            alert('Error updating task: ' + (data.error || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating task');
    });
}

// Delete Task
function deleteTask(taskId) {
    if (!confirm('Are you sure you want to delete this task?')) return;
    
    fetch(`/weekly-board/tasks/${taskId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.querySelector(`[data-task-id="${taskId}"]`).remove();
        } else {
            alert('Error deleting task: ' + (data.error || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error deleting task');
    });
}

// Submit for Review
function submitForReview() {
    if (!confirm('Are you sure you want to submit this week\'s tasks for review? This action cannot be undone.')) return;
    
    fetch('{{ route("weekly-board.submit") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            week_start: currentWeekStart
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error submitting tasks: ' + (data.error || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error submitting tasks');
    });
}

// Start Next Week (creates next Monday board and carries over pending tasks)
function startNextWeek() {
    if (!confirm('Start next week and carry over any pending tasks?')) return;
    fetch('{{ route("weekly-board.start-next-week") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            current_week_start: currentWeekStart
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Navigate to next week's board
            window.location.href = `{{ route('weekly-board.index') }}?week=${data.next_week}`;
        } else {
            alert('Error: ' + (data.error || 'Unknown error'));
        }
    })
    .catch(() => alert('Request failed'));
}
</script>
</x-layouts.app>
