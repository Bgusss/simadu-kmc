<!DOCTYPE html>
<html>

<head>
    <title>Data API</title>
</head>

<body>

    <h1>Data dari API</h1>

    @foreach ($posts as $post)
        <div style="border:1px solid #ccc;padding:10px;margin:10px;">

            <h3>{{ $post['title'] }}</h3>

            <p>{{ $post['body'] }}</p>

        </div>
    @endforeach

</body>

</html>
