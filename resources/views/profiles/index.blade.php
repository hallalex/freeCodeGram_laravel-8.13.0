

@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-3 p-5">
      <img src="{{ $user->profile->profileImage() }}" class="rounded-circle w-100">
    </div>
    <div class="col-9">
      <div class="d-flex justify-content-between align-items-baseline">
        <div class="d-flex align-items-center pb-3">
          <div class="h4">{{ $user->username }}</div>

        <follow-button user-id="{{ $user->id }}" follows="{{ $follows }}"></follow-button>
      </div>

        @can('update', $user->profile)
        <a href="/p/create">Add new post</a>
        <a href="/profile/{{ $user->id }}/edit">Edit profile</a>
        @endcan
      </div>
      <div class="d-flex">
        <div class="pr-5">
          {{ $postCount }} posts
        </div>
        <div class="pr-5">
          {{ $followersCount }} followers
        </div>
        <div class="pr-5">
          {{ $followingCount }} following
        </div>
      </div>
      <div class="pt-4 font-weight-bold">
        {{ $user->profile->title }}
      </div>
      <div class="">
        {{ $user->profile->description ?? 'No description'}}
      </div>
      <div class="">
        <a href="{{ $user->profile->url ?? '#' }}">{{ $user->profile->url ?? 'No URL' }}</a>
      </div>
    </div>
  </div>

  <div class="row pt-5">
    @foreach($user-> posts as $post)

      <div class="col-4 pb-4">
        <a href="/p/{{ $post->id }}">
          <img src="/storage/{{ $post->image }}" class="w-100">
        </a>
      </div>

    @endforeach
  </div>

@endsection
