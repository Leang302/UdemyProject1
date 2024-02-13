<x-profile :sharedData="$sharedData" doctitle="{{$sharedData['username']}}'s Posts">
  <div class="list-group">
    @foreach ($posts as $post)
    <x-post :post="$post" hideauthor/>
    @endforeach
  </a>
  </div>
</x-profile>