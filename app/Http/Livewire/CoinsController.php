<?php

namespace App\Http\Livewire;
use Illuminate\Support\Facades\Storage;
use App\Models\Denomination;
use Livewire\Component;
use livewire\WithFileUpLoads;

 use Livewire\withPagination;

class CoinsController extends Component
{
    
 /*comentario */
       
 use withFiLeUpLoads;
 use withPagination;
  

     /*comentario declaramos */
     public  $type  ,$value, $search ,$image, $selected_id, $pageTitle, $componentName;
     private $pagination= 5;
 
        /* se utiliza para renderizar informacion*/
     public function mount()
     {
       $this->pageTitle='Listado';
       $this->componentName ='Denominaciones';
       $this->type ='Eligir';
     }
 
            /*comentario paginas siguientes */
        public function paginationView()
        {
          return 'vendor.livewire.bootstrap';
        }
 
 
   public function render()
   {
         
     if(strlen($this->search) > 0)
 
              /*BUSCADOR */
     $data = Denomination::where('type', 'like', '%' . $this->search . '%')->paginate($this->pagination);
 
     else 
     $data = Denomination::orderBy('id','desc')->paginate($this->pagination);
 
      /*comentario retornar vista categoria*/
     return view('livewire.denominations.component',['data' =>$data])
     
     /*comentario */
     
     ->extends('layouts.theme.app')
     ->section('content');
 
   }
     
 
      /*EDITAR* */
 
    public function Edit($id)
    {
      $record = Denomination::find($id, ['id','type','value','image']);
      $this->type= $record->type;
     $this->value= $record->value;
      $this->selected_id = $record->id;
      $this->image = null;
 
      $this->emit('show-modal', 'show modal!');
    }
 
       public function Store()
       {
         $rules =[
           'type' => 'required|not_in:Elegir',
           'value'=> 'required|unique:denominations'
 
         ];
         $messages =[
           'type.required' => 'el tipo es requerido',
           'type.not_in' => 'elige un valor para otro distino a Elegir',
           'value.required' => 'el valor es requerido',
           'value.unique'=>'ya existe el valor'
         ];
 
         $this->validate($rules, $messages);
 
 
         $denomination = Denomination::create([
            'type' =>$this->type,
            'value' =>$this->value
         ]);
 
         
         if($this->image)
         {
            $customFileName = uniqid() . '_.' . $this->image->extension();
            $this->image->storeAs('public/denominations', $customFileName);
            $denomination->image = $customFileName;
            $denomination->save();
 
         }
            
         $this->resetUI();
         $this->emit('item-added','Denominacion Registrada');
       }
           /*ACTUALIZAR */
       public function Update()
       {
         $rules =[

            'type'=>'required|not_in:Elegir',
           'value'=> "required|unique:denominations,value,{$this->selected_id}"
 
         ];
 
         $messages =[
           'type.required'=> 'el tipo  es requerido',
           'type.not_in'=> 'elige un tipo valido',
           'value.required'=> 'el valor es requerido',
           'value.unique'=> 'el valor ya existe'
         ];
         $this->validate ($rules,$messages);
         $denomination = Denomination::find($this->selected_id);
         $denomination->Update([
           'type'=>$this->type,
           'value'=>$this->value

         ]);
         if($this->image)
         {
           $customFileName  = uniqid() . '_.' . $this->image->extension();
           $this->image->storeAs('public/denominations', $customFileName);
           $imageName = $denomination->image;
           
 
           $denomination->image = $customFileName;
           $denomination->save();
 
           if($imageName !=null)
           {
             if(file_exists('storage/denominations' . $imageName))
             {
               unlink('storage/denominations' . $imageName);
             }
           }
         }
          
         $this->resetUI();
         $this->emit('item-updated', 'Denominacion actualizada');
       }
 
 
    public function resetUI()
    {
      $this->type ='';
      $this->value ='';
      $this->image = null;
      $this->search ='';
      $this->selected_id =0;
 
    }
 
   /*ELIMINAR */
 
 
    protected $listeners =[
      'deleteRow' =>'Destroy'
    ];
   public function Destroy ( Denomination $denomination)
   {
    // $category = Category::find ($id);
    //dd($category);
 
     $imageName =$denomination->image ;
     $denomination->delete();
     if ($imageName != null){
       unlink('storage/denominations/' . $imageName);
 
     }
     $this -> resetUI();
     $this-> emit('item-delete' , 'Deniminacion eliminada');
   }
 
}
