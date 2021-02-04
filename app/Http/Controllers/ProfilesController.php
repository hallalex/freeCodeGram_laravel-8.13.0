<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Facades\Image;

class ProfilesController extends Controller
{
    // 
    public function index(User $user)
    {
        // findOrFail för att inte ge en error när användaren saknas.
        //$user = User::findOrFail($user);

        $follows = (auth()->user()) ? auth()->user()->following->contains($user->id) : false;

        // Utan cache
        //$postCount = $user->posts->count();
        // $followersCount = $user->profile->followers->count();
        // $followingCount = $user->following->count();

        // Med 30 sekunder cache
        $postCount = Cache::remember(
          'count.posts.' . $user->id,
          now()->addSeconds(30),
          function() use ($user) {
            return $user->posts->count();
          }
        );

        $followersCount = Cache::remember(
          'count.followers.' . $user->id,
          now()->addSeconds(30),
          function() use ($user) {
            return $user->profile->followers->count();
          }
        );

        $followingCount = Cache::remember(
            'count.following.' . $user->id,
            now()->addSeconds(30),
            function () use ($user) {
                return $user->following->count();
            });

        return view('profiles/index', compact(
          'user',
          'follows',
          'postCount',
          'followersCount',
          'followingCount'
        ));
    }

    public function edit(User $user)
    {
      //return view ('profiles/edit', compact('user'));

      // Authorize with ProfilePolicy
      $this->authorize('update', $user->profile);

      return view('profiles/edit', [
        'user' => $user,
      ]);

      //dd($user);
    }

    public function update(User $user)
    {
      // Authorize with ProfilePolicy
      $this->authorize('update', $user->profile);

      $data = request()->validate([
        'title' => 'required',
        'description' => '',
        'url' => 'url',
        'image' => '',
      ]);

      // Om en ny bild laddats upp
      if (request('image')) {
        $imagePath = request('image')->store('profile', 'public');

        // Fit image to 1000x1000 px
        $image = Image::make(public_path("storage/{$imagePath}"))->fit(1000, 1000);
        $image->save();

        $data['image'] = $imagePath;

        //dd($data);
      }



      //dd(array_merge($data,  ['image' => $imagePath]  ));

      auth()->user()->profile->update($data);
      // Detta är ett säkerhetshål, funkar även om man inte är inloggad
      //$user->profile->update($data);

      return redirect("/profile/{$user->id}");
    }
}
