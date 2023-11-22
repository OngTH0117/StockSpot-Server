<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BlogPostController extends Controller
{
    public function storeBlogPost(Request $request)
    {

        if (auth()->check()) {
            Log::info('User authenticated:', ['id' => auth()->id(), 'name' => auth()->user()->name]);
        } else {
            Log::info('User not authenticated');
        }
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        Log::info('Request Headers:', $request->headers->all());
        Log::info('Request Body:', $request->all());
        // Get the currently authenticated user
        $user = auth()->user();

        $blogPost = new BlogPost();
        $blogPost->user_id = $user->id;
        $blogPost->title = $validatedData['title'];
        $blogPost->content = $validatedData['content'];

        // Save the image 
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('public/blog_post_images');
            $imagePath = str_replace('public/', '', $imagePath);
            $blogPost->image = $imagePath;
        }

        $blogPost->save();

        return response()->json(['message' => 'Blog Post Created'], 200);
    }

    public function dashBlog()
    {
        
    $blogPosts = BlogPost::with('user')->get(); 
    return response()->json(['blogPosts' => $blogPosts]);
    }

    public function serveImage($filename)
{
    $imagePath = 'blog_post_images/' . $filename;

    if (Storage::disk('public')->exists($imagePath)) {
        return response()->file(storage_path('public/' . $imagePath));
    }

    abort(404);
}

public function getBlogPost($id)
{
    $blogPost = BlogPost::with('user')->find($id);

    if (!$blogPost) {
        return response()->json(['error' => 'Blog post not found'], 404);
    }

    return response()->json(['blogPost' => $blogPost], 200);
}

public function deleteBlogPost($id)
{
    $blogPost = BlogPost::find($id);

    if (!$blogPost) {
        return response()->json(['error' => 'Blog post not found'], 404);
    }

    if (auth()->id() !== $blogPost->user_id) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }
    $blogPost->delete();

    return response()->json(['message' => 'Blog post deleted successfully'], 200);
}
}
