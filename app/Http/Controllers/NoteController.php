<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Services\ActivityService;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function store(Request $request)
    {
        if (auth()->user()->role === 'support_agent') {
            abort(403);
        }

        $request->validate([
            'note' => 'required|string|max:5000',
            'lead_id' => 'nullable|exists:leads,id',
            'customer_id' => 'nullable|exists:customers,id',
            'deal_id' => 'nullable|exists:deals,id',
        ]);

        if (!$request->lead_id && !$request->customer_id && !$request->deal_id) {
            return back()->withErrors([
                'note' => 'Note must be linked with lead, customer or deal.',
            ]);
        }

        $note = Note::create([
            'user_id' => auth()->id(),
            'lead_id' => $request->lead_id,
            'customer_id' => $request->customer_id,
            'deal_id' => $request->deal_id,
            'note' => $request->note,
        ]);

        ActivityService::log([
            'lead_id' => $request->lead_id,
            'customer_id' => $request->customer_id,
            'deal_id' => $request->deal_id,
            'type' => 'note_added',
            'description' => auth()->user()->name . ' added a note.',
        ]);

        return back()->with('success', 'Note added successfully.');
    }

    public function destroy(Note $note)
    {
        if (auth()->user()->role !== 'admin' && $note->user_id !== auth()->id()) {
            abort(403);
        }

        ActivityService::log([
            'lead_id' => $note->lead_id,
            'customer_id' => $note->customer_id,
            'deal_id' => $note->deal_id,
            'type' => 'note_deleted',
            'description' => auth()->user()->name . ' deleted a note.',
        ]);

        $note->delete();

        return back()->with('success', 'Note deleted successfully.');
    }
}