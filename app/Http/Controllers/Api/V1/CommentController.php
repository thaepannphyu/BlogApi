<?php


namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Comment\CommentCollection;
use App\Models\Blog;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function index(Blog $blog) {
      return  $blog->comments;
    }

    public function count(Blog $blog) {
      return  $blog->comments->count();
      
    }


    public function store(Request $request, Blog $blog) {

      $validated = $request->validate([
          "body" => ["min:4", "required"]
      ]);
  
      $validated["user_id"]=Auth::user()->id;
      $validated["blog_id"]=$blog->id;
      try {
        Comment::create($validated);
          return response()->json(['message' => 'Comment added successfully']);
      } catch (\Exception $e) {
          return response()->json(['error' => 'Failed to add comment', 'details' => $e->getMessage()], 500);
      }

  }
  
    public function destory(Blog $blog,Comment $comment) {
    
      $comment->delete();   
      return  response()->json(['message' => 'Comment deleted successfully',"comment"=>$comment]);
      
    }
}
