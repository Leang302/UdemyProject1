<x-layout>
    <div class="container py-md-5 container--narrow">
        <h2 class="text-center mb-4">The Latest From Those You Follow</h2>
        @unless ($feedposts->isempty())
        @foreach ($feedposts as $feedpost)
    <a href="/post/{{$feedpost->id}}" class="list-group-item list-group-item-action">
        <img class="avatar-tiny" src="{{$feedpost->user->avatar}}" />
        <strong>{{$feedpost->title}}</strong> {{$feedpost->created_at->format('d/F/Y')}}
      </a>
    @endforeach
    <div class="mt-4 ">
      {{$feedposts->links()}}  
    </div>
    {{-- for pagination --}}
      
        @else
        <p>Your feed is empty</p>
        @endunless
      </div>
</x-layout>