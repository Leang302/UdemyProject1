<x-profile :sharedData="$sharedData" doctitle="{{$sharedData['username']}}'s Posts">
  @include('profile-posts-only')
</x-profile>