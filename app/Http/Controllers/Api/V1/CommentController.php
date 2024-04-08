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

    public function store(Request $request,Blog $blog) {

      $validated=$request->validate([
        "body"=>["min:4","required"]
      ]);

      $blog->attachComment( $validated);
     
      return  response()->json(['message' => 'Comment added successfully']);
      
    }

    public function delete(Blog $blog) {

      $blog->detachComment();
      return  response()->json(['message' => 'Comment deleted successfully']);
      
    }
}
