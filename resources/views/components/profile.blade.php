<x-layout :doctitle="$doctitle">
    {{-- when passing props 
    : = we want to pass php variable
    example :propsname ="$variable"
    wihtout : = passsing non variable
    example propsname = "{{$sharedData['username']}}'s followers" --}}


    <div class="container py-md-5 container--narrow">
        <h2>
          <img class="avatar-small" src="{{$sharedData['userImage']}}" /> {{$sharedData['username']}}
          @auth
          @if (!$sharedData['currentlyFollowing'] and auth()->user()->id!=$sharedData['userId'])
          <form class="ml-2 d-inline" action="/create-follow/{{$sharedData['username']}}" method="POST">
            @csrf
            <button class="btn btn-primary btn-sm">Follow<i class="fas fa-user-plus"></i></button>
          </form>
          @endif
         @if ($sharedData['currentlyFollowing'])
         <form class="ml-2 d-inline" action="/remove-follow/{{$sharedData['username']}}" method="POST">
          @csrf
          <button class="btn btn-danger btn-sm">Stop Following <i class="fas fa-user-times"></i></button>
        </form>
         @endif
          @if ($sharedData['userId']==auth()->user()->id)
          <a href="/manage-avatar" class="btn btn-secondary btn-sm">Edit avatar <i class="fas fa-user-plus"></i></a>
          @endif
          @endauth
         
          
        </h2>
        {{-- segment --}}
        {{-- for example: localhost:8000/view-profile/name/follower --}}
        {{-- if we want to check the follower we need to use segment 3 --}}
        <div class="profile-nav nav nav-tabs pt-2 mb-4">
          <a href="/profile/{{$sharedData['username']}}" class="profile-nav-link nav-item nav-link {{ Request::segment(3)==""?"active":""}}">Posts: {{$sharedData['postCounts']}}</a>
          <a href="/profile/{{$sharedData['username']}}/followers" class="profile-nav-link nav-item nav-link {{ Request::segment(3)=="followers"?"active":""}}">Followers: {{$sharedData['followersCount']}}</a>
          <a href="/profile/{{$sharedData['username']}}/following" class="profile-nav-link nav-item nav-link {{Request::segment(3)=="following"?"active":""}}">Following: {{$sharedData['followingCount']}}</a>
        </div>
        <div class="profile-slot-content">
            {{$slot}}
        </div>
       
      </div>
  
</x-layout>