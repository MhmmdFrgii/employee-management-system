<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\EmployeeDetail;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request as FacadesRequest;

class CommentController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(CommentRequest $request)
    {
        // Mengambil ID user yang sedang login
        $userId = Auth::id();


        Comment::create([
            'project_id' => $request->project_id,
            'user_id' => $userId,
            'comment' => $request->comment,
            'parent_id' => null
        ]);
        return redirect()->back()->with('success', 'Komentar berhasil ditambahkan.');
    }

    public function reply(Comment $comment, Request $request)
    {
        $userId = Auth::id();

        Comment::create([
            'project_id' => $comment->project_id,
            'user_id' => $userId,
            'comment' => $request->comment,
            'parent_id' => $comment->id
        ]);

        return back();
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(CommentRequest $request, Comment $comment)
    {
        $user = Auth::user();
        $validateData = $request->validated();

        $validateData['project_id'] = $comment->project_id;

        if ($comment->user_id != $user->id) {
            return redirect()->back()->with('success', ' Gagal Update Komentar!');
        }

        $comment->update($validateData);
        return redirect()->back()->with('success', 'Update komentar berhasil!');
    }


   
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();
        return redirect()->back()->with('success', 'Berhasil Hapus Komentar!');
    }
}
