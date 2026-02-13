@php
$lapangans = \App\Models\Lapangan::all(['id', 'namaLapangan', 'gambar', 'region', 'admin_id']);
$sliders = \App\Models\Slider::all(['id', 'gambar', 'region', 'admin_id']);
$events = \App\Models\Event::all(['id', 'judul', 'gambar', 'region', 'admin_id']);
@endphp

<!DOCTYPE html>
<html>
<head>
    <title>Database Check</title>
</head>
<body>
<h2>Lapangan Records</h2>
<pre>
@foreach($lapangans as $l)
ID: {{ $l->id }}, Name: {{ $l->namaLapangan }}, Image: {{ $l->gambar }}, Region: {{ $l->region }}, Admin: {{ $l->admin_id }}
@endforeach
</pre>

<h2>Slider Records</h2>
<pre>
@foreach($sliders as $s)
ID: {{ $s->id }}, Image: {{ $s->gambar }}, Region: {{ $s->region }}, Admin: {{ $s->admin_id }}
@endforeach
</pre>

<h2>Event Records</h2>
<pre>
@foreach($events as $e)
ID: {{ $e->id }}, Title: {{ $e->judul }}, Image: {{ $e->gambar }}, Region: {{ $e->region }}, Admin: {{ $e->admin_id }}
@endforeach
</pre>

<h2>Gambar URL Test</h2>
<h3>Lapangan</h3>
@foreach($lapangans as $l)
@if($l->gambar)
<img src="{{ asset('storage/' . $l->gambar) }}" style="max-width: 200px; margin: 10px;" alt="{{ $l->namaLapangan }}">
<p>URL: {{ asset('storage/' . $l->gambar) }}</p>
@endif
@endforeach

<h3>Slider</h3>
@foreach($sliders as $s)
@if($s->gambar)
<img src="{{ asset('storage/' . $s->gambar) }}" style="max-width: 200px; margin: 10px;" alt="Slider">
<p>URL: {{ asset('storage/' . $s->gambar) }}</p>
@endif
@endforeach

<h3>Event</h3>
@foreach($events as $e)
@if($e->gambar)
<img src="{{ asset('storage/' . $e->gambar) }}" style="max-width: 200px; margin: 10px;" alt="{{ $e->judul }}">
<p>URL: {{ asset('storage/' . $e->gambar) }}</p>
@endif
@endforeach
</body>
</html>
