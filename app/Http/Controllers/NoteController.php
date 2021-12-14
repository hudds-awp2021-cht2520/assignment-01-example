<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Note;

use App\Http\Requests\NoteRequest;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notes = Auth::user()
            ->notes()
            ->paginate(20);
        
        return view('notes/index', [
            'notes' => $notes
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('notes/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\NoteRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NoteRequest $request)
    {
        $note = new Note();
        $note->content = $request->content;
        $note->user()->associate(Auth::user());
        $note->save();

        return redirect()
            ->route('notes.index')
            ->with('alerts', [[
                'level' => 'success',
                'message' => 'A note was created.'
            ]]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function show(Note $note)
    {
        if ($note->user->id !== Auth::user()->id) {
            abort(Response::HTTP_FORBIDDEN);
        }

        return view('notes.show', [
            'note' => $note
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function edit(Note $note)
    {
        if ($note->user->id !== Auth::user()->id) {
            abort(Response::HTTP_FORBIDDEN);
        }

        return view('notes.edit', [
            'note' => $note
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\NoteRequest  $request
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function update(NoteRequest $request, Note $note)
    {
        if ($note->user->id !== Auth::user()->id) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $note->content = $request->content;
        $note->save();

        return redirect()
            ->route('notes.index')
            ->with('alerts', [[
                'level' => 'success',
                'message' => 'The note was updated.'
            ]]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function destroy(Note $note)
    {
        if ($note->user->id !== Auth::user()->id) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $note->delete();

        return redirect()
            ->route('notes.index')
            ->with('alerts', [[
                'level' => 'success',
                'message' => 'The note was deleted.'
            ]]);
    }
}
