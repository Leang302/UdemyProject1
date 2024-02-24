<div class="list-group">
    @foreach ($posts as $post)
    <x-post :post="$post" hideauthor/>
    @endforeach
  </a>
  </div>