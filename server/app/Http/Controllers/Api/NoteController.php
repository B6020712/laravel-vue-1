<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Note;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        Log::info("User email: <{email}> request for all Notes", ["email" => $request->user()->email]);

        try {
            $all_notes = Note::all();
    
            return response()->json([
                "message" => "Success",
                "data" => $all_notes
            ], 200);
        } catch (\Exception $err) {
            Log::error("User email: <{email}> request for all Notes and error is <{error_msg}>", ["email" => $request->user()->email, "error_msg" => $err->getMessage()]);

            return response()->json([
                "message" => "Can't find any notes",
            ], 400);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string'],
            'content' => ['required', 'string']
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try {
            $note = Note::create([
                'user_id' => $request->user()->id,
                'title' => $request->title,
                'content' => $request->content,
                'categories' => $request->categories,
            ]);
        } catch (\Exception $err) {
            Log::error("Can't create new note for user email: <{email}>", ["email" => $request->user()->email]);
            Log::error("<{error_msg}>", ["error_msg" => $err->getMessage()]);

            return response()->json([
                "message" => "Can't create notes"
            ], 400);
        }

        if (! $note) {
            Log::error("Can't create new note for user email: <{email}>", ["email" => $request->user()->email]);
            return response()->json([
                "message" => "Can't create new notes"
            ], 400);
        }
        
        Log::info('User email: <{email}> is create new notes at '.Carbon::now()->format('Y-m-d H:i:s'), ['email' => $request->user()->email]);
        
        return response()->json([
            "message" => "New notes created",
            "notes" => $note
        ], 204);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Note $note): JsonResponse
    {
        Log::info("User email: <{email}> request for Note <{note_id}>", ["email" => $request->user()->email, "note_id" => $note->id]);

        try {
            return response()->json([
                "message" => "Success",
                "data" => $note
            ], 200);
        } catch (\Exception $err) {
            Log::error("User email: <{email}> request for all Notes and error is <{error_msg}>", ["email" => $request->user()->email, "error_msg" => $err->getMessage()]);

            return response()->json([
                "message" => "Can't find any notes",
            ], 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Note $note): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required', 'int'],
            'title' => ['required', 'string'],
            'content' => ['required', 'string']
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try {
            $update_note = Note::where('id', $request->id)->update([
                'id' => $request->id,
                'user_id' => $request->user()->id,
                'title' => $request->title,
                'content' => $request->content,
                'categories' => $request->categories,
            ]);
        } catch (\Exception $err) {
            Log::error("Can't update note id: <{update_note_id}> for user email: <{email}>", ["update_note_id" => $request->id, "email" => $request->user()->email]);
            Log::error("<{error_msg}>", ["error_msg" => $err->getMessage()]);

            return response()->json([
                "message" => "Can't update notes"
            ], 400);
        }

        if (! $update_note) {
            Log::error("Can't update note id: <{update_note_id}> for user email: <{email}>", ["update_note_id" => $request->id, "email" => $request->user()->email]);
            return response()->json([
                "message" => "Can't update notes"
            ], 400);
        }

        Log::info('User email: <{email}> is update note id: <{update_note_id}> at '.Carbon::now()->format('Y-m-d H:i:s'), ["update_note_id" => $request->id, 'email' => $request->user()->email]);
        
        return response()->json([
            "message" => "Note Updated",
            "notes" => $update_note
        ], 204);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Note $note)
    {
        Log::info("User email: <{email}> request for delete Note id: <{note_id}>", ["email" => $request->user()->email, "note_id" => $note->id]);

        try {
            $deleted_user = Note::where('id', $note->id)->delete();
            
            if (! $deleted_user) {
                return response()->json([
                    "message" => "Can't find notes",
                ], 400);
            }
            
            return response()->json([
                "message" => "Delete note successfully!",
            ], 200);
        } catch (\Exception $err) {
            Log::error("User email: <{email}> request for delete Note id: <{note_id}> and error is {error_msg}", ["email" => $request->user()->email, "error_msg" => $err->getMessage(), "note_id" => $note->id, "error_msg" => $err->getMessage()]);

            return response()->json([
                "message" => "Can't find notes",
            ], 400);
        }
    }
}
