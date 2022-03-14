<?php
/*probando */
namespace App\Http\Livewire;

use Livewire\Component;
 /*comentario */
 use App\Models\Category;
 use Illuminate\Support\Facades\Storage;
 use Livewire\withFiLeUpLoads;/* trait subir imagenes  */

 use Livewire\withPagination;

class CategoriesController extends Component
{
    /*comentario */
       
    use withFiLeUpLoads;
    use withPagination;
     
     /*comentario declaramos */
    public $name, $search ,$image, $selected_id, $pageTitle, $componentName;
    private $pagination= 5;

       /* se utiliza para renderizar informacion*/
    public function mount()
    {
      $this->pageTitle='Listado';
      $this->componentName ='Categorias';
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
    $data = Category::where('name', 'like', '%' . $this->search . '%')->paginate($this->pagination);

    else 
    $data = Category::orderBy('id','desc')->paginate($this->pagination);

     /*comentario retornar vista categoria*/
    return view('livewire.category.categories',['categories' =>$data])
    
    /*comentario */
    
    ->extends('layouts.theme.app')
    ->section('content');

  }
    

     /*EDITAR* */

   public function Edit($id)
   {
     $record = category::find($id, ['id','name','image']);
     $this->name= $record->name;
     $this->selected_id = $record->id;
     $this->image = null;

     $this->emit('show-modal', 'show modal!');
   }

      public function Store()
      {
        $rules =[
          'name' => 'required|unique:categories|min:3'

        ];
        $messages =[
          'name.required' => 'nombre de la categoria es requerido',
          'name.unique' => 'ya existe el nombre de la categoria',
          'name.min' => 'el nombre de la categoria debe tener al menos  3 caracteres'
        ];

        $this->validate($rules, $messages);


        $category = category::create([
           'name' =>$this->name
        ]);

        $customFileName;
        if($this->image)
        {
           $customFileName = uniqid() . '_.' . $this->image->extension();
           $this->image->storeAs('public/categories', $customFileName);
           $category->image = $customFileName;
           $category->save();

        }
           
        $this->resetUI();
        $this->emit('category-added','categoria Registrada');
      }
          /*ACTUALIZAR */
      public function Update()
      {
        $rules =[
          'name'=> "required|min:3|unique:categories,name,{$this->selected_id}"

        ];

        $messages =[
          'name.required'=> 'nombre de la categoria es requerido',
          'name.unique'=> 'ya existe el nombre de la categoria',
          'name.min'=> 'el nombre de la categoria debe tener al menos  3 caracteres'
        ];
        $this->validate ($rules,$messages);
        $category = category::find($this->selected_id);
        $category->Update([
          'name'=>$this->name
        ]);
        if($this->image)
        {
          $customFileName  = uniqid() . '_.' . $this->image->extension();
          $this->image->storeAs('public/categories', $customFileName);
          $imageName = $category->image;
          

          $category->image = $customFileName;
          $category->save();

          if($imageName !=null)
          {
            if(file_exists('storage/categories' . $imageName))
            {
              unlink('storage/categories' . $imageName);
            }
          }
        }
         
        $this->resetUI();
        $this->emit('category-updated', 'categoria actualizada');
      }


   public function resetUI()
   {
     $this->name ='';
     $this->image = null;
     $this->search ='';
     $this->selected_id =0;

   }

  /*ELIMINAR */


   protected $listeners =[
     'deleteRow' =>'Destroy'
   ];
  public function Destroy ( Category $category)
  {
   // $category = Category::find ($id);
   //dd($category);

    $imageName =$category->image ;
    $category->delete();
    if ($imageName != null){
      unlink('storage/categories/' . $imageName);

    }
    $this -> resetUI();
    $this-> emit('category-delete' , 'categoria eliminada');
  }

}
