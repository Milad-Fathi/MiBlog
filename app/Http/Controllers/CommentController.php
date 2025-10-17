<?php

namespace App\Http\Controllers;

use App\Models\blogs;
use App\Models\comments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function index($id)
    {
        try{
            $comments = comments::where('blog_id', $id)->orderBy('id', 'desc')->get();
            return view('comments.index', ['comments' => $comments]);
        } catch (\Exception $e){
            return redirect()->back()->with('error', 'An unexpected error occurred while fetching comments.');
        }
    }

    public function create($blog_id)
    {
        return view('comments.create', ['blog_id' => $blog_id]);
    }

    public function store(Request $request)
    {
        // validate
        $request->validate([
            'text' => 'required'
        ]);

        $blog = blogs::findOrFail(request('blog_id'));

        // store
        if ($blog){
            $comments = new comments();
            $comments->text = request('text');
            $comments->user_id = Auth::id();
            $comments->blog_id = request('blog_id');
            $comments->save();
        }

        return redirect()->back()->with('success', 'Data saved successfully');
    }
}
