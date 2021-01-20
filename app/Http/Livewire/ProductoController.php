<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Producto;
use App\Rubro;


class ProductoController extends Component
{
	public $rubro ='Elegir', $descripcion, $estado='DISPONIBLE', $precio_costo=null, $precio_venta;
	public $codigo, $codigo_sugerido, $selected_id = null, $search, $rubros;
	public $comercioId;
	
	public function render()
	{
		//busca el comercio que está en sesión
		$this->comercioId = session('idComercio');
		
		$this->rubros = Rubro::select('*')->where('comercio_id', $this->comercioId)->get();

		if($this->selected_id == null) {
			$nuevo_codigo = Producto::select('*')->where('comercio_id', $this->comercioId)->get();
			if ($nuevo_codigo->count() == 0){
				$this->codigo_sugerido = 1;
			}else{
				$nuevo_codigo = Producto::select('id')
                ->where('comercio_id', $this->comercioId)
				->orderBy('id','desc')->first();
				$this->codigo_sugerido = $nuevo_codigo->id + 1;
			}
		}else{
			$this->codigo_sugerido = $this->selected_id;
		}

		if(strlen($this->search) > 0) 
		{
			$info = Producto::leftjoin('rubros as r','r.id','productos.rubro_id')
			->select('productos.*', 'r.descripcion as rubro')
			->where('productos.descripcion', 'like', '%' . $this->search .'%')
			->where('productos.comercio_id', $this->comercioId)
			->orWhere('productos.estado', 'like', '%' . $this->search .'%')
			->where('productos.comercio_id', $this->comercioId)
			->orWhere('r.descripcion', 'like', '%' . $this->search .'%')
			->where('productos.comercio_id', $this->comercioId)
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
			->where('productos.comercio_id', $this->comercioId)
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
		$this->codigo = '';
		$this->descripcion = '';
		$this->precio_costo = null;
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
		$this->codigo = $record->codigo;
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
			'codigo' => 'required|integer',
			'descripcion' => 'required',
			'precio_venta' => 'required',
			'estado' => 'required'
		//	'precio_costo' => 'required',
        ]);
        
        //valida si existe otro producto con el mismo nombre (edicion de productos)
        if($this->selected_id > 0) {
            $existe = Producto::where('descripcion', $this->descripcion)
				->where('id', '<>', $this->selected_id)
                ->where('comercio_id', $this->comercioId)				
                ->get();
        
            if( $existe->count() > 0) {
                session()->flash('msg-error', 'Ya existe el Producto');
                $this->resetInput();
                return;
			}
			//valida si existe otro producto con el mismo código (edicion de productos)
			$existe = Producto::where('codigo', $this->codigo)
				->where('id', '<>', $this->selected_id)
				->where('comercio_id', $this->comercioId)
				->get();
	
			if( $existe->count() > 0) {
				session()->flash('msg-error', 'Ya existe el Código');
				$this->resetInput();
				return;
		}
        }else{
            //valida si existe otro producto con el mismo nombre (nuevos registros)
			$existe = Producto::where('descripcion', $this->descripcion)
								->where('comercio_id', $this->comercioId)->get();
        
            if($existe->count() > 0 ) {
                session()->flash('msg-error', 'Ya existe el Producto');
                $this->resetInput();
                return;
			}
			//valida si existe otro producto con el mismo código de barras (nuevos registros)
			$existe = Producto::where('codigo', $this->codigo)
								->where('comercio_id', $this->comercioId)->get();
        
            if($existe->count() > 0 ) {
                session()->flash('msg-error', 'Ya existe el Código');
                $this->resetInput();
                return;
            }
        }

		if($this->selected_id <=0)
		{
			$cajon = Producto::create([
				'codigo' => $this->codigo,
				'descripcion' => ucwords($this->descripcion),
				'precio_costo' => $this->precio_costo,
				'precio_venta' => $this->precio_venta,
				'rubro_id' => $this->rubro,
				'estado' => $this->estado,
				'comercio_id' => $this->comercioId
			]);
		}
		else {
		//	dd($this->precio_costo);
			$record = Producto::find($this->selected_id);
				if ($this->precio_costo == '') $this->precio_costo = 0.00;
			$record->update([
				'codigo' => $this->codigo,
				'descripcion' => ucwords($this->descripcion),
				'precio_costo' => $this->precio_costo,			
				'precio_venta' => $this->precio_venta,
				'rubro_id' => $this->rubro,
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
