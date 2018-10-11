@extends('usuarios/base_profile')

@section('section')
    <div>
        @include('posts.form', ['tipo' => \App\Models\Post::$post_usuario_tipo])
        <div class="space-25"></div>
        <div class="timeline">
            @foreach ($usuario->feed->all() as $post)
                @include('posts.post_body')
                <div class="space-25"></div>
            @endforeach
        </div>
    </div>
@endsection
