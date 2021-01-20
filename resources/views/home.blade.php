@extends('layouts.template')

@section('logo')
     
  @livewire('logo-controller')

@endsection

@section('content')
<div class="container">
    <div class="widget-content-area mt-3">
        <div class="widget-one">
            <div class="row">
                <h1>Bienvenido <strong>{{Auth::user()->name}}!!!</strong></h1>
            </div>
        </div>
    </div>
</div>
@endsection
