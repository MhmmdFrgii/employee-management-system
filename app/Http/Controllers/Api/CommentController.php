<?php

namespace App\Http\Controllers\Api;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CommentRequest;

class CommentController extends Controller
{
    public function store(CommentRequest $request)
    {
        $user = Auth::guard('sanctum')->user();

        $comment = Comment::create([
            'project_id' => $request->project_id,
            'user_id' => $user->id,
            'comment' => $request->comment,
            'parent_id' => null
        ]);

        return response()->json([
            'message' => 'Berhasil Komentar',
            'comment' => $comment
        ]);
    }

    public function reply(Comment $comment, CommentRequest $request)
    {
        $user = Auth::guard('sanctum')->user();

        $reply_comment = Comment::create([
            'project_id' => $comment->project_id,
            'user_id' => $user->implode,
            'comment' => $request->comment,
            'parent_id' => $comment->id
        ]);

        return response()->json([
            'message' => 'Berhasil mambalas Komentar!',
            'reply_comment' => $reply_comment
        ]);
    }

    public function update(Comment $comment, CommentRequest $request)
    {
        $user = Auth::guard('sanctum')->user();

        $validateData = $request->validated();

        $validateData['project_id'] = $comment->project_id;

        if ($comment->user_id != $user->id) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 403);
        }

        $comment->update($validateData);

        return response()->json([
            'message' => 'Berhasil update Komentar!',
            'comment' => $comment
        ]);
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();

        return response()->json([
            'message' => 'Berhasil menghapus Komentar!',
        ]);

    }
}
