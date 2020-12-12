@extends('layouts.template')

@section('logo')
     
  @livewire('logo-controller')

@endsection

@section('title', 'Home')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 mt-2">
            <div class="card">                
                <div class="card-body">
                    <h1> ¡¡¡Hola {{ Auth::user()->nombre }}!!!</h1>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
