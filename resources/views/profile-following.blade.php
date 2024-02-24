<x-profile :sharedData="$sharedData" doctitle="{{$sharedData['username']}}'s followings">
    @include('profile-following-only')
  </x-profile>