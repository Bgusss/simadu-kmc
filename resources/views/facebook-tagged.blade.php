<h1>Facebook Tagged Posts</h1>

@foreach($posts as $post)

<div style="border:1px solid #ccc;padding:10px;margin:10px;">

    <b>ID:</b>

    {{ $post['id'] }}

    <br><br>

    <b>Pesan:</b>

    {{ $post['message'] ?? '-' }}

</div>

@endforeach