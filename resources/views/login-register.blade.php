@extends('layouts.app')

<!-- @section('content')
     

<h1>AL FIIIIIIIIINNNNNNNN!!!!!!!!!!</h1>
@endsection -->



@section('logo')
     
  @livewire('logo-controller')

@endsection

@section('content')
     
  @livewire('login-register-user-controller')

@endsection