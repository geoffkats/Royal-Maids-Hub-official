<?php

namespace App\Http\Controllers;

use App\Models\WeeklyTaskBoard;
use App\Models\WeeklyTask;
use App\Models\Trainer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class WeeklyTaskBoardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        if ($user->role !== 'trainer') {
            abort(403, 'Access denied. Trainer role required.');
        }
        
        $trainer = $user->trainer;
        if (!$trainer) {
            abort(404, 'Trainer profile not found.');
        }
        
        // Determine target week's Monday (supports viewing historical weeks)
        $currentWeekStart = $request->has('week')
            ? Carbon::parse($request->get('week'))->startOfWeek(Carbon::MONDAY)
            : Carbon::now()->startOfWeek(Carbon::MONDAY);
        
        // Get or create this week's board
        $board = WeeklyTaskBoard::firstOrCreate(
            [
                'trainer_id' => $trainer->id,
                'start_of_week' => $currentWeekStart->toDateString(),
            ],
            [
                'status' => 'draft',
            ]
        );
        
        // Load tasks grouped by day
        $tasksByDay = $board->tasks()
            ->orderBy('day_of_week')
            ->orderBy('sort_order')
            ->get()
            ->groupBy('day_of_week');

        // Recent boards for quick navigation
        $recentBoards = WeeklyTaskBoard::where('trainer_id', $trainer->id)
            ->orderByDesc('start_of_week')
            ->limit(6)
            ->get();
        
        return view('trainer.weekly-board', compact('board', 'tasksByDay', 'currentWeekStart', 'recentBoards'));
    }
    
    public function storeTask(Request $request)
    {
        $user = Auth::user();
        
        if ($user->role !== 'trainer') {
            return response()->json(['error' => 'Access denied'], 403);
        }
        
        $trainer = $user->trainer;
        if (!$trainer) {
            return response()->json(['error' => 'Trainer profile not found'], 404);
        }
        
        $request->validate([
            'content' => 'required|string|max:500',
            'day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday',
            'week_start' => 'required|date',
        ]);
        
        // Get or create board for this week
        $board = WeeklyTaskBoard::firstOrCreate(
            [
                'trainer_id' => $trainer->id,
                'start_of_week' => $request->week_start,
            ],
            [
                'status' => 'draft',
            ]
        );

        if ($board->status !== 'draft') {
            return response()->json(['error' => 'Board is not editable'], 400);
        }
        
        // Get next sort order for this day
        $nextOrder = $board->tasks()
            ->where('day_of_week', $request->day_of_week)
            ->max('sort_order') + 1;
        
        $task = $board->tasks()->create([
            'day_of_week' => $request->day_of_week,
            'content' => $request->content,
            'is_complete' => false,
            'sort_order' => $nextOrder,
        ]);
        
        return response()->json([
            'success' => true,
            'task' => $task,
        ]);
    }
    
    public function updateTask(Request $request, $taskId)
    {
        $user = Auth::user();
        
        if ($user->role !== 'trainer') {
            return response()->json(['error' => 'Access denied'], 403);
        }
        
        $trainer = $user->trainer;
        if (!$trainer) {
            return response()->json(['error' => 'Trainer profile not found'], 404);
        }
        
        $task = WeeklyTask::whereHas('board', function($query) use ($trainer) {
            $query->where('trainer_id', $trainer->id);
        })->findOrFail($taskId);

        if ($task->board->status !== 'draft') {
            return response()->json(['error' => 'Board is not editable'], 400);
        }
        
        $request->validate([
            'content' => 'sometimes|string|max:500',
            'is_complete' => 'sometimes|boolean',
        ]);
        
        $task->update($request->only(['content', 'is_complete']));
        
        return response()->json([
            'success' => true,
            'task' => $task,
        ]);
    }
    
    public function destroyTask($taskId)
    {
        $user = Auth::user();
        
        if ($user->role !== 'trainer') {
            return response()->json(['error' => 'Access denied'], 403);
        }
        
        $trainer = $user->trainer;
        if (!$trainer) {
            return response()->json(['error' => 'Trainer profile not found'], 404);
        }
        
        $task = WeeklyTask::whereHas('board', function($query) use ($trainer) {
            $query->where('trainer_id', $trainer->id);
        })->findOrFail($taskId);

        if ($task->board->status !== 'draft') {
            return response()->json(['error' => 'Board is not editable'], 400);
        }
        
        $task->delete();
        
        return response()->json(['success' => true]);
    }

    public function startNextWeek(Request $request)
    {
        $user = Auth::user();
        
        if ($user->role !== 'trainer') {
            return response()->json(['error' => 'Access denied'], 403);
        }
        
        $trainer = $user->trainer;
        if (!$trainer) {
            return response()->json(['error' => 'Trainer profile not found'], 404);
        }

        $request->validate([
            'current_week_start' => 'required|date',
        ]);

        $currentWeekStart = Carbon::parse($request->current_week_start)->startOfWeek(Carbon::MONDAY);
        $nextWeekStart = (clone $currentWeekStart)->addWeek()->startOfWeek(Carbon::MONDAY);

        // Create next week's board or get existing
        $nextBoard = WeeklyTaskBoard::firstOrCreate([
            'trainer_id' => $trainer->id,
            'start_of_week' => $nextWeekStart->toDateString(),
        ], [
            'status' => 'draft',
        ]);

        // Carry over pending tasks from current board
        $currentBoard = WeeklyTaskBoard::where('trainer_id', $trainer->id)
            ->where('start_of_week', $currentWeekStart->toDateString())
            ->first();

        if ($currentBoard) {
            $pendingTasks = $currentBoard->tasks()->where('is_complete', false)->get();
            foreach ($pendingTasks as $pending) {
                $nextBoard->tasks()->firstOrCreate([
                    'day_of_week' => $pending->day_of_week,
                    'content' => $pending->content,
                ], [
                    'is_complete' => false,
                    'sort_order' => $pending->sort_order,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'next_week' => $nextWeekStart->toDateString(),
        ]);
    }
    
    public function submitForReview(Request $request)
    {
        $user = Auth::user();
        
        if ($user->role !== 'trainer') {
            return response()->json(['error' => 'Access denied'], 403);
        }
        
        $trainer = $user->trainer;
        if (!$trainer) {
            return response()->json(['error' => 'Trainer profile not found'], 404);
        }
        
        $request->validate([
            'week_start' => 'required|date',
        ]);
        
        $board = WeeklyTaskBoard::where('trainer_id', $trainer->id)
            ->where('start_of_week', $request->week_start)
            ->firstOrFail();
        
        if ($board->status !== 'draft') {
            return response()->json(['error' => 'Board already submitted'], 400);
        }
        
        $board->update([
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);
        
        return response()->json(['success' => true]);
    }

    // ADMIN: List submitted boards
    public function adminIndex()
    {
        $user = Auth::user();
        if ($user->role !== 'admin') {
            abort(403, 'Access denied. Admin role required.');
        }

        $boards = WeeklyTaskBoard::with(['trainer', 'trainer.user'])
            ->whereIn('status', ['submitted', 'reviewed'])
            ->orderByDesc('submitted_at')
            ->paginate(20);

        return view('admin.weekly-boards.index', compact('boards'));
    }

    // ADMIN: Show specific board
    public function adminShow($boardId)
    {
        $user = Auth::user();
        if ($user->role !== 'admin') {
            abort(403, 'Access denied. Admin role required.');
        }

        $board = WeeklyTaskBoard::with(['trainer', 'trainer.user', 'tasks' => function($q){
            $q->orderBy('day_of_week')->orderBy('sort_order');
        }])->findOrFail($boardId);

        $tasksByDay = $board->tasks->groupBy('day_of_week');

        return view('admin.weekly-boards.show', compact('board', 'tasksByDay'));
    }

    // ADMIN: Mark as reviewed
    public function adminMarkReviewed($boardId)
    {
        $user = Auth::user();
        if ($user->role !== 'admin') {
            return response()->json(['error' => 'Access denied'], 403);
        }

        $board = WeeklyTaskBoard::findOrFail($boardId);
        if ($board->status !== 'submitted') {
            return response()->json(['error' => 'Only submitted boards can be reviewed'], 400);
        }

        $board->update([
            'status' => 'reviewed',
            'reviewed_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }
}
