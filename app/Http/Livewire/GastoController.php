<?php

namespace App\Http\Livewire;

use Livewire\Component;
use\App\Auditoria;
use\App\Gasto;
use DB;

class GastoController extends Component
{  
	public $descripcion, $comentario = '';            
    public $selected_id, $search; 
    public $comercioId, $action = 1;
    public $recuperar_registro = 0, $descripcion_soft_deleted, $id_soft_deleted;

    public function render()
    {
        //busca el comercio que está en sesión
        $this->comercioId = session('idComercio');

        if(strlen($this->search) > 0)
        {
            $info = Gasto::where('descripcion', 'like', '%' .  $this->search . '%')
                    ->where('comercio_id', $this->comercioId)
                    ->orderby('descripcion','desc')->get();
            return view('livewire.gastos.component', [
                'info' =>$info
            ]);
        }
        else {
           return view('livewire.gastos.component', [
            'info' => Gasto::orderBy('descripcion', 'asc')
                            ->where('comercio_id', $this->comercioId)->get()
        ]);
       }
    }

    public function doAction($action)
    {
        $this->action = $action;
        $this->resetInput();
    }

    private function resetInput()
    {
        $this->descripcion = '';
        $this->selected_id = null;    
        $this->search = '';
        $this->comentario = '';
    }

    public function edit($id)
    {
        $record = Gasto::findOrFail($id);
        $this->selected_id = $id;
        $this->descripcion = $record->descripcion;
        
        $this->action = 2;
    }

    public function volver()
    {
        $this->recuperar_registro = 0;
        $this->resetInput();
        return; 
    }

    public function RecuperarRegistro($id)
    {
        DB::begintransaction();
        try{
            Gasto::onlyTrashed()->find($id)->restore();
            session()->flash('msg-ok', 'Registro recuperado');
            $this->volver();
            
            DB::commit();               
        }catch (\Exception $e){
            DB::rollback();
            session()->flash('msg-error', '¡¡¡ATENCIÓN!!! El registro no se recuperó...');
        }
    }

    public function StoreOrUpdate()
    { 
        $this->validate(['descripcion' => 'required']);

        DB::begintransaction();
        try{
            if($this->selected_id > 0) {
                $existe = Gasto::where('descripcion', $this->descripcion)
                    ->where('id', '<>', $this->selected_id)
                    ->where('comercio_id', $this->comercioId)
                    ->withTrashed()->get();
                if($existe->count() > 0 && $existe[0]->deleted_at != null) {
                    session()->flash('info', 'El Gasto que desea modificar ya existe pero fué eliminado anteriormente, para recuperarlo haga click en el botón "Recuperar registro"');
                    $this->action = 1;
                    $this->recuperar_registro = 1;
                    $this->descripcion_soft_deleted = $existe[0]->descripcion;
                    $this->id_soft_deleted = $existe[0]->id;
                    return;
                }elseif($existe->count() > 0) {
                    session()->flash('info', 'El Gasto ya existe...');
                    $this->resetInput();
                    return;
                }
            }else {
                $existe = Gasto::where('descripcion', $this->descripcion)
                    ->where('comercio_id', $this->comercioId)->withTrashed()->get();

                if($existe->count() > 0 && $existe[0]->deleted_at != null) {
                    session()->flash('info', 'El Gasto que desea agregar ya existe pero fué eliminado anteriormente, para recuperarlo haga click en el botón "Recuperar registro"');
                    $this->action = 1;
                    $this->recuperar_registro = 1;
                    $this->descripcion_soft_deleted = $existe[0]->descripcion;
                    $this->id_soft_deleted = $existe[0]->id;
                    return;
                }elseif($existe->count() > 0) {
                    session()->flash('info', 'El Gasto ya existe...');
                    $this->resetInput();
                    return;
                }
            }
            if($this->selected_id <= 0) {
                $gasto =  Gasto::create([
                    'descripcion' => strtoupper($this->descripcion),
                    'comercio_id' => $this->comercioId
                ]);
            }else {   
                $gasto = Gasto::find($this->selected_id);
                $gasto->update([
                    'descripcion' => strtoupper($this->descripcion)
                ]);                
                $this->action = 1;             
            }
            if($this->selected_id) session()->flash('msg-ok', 'Gasto Actualizado');            
            else session()->flash('msg-ok', 'Gasto Creado');
            
            DB::commit();               
        }catch (\Exception $e){
            DB::rollback();
            session()->flash('msg-error', '¡¡¡ATENCIÓN!!! El registro no se grabó...');
        }
        $this->resetInput();
        return;
    }

    protected $listeners = ['deleteRow'=>'destroy'];  

    public function destroy($id)
    {
        if ($id) {
            DB::begintransaction();
            try{
                $gasto = Gasto::find($id)->delete();
                $audit = Auditoria::create([
                    'item_deleted_id' => $id,
                    'tabla' => 'Gastos',
                    'user_delete_id' => auth()->user()->id,
                    'comentario' => $this->comentario,
                    'comercio_id' => $this->comercioId
                ]);
                session()->flash('msg-ok', 'Registro eliminado con éxito!!');
                DB::commit();               
            }catch (\Exception $e){
                DB::rollback();
                session()->flash('msg-error', '¡¡¡ATENCIÓN!!! El registro no se eliminó...');
            }
            $this->resetInput();
            return;
        }
    } 
}
