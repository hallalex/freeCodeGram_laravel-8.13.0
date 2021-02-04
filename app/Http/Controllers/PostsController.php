<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class PostsController extends Controller
{
  // Login required
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index()
  {
      $users = auth()->user()->following()->pluck('profiles.user_id');

      //$posts = Post::whereIn('user_id', $users)->latest()->get();
      $posts = Post::whereIn('user_id', $users)->with('user')->latest()->paginate(5);

      return view('posts.index', compact('posts'));

  }

  public function create()
  {
      return view('posts.create');
  }

  public function store()
  {
    $data = request()->validate([
      'caption' => ['required', 'string', 'max:255'],
      'image' => ['required', 'image'],
    ]);

    $imagePath = request('image')->store('uploads', 'public');

    // Fit image to 1200x1200 px
    $image = Image::make(public_path("storage/{$imagePath}"))->fit(1200, 1200);
    $image->save();

    auth()->user()->posts()->create([
        'caption' => $data['caption'],
        'image' => $imagePath,
      ]);

    return redirect('/profile/' . auth()->user()->id);

    // Troubleshooting
    //dd(request()->all());
    //dd(auth()->user());
  }

  public function show(Post $post)
  {
    // Not needed if (Post $post) is added above
    //$post = Post::find($post);

    // Troubleshooting
    //dd($post);

    return view('posts.show', compact('post'));
  }
}
