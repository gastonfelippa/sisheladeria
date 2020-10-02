@extends('layouts.pdf')

@section('content')
<div >
	<table class="table table-hover table-checkable table-sm mb-4">
		<thead>
			<tr>
				<th class="text-center">CLIENTE</th>
				<th class="text-center">IMPORTE</th>
			</tr>
		</thead>
		<tbody>
			@foreach($info as $r)
			<tr>
				<td class="text-left">{{$r->nomcli}}</td>
				<td class="text-right">{{number_format($r->importe,2)}}</td>
			</tr>
			@endforeach
		</tbody>
	</table>                   
</div>
@endsection