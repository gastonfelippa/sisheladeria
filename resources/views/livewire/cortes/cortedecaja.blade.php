@extends('layouts.pdf')

@section('content') 
	<div>
		<div>
			<b>Cliente:</b>  {{$info[0]->nomcli}}<br>                       
			<b>Dirección:</b>  {{$info[0]->dircli}}<br>                      
			<b>Fecha:</b>  {{\Carbon\Carbon::parse($info[0]->created_at)->format('d-m-Y')}}
			<br><br>
		</div>
		<div>
			<table class="table table-sm">
				<thead style="font-size:14px">
					<tr>
						<th class="text-center">VENTAS</th>
						<th class="text-left">ENTRADAS</th>
						<th class="text-center">SALIDAS</th>
						<th class="text-center">BALANCE</th>
					</tr>
				</thead>
				<tbody style="font-size:12px">
					@foreach($info as $r)
					<tr>
						<td class="text-center">{{number_format($r->ventas,0)}}</td>
						<td class="text-left">{{number_format($r->entradas,0)}}</td>
						<td class="text-right mr-2">{{$number_format($r->salidas,0)}}</td>
						<td class="text-right mr-2">{{number_format($r->balance,0)}}</td>
					</tr>
					@endforeach
				</tbody>
			</table> 		                  
		</div><br>
		<div class="text-right mr-2">
			<b>TOTAL: $  {{number_format($info[0]->importe,2)}}</b>
		</div>
	</div>
@endsection