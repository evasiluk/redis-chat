@extends('layouts.app')

@section('content')
<div class="container">
    <ul>
        @forelse($rooms as $room)
            <li><a href="/room/{{$room->id}}">{{$room->name}}</a></li>
        @empty
            <li>You haven't any rooms yet :(</li>
        @endforelse
    </ul>
</div>
@endsection
