<h1>Multi Channel Aduan</h1>

<table border="1" cellpadding="10">

<tr>
    <th>Channel</th>
    <th>Pengirim</th>
    <th>Pesan</th>
</tr>

@foreach($messages as $msg)

<tr>
    <td>{{ $msg['channel'] }}</td>
    <td>{{ $msg['sender'] }}</td>
    <td>{{ $msg['message'] }}</td>
</tr>

@endforeach

</table>