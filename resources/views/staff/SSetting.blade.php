@extends('layouts.staff-layout')

@section('title', 'DreamHouse • Backup Restoration')

@section('content')
<h1>Available Backups</h1>
<p>Select a backup file from the list below to restore.</p>

@if(session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        {{ $errors->first() }}
    </div>
@endif

<ul>
    @forelse ($zipFiles as $file)
        <li>{{ $file['name'] }} - {{ \Carbon\Carbon::createFromTimestamp($file['timestamp'])->format('Y-m-d H:i:s') }}</li>
    @empty
        <li>No backups available.</li>
    @endforelse
</ul>
@endsection
