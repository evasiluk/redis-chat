@extends('layouts.app')

@section('content')
    <private-chat v-bind:room="{{$room->id}}" v-bind:user="{{auth()->user()}}"></private-chat>
@endsection