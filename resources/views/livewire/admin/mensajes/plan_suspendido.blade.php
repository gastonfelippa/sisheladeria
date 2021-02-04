
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <h3 class="mt-5 text-center col-sm-12 col-md-8">LO SIENTIMOS MUCHO...<br>TU SISTEMA ESTÁ SUSPENDIDO MOMENTÁNEAMENTE 
            HASTA QUE SE SE PRODUZCA EL PAGO CORRESPONDIENTE</h3>
    </div>
    <div class="dropdown-item mt-2 text-center">
        <form id="form1" class="form-horizontal" method="POST" action="{{ route('logout') }}">
            {{ csrf_field() }} 
        </form>
        <a class="" onclick="document.getElementById('form1').submit();" href="javascript:void(0)"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-out"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg> Salir</a>
    </div> 
@endsection