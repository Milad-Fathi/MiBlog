<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use App\Models\blogs;
use App\Models\comments;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class BlogController extends Controller
{
    public function index()
    {
        try{
            $blogs = blogs::where('status', 'confirm')->orderBy('id', 'desc')->get();

            foreach ($blogs as $blog) {
                $blog->text = substr($blog->text, 0, 900);
            }
    
            return view('blogs.index', ['blogs' => $blogs]);
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An unexpected error occurred while fetching blogs.');
        }
    }

    public function show($id)
    {
        try {
            $blog = blogs::findOrFail($id);
            $comments = comments::where('blog_id', $id)->where('status', 'confirm')->with('user')->get();

            // generating summary
            if(is_null($blog->summary)){
                $text = $blog->text;
                $pythonPath = base_path('venv/Scripts/python.exe');

                $tempJson = tempnam(sys_get_temp_dir(), 'summarize');
                file_put_contents($tempJson, json_encode(['text' => $text]));
                
                $process = new Process([
                    $pythonPath,
                    base_path('app/Services/summarize.py'),
                    $tempJson
                ]);

                $process->setWorkingDirectory(base_path('app'));
                
                $process->setEnv([
                    'SYSTEMROOT' => 'C:\Windows',
                    'PYTHONUNBUFFERED' => '1',
                    'PYTHONIOENCODING' => 'utf-8'
                ]);

                $process->setTimeout(300);
                $process->run();
                
                if (!$process->isSuccessful()) {
                    throw new ProcessFailedException($process);
                }
                
                $result = json_decode(file_get_contents($tempJson . '.result'), true);
                $summary_text = $result['summary'];

                $blog->summary = $summary_text;
                $blog->save();

                unlink($tempJson);
                unlink($tempJson . '.result');

            }else{
                $summary_text = $blog->summary;
            }

            return view('blogs.show', ['blog' => $blog, 'comments' => $comments, 'summary_text' => $summary_text]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An unexpected error occurred while fetching blog details.');
        }
    }

    public function create()
    {
        return view('blogs.create');
    }

    public function store(Request $request)
    {
        // validate 
        $request->validate([
            'title' => 'bail|required|unique:blogs|max:255',
            'text' => 'required',
            'cover_image' => 'image|nullable|max:1999' // 1999 KB
        ]);

        $coverImagePath = $request->file('cover_image') ? $request->file('cover_image')->store('cover_images', 'public') : null;

        // store
        $blog = new blogs();
        $blog->title = request('title');
        $blog->text = request('text');
        $blog->user_id = Auth::id();
        $blog->tag1 = request('tag1');
        $blog->tag2 = request('tag2');
        $blog->tag3 = request('tag3');
        $blog->cover_image = $coverImagePath; 
        $blog->save();

        return redirect('/')->with('success', 'Blog created successfully');
    }

    public function search(Request $request)
    {
        try{
            $searchTerm = $request->input('q');
            $results = blogs::where('title', 'like', "%$searchTerm%")->where('status', 'confirm')->get();

            foreach ($results as $blog) {
                $blog->text = substr($blog->text, 0, 900);
            }

            return view('blogs.search', ['blogs' => $results]);
        
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An unexpected error occurred while searching for blogs.');
        }
    }

    public function showWriterBlogs()
    {
        $user = auth()->user();

        // Fetch all blogs for the authenticated writer
        $blogs = blogs::where('user_id', $user->id)->orderBy('id', 'desc')->get(); 

        return view('blogs.writer', compact('blogs'));
    }

    public function recommendations(Request $request)
    {
        $user = $request->user(); 

        // Step 1: Check user role and fetch comments or blogs
        if ($user->role === 'user') {
            // Fetch all comments for the user
            
            $comments = comments::where('user_id', $user->id)->where('status', 'confirm')->get();
            $combinedText = $comments->pluck('text')->implode(' '); // Combine all comments into one string
            
        } elseif ($user->role === 'writer') {
            // Fetch all blogs for the writer
            $blogs = blogs::where('user_id', $user->id)->where('status', 'confirm')->get();
            $combinedText = $blogs->pluck('text')->implode(' '); // Combine all blog texts into one string

        } else {
            return response()->json(['error' => 'User role not recognized.'], 400);
        }

        // Step 2: Prepare the JSON structure
        $jsonData = [
            'user' => $combinedText,
        ];

        // Step 3: Fetch all blogs that are not written by the current user
        $otherBlogs = blogs::where('user_id', '!=', $user->id)->where('status', 'confirm')->get();
        foreach ($otherBlogs as $blog) {
            $jsonData[$blog->id] = $blog->text; // Add blog ID and text to the JSON
        }

        // Log the JSON data before writing it to the file
        Log::info('JSON Data to be sent to Python: ' . json_encode($jsonData));

        // Step 4: Set up the Python script execution
        $pythonPath = base_path('venv/Scripts/python.exe');
        $scriptPath = base_path('app/Services/recommendation.py');

        // Create a temporary JSON file
        $tempJson = tempnam(sys_get_temp_dir(), 'recommendation_');
        file_put_contents($tempJson, json_encode($jsonData));

        // Create a new process for running the Python script
        $process = new Process([
            $pythonPath,
            $scriptPath,
            $tempJson
        ]);

        // Set the working directory 
        $process->setWorkingDirectory(base_path('app'));

        $process->setEnv([
            'SYSTEMROOT' => 'C:\Windows',
            'PYTHONUNBUFFERED' => '1',
            'PYTHONIOENCODING' => 'utf-8'
        ]);

        $process->setTimeout(300);

        // Run the process
        $process->run();

        // Check if the process was successful
        if (!$process->isSuccessful()) {
            Log::error('Process failed: ' . $process->getErrorOutput());
            throw new ProcessFailedException($process);
        }

        $outputFile = $tempJson . '.result';
        $result = json_decode(file_get_contents($outputFile), true);

        Log::info('result: ' . json_encode($result));

        // Clean up temporary files
        unlink($tempJson);
        unlink($outputFile);

        // Handle the case where no recommendations are returned
        if (empty($result)) {
            return view('blogs.recommendations', ['blogs' => collect()]); // No blogs found
        }

        // Step 5: Fetch the recommended blogs from the database
        $recommendedIds = $result;
        $recommendedBlogs = blogs::whereIn('id' , $recommendedIds)
        ->orderByRaw('FIELD(id, ' . implode(',', $recommendedIds) . ')')->get();

        // Step 6: Show the blogs in a Blade view
        return view('blogs.recommendations', ['blogs' => $recommendedBlogs]);
    }
}
