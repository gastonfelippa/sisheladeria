<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Producto;
use App\Rubro;


class ProductoController extends Component
{
	public $rubro ='Elegir', $descripcion, $estado='DISPONIBLE', $precio_costo, $precio_venta;
	public $selected_id = null, $search, $rubros;
	
	public function render()
	{
		$this->rubros = Rubro::all();

		if(strlen($this->search) > 0) 
		{
			$info = Producto::leftjoin('rubros as r','r.id','productos.rubro_id')
			->select('productos.*', 'r.descripcion as rubro')
			->where('productos.descripcion', 'like', '%' . $this->search .'%')
			->orWhere('productos.estado', 'like', '%' . $this->search .'%')
			->orWhere('r.descripcion', 'like', '%' . $this->search .'%')
			->orderBy('productos.descripcion', 'asc')
			->get();

			return view('livewire.productos.component', [
				'info' => $info
			]);
		}
		else 
		{
			$info = Producto::leftjoin('rubros as r','r.id','productos.rubro_id')
			->select('productos.*', 'r.descripcion as rubro')
			->orderBy('productos.descripcion', 'asc')
			->get();
		
			return view('livewire.productos.component', [
				'info' => $info
			]);
		}
	}

	public function doAction($action)
	{
		$this->resetInput();
	}

	public function resetInput()
	{
		$this->descripcion = '';
		$this->precio_costo = '';
		$this->precio_venta = '';
		$this->rubro = 'Elegir';
		$this->estado = 'DISPONIBLE';
		$this->selected_id = null;
		$this->search ='';
	}

	public function edit($id)
	{
		$record = Producto::find($id);
		$this->selected_id = $id;
		$this->rubro = $record->rubro_id;
		$this->descripcion = $record->descripcion;
		$this->precio_costo = $record->precio_costo;
		$this->precio_venta = $record->precio_venta;
		$this->estado = $record->estado;
	}

	public function StoreOrUpdate()
	{
		$this->validate([
			'rubro' => 'not_in:Elegir'
		]);
		
		$this->validate([
			'rubro' => 'required',
			'descripcion' => 'required',
			'precio_costo' => 'required',
			'precio_venta' => 'required',
			'estado' => 'required'
        ]);
        
        //valida si existe otro cajón con el mismo nombre (edicion de productos)
        if($this->selected_id > 0) {
            $existe = Producto::where('descripcion', $this->descripcion)
                ->where('id', '<>', $this->selected_id)
                ->select('descripcion')
                ->get();
        
            if( $existe->count() > 0) {
                session()->flash('msg-error', 'Ya existe el Producto');
                $this->resetInput();
                return;
            }
        }else{
            //valida si existe otro cajón con el mismo nombre (nuevos registros)
            $existe = Producto::where('descripcion', $this->descripcion)
                ->select('descripcion')
                ->get();
        
            if($existe->count() > 0 ) {
                session()->flash('msg-error', 'Ya existe el Producto');
                $this->resetInput();
                return;
            }
        }

		if($this->selected_id <=0)
		{
			$cajon = Producto::create([
				'descripcion' => ucwords($this->descripcion),
				'precio_costo' => $this->precio_costo,
				'precio_venta' => $this->precio_venta,
				'rubro_id' => $this->rubro,
				'estado' => $this->estado
			]);
		}
		else {
			$record = Producto::find($this->selected_id);
			$record->update([
				'descripcion' => ucwords($this->descripcion),
				'precio_costo' => $this->precio_costo,
				'precio_venta' => $this->precio_venta,
				'frubro_id' => $this->rubro,
				'estado' => $this->estado
			]);
		}

		if($this->selected_id > 0)		
		session()->flash('message', 'Producto Actualizado');       
		else 
		session()->flash('message', 'Producto Creado'); 

		$this->resetInput();
	}

	public function destroy($id) //eliminar / delete / remove
	{
		if($id) {
			$record = Producto::where('id', $id);
			$record->delete();
			$this->resetInput();
			$this->emit('msg-ok','Registro eliminado con éxito');

		}
	}
	
	protected $listeners = [
		'deleteRow' => 'destroy'        
	];  
}
