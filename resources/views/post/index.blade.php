@extends('layouts.app')

@section('content')

<h3>Forum Kelas</h3>

<div class="card p-4 mb-4">
    <h5>Buat Postingan Baru</h5>
    <form method="POST" action="/post" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <textarea name="content" class="form-control" rows="4" placeholder="Tulis tugas / kegiatan..." required></textarea>
        </div>
        <div class="mb-3">
            <input type="file" name="image" class="form-control" accept="image/*">
        </div>
        <button class="btn btn-primary">Post</button>
    </form>
</div>

<hr>

<!-- FEED -->
@foreach($posts as $p)
<div class="card mb-3 p-3">

    <div class="d-flex align-items-center mb-2">
        <div>
            <b>{{ $p->user->name }}</b>
            <br>
            <small class="text-muted">{{ $p->created_at->diffForHumans() }}</small>
        </div>
    </div>

    <p>{{ $p->content }}</p>

    @if($p->image)
    <img src="/storage/{{ $p->image }}" style="max-width: 100%; border-radius: 10px; margin-bottom: 15px;">
    @endif

    <div class="mb-3">
        <button onclick="like({{ $p->id }})" class="btn btn-sm btn-outline-danger">
            ❤️ {{ $p->likes->count() }}
        </button>
    </div>

    <!-- KOMENTAR SECTION -->
    <div class="bg-light p-3 rounded">
        <h6>Komentar</h6>

        @foreach($p->comments as $c)
        <div class="mb-2 pb-2 border-bottom">
            <b>{{ $c->user->name }}</b>
            <p class="mb-0">{{ $c->comment }}</p>
            <small class="text-muted">{{ $c->created_at->diffForHumans() }}</small>
        </div>
        @endforeach

        <div class="mt-3">
            <input type="text" id="c{{ $p->id }}" placeholder="Tulis komentar..." class="form-control mb-2">
            <button onclick="comment({{ $p->id }})" class="btn btn-sm btn-primary">Kirim</button>
        </div>
    </div>

</div>
@endforeach

<script>
function like(id){
 fetch('/like/'+id,{
    method:'POST',
    headers:{
      'X-CSRF-TOKEN':'{{ csrf_token() }}'
    }
 }).then(()=>location.reload());
}

function comment(id){
 let val = document.getElementById('c'+id).value;

 if(!val.trim()) {
    alert('Tulis komentar terlebih dahulu');
    return;
 }

 fetch('/comment',{
  method:'POST',
  headers:{
    'Content-Type':'application/json',
    'X-CSRF-TOKEN':'{{ csrf_token() }}'
  },
  body:JSON.stringify({post_id:id,comment:val})
 }).then(()=>location.reload());
}
</script>

@endsection
