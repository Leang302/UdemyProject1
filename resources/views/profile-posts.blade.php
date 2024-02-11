<x-layout>
    <div class="container py-md-5 container--narrow">
        <h2>
          <img class="avatar-small" src="{{$userImage}}" /> {{$username}}
          <form class="ml-2 d-inline" action="#" method="POST">
            @csrf
            <button class="btn btn-primary btn-sm">Follow <i class="fas fa-user-plus"></i></button>
            <!-- <button class="btn btn-danger btn-sm">Stop Following <i class="fas fa-user-times"></i></button> -->
          </form>
          @if ($userId==auth()->user()->id)

          <a href="/manage-avatar" class="btn btn-secondary btn-sm">Edit avatar <i class="fas fa-user-plus"></i></a>
          @endif
          
        </h2>
  
        <div class="profile-nav nav nav-tabs pt-2 mb-4">
          <a href="#" class="profile-nav-link nav-item nav-link active">Posts: {{$postCounts}}</a>
          <a href="#" class="profile-nav-link nav-item nav-link">Followers: 3</a>
          <a href="#" class="profile-nav-link nav-item nav-link">Following: 2</a>
        </div>
  
        <div class="list-group">
            @foreach ($posts as $post)
            <a href="/post/{{$post->id}}" class="list-group-item list-group-item-action">
                <img class="avatar-tiny" src="{{$post->user->avatar}}" />
                <strong>{{$post->title}}</strong> {{$post->created_at->format('d/F/Y')}}
              </a>
            @endforeach
          </a>
        </div>
      </div>
  
</x-layout>