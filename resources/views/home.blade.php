@extends('layouts.template')

@section('logo')     
  @livewire('logo-controller')
@endsection

@section('content')
<div class="container">
    <div class="widget-content-area mt-3">
        <div class="widget-one">
            <div class="row">
                @if(Auth::user()->sexo == 1)
                    <h1>Bienvenida <strong>{{Auth::user()->name}}!!!</strong></h1>
                @else
                    <h1>Bienvenido <strong>{{Auth::user()->name}}!!!</strong></h1>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
