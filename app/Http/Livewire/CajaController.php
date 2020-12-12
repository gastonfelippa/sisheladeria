<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use App\Caja;


class CajaController extends Component
{
	//paginado
	use WithPagination;

	//properties
	public $tipo ='Elegir', $concepto, $monto;
	public $selected_id, $search;
	public $action = 1, $pagination = 5;


	public function render()
	{
		if(strlen($this->search) > 0)
		{
			return view('livewire.movimientos.component', [
				'info' => Caja::where('tipo', 'like', '%'. $this->search . '%')
				->orWhere('concepto', 'like', '%'. $this->search . '%')
				->paginate($this->pagination),
			]);
		}
		else {
			$caja = Caja::leftjoin('users as u','u.id', 'cajas.user_id')
			->select('cajas.*', 'u.nombre')
			->orderBy('id','desc')
			->paginate($this->pagination);

			return view('livewire.movimientos.component', [
				'info' => $caja
			]);
		}
	}

	public function updatingSearch()
	{
		$this->gotoPage(1);
	}


	public function doAction($action)
	{
		$this->resetInput();
		$this->action = $action;
	}

	public function resetInput()
	{
		$this->concepto ='';
		$this->tipo = 'Elegir';
		$this->monto ='';
		$this->selected_id = null;
		$this->action = 1;
		$this->search ='';
	}

	public function edit($id)
	{
		$record = Caja::find($id);
		$this->selected_id = $id;
		$this->tipo = $record->tipo;
		$this->concepto = $record->concepto;
		$this->monto = $record->monto;
		$this->action = 3;
	}

	public function StoreOrUpdate() 
	{
		$this->validate([
			'tipo' => 'not_in:Elegir'
		]);

		$this->validate([
			'tipo' => 'required',
			'monto' => 'required',
			'concepto' =>'required'
		]);

		if($this->selected_id <=0)
		{
			$caja = Caja::create([
				'monto' => $this->monto,
				'tipo' => $this->tipo,
				'concepto' => $this->concepto,
				'user_id' => Auth::user()->id // auth()->user()->id
			]);
		}
		else{
			$record = Caja::find($this->selected_id);
			$record->update([
				'monto' => $this->monto,
				'tipo' => $this->tipo,
				'concepto' => $this->concepto,
				'user_id' => Auth::user()->id 
			]);
		}

        if($this->selected_id)	
            session()->flash('message', 'Movimiento de Caja Actualizado con Éxito');
        else 
            session()->flash('message', 'Movimiento de Caja creado con Éxito');
			
		$this->resetInput();

	}

	protected $listeners = [
		'deleteRow' =>'destroy',
		'fileUpload' =>'handleFileUpload'
	];

	public function destroy($id)
	{
		if($id) {
			$record = Caja::where('id', $id);
			$record->delete();
			$this->resetInput();
		}
	}
}
