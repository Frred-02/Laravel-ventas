<?php

namespace App\Http\Livewire;



use App\Models\Category;
use App\Models\Product;

use Livewire\Component;

use Livewire\withFiLeUpLoads;
use  Livewire\withPagination;

class ProductsController extends Component
{

use withPagination;
use withFiLeUpLoads;


    public $name ,$barcode,$cost,$price,$stock,$alerts,$categoryid,$search,$image,$selected_id,$pageTitle,$componentName;
    private $pagination = 5;

     public function paginationView()
     {
         return 'vendor.livewire.bootstrap';
     }


     public function mount()
     {
        $this->pageTitle = 'Listado';
        $this->componentName = 'Productos';
        $this->categoryid = 'Elegir';
     }




    public function render()
    {

       if (strlen($this->search) > 0 )

       $products = Product::join('categories as c','c.id','products.category_id')
                  ->select('products.*','c.name as category')
                  ->where('products.name', 'like', '%' . $this->search .'%')
                  ->orwhere('products.barcode' , 'like' , '%' . $this->search . '%')
                  ->orwhere('c.name','like' , '%' .$this->search .'%')
                  ->orderBy('products.name', 'asc')
                  ->paginate($this->pagination);



       
       else
       $products = Product::join('categories as c','c.id','products.category_id')
       ->select('products.*','c.name as category')
       ->orderBy('products.name', 'asc')
                  ->paginate($this->pagination);



       return view('livewire.products.component',[
          'data'=> $products,
          'categories'=> Category::orderBy('name' , 'asc')->get()

       ])
       

       ->extends('layouts.theme.app')
       ->section('content');

      
    }

    public function Store()
    {
       $rules=[
        'name' => 'required |unique:products|min:3',
        'cost'=> 'required',
        'price'=> 'required',
        'stock'=> 'required',
        'alerts'=> 'required',
        'categoryid'=> 'required|not_in:Elegir'

       ];

       $messages=[
          'name.required'=>'nombre de producto requerido',
          'name.unique'=> 'ya existe el nombre del producto',
          'name.min' =>'el nombre del producto deve tener  al menos 3 caracteres ',
          'cost.required'=> 'el costo es requerido',
          'price.required '=> 'el precio es requerido',
          'stock.required'=> 'el stock es requerido',
          'alerts.required'=> 'ingrese el valor minimoi de existencias',
          'categoryid.not_in'=> 'elige un monbre de categoria diferente de elegir',

       ];
       $this->validate($rules, $messages);

       $product = Product::create([
        'name' => $this->name,
        'cost' => $this->cost,
        'price' => $this->price,
        'barcode' => $this->barcode,
        'stock' => $this->stock,
        'alerts' => $this->alerts,
        'category_id' => $this->categoryid
        
       ]);

       /*validar la imagen */
     
      if($this->image)
      {
         $customFileName = uniqid() . '_.' . $this->image->extension();
         $this->image->storeAs('public/products', $customFileName);
         $product->image = $customFileName;
         $product->save();
      }
      $this->resetUI();
      $this->emit('product-added', 'Producto Registrado');

    }

   /*EDITAR */
    public  function Edit(Product $product)
    {
         $this->selected_id =$product->id;
         $this->name =$product->name;
         $this->barcode =$product->barcode;
         $this->cost =$product->cost;
         $this->price =$product->price;
         $this->stock =$product->stock;
         $this->alerts =$product->alerts;
         $this->categoryid =$product->category_id;
         $this->image =null;


         $this->emit('modal-show','Show modal');
    }

    public function Update()
    {
       $rules=[
        'name' => "required|min:3|unique:products,name,{$this->selected_id}",
        'cost'=> 'required',
        'price'=> 'required',
        'stock'=> 'required',
        'alerts'=> 'required',
        'categoryid'=> 'required|not_in:Elegir'

       ];

       $messages=[
          'name.required'=>'nombre de producto requerido',
          'name.unique'=> 'ya existe el nombre del producto',
          'name.min' =>'el nombre del producto deve tener  al menos 3 caracteres ',
          'cost.required'=> 'el costo es requerido',
          'price.required '=> 'el precio es requerido',
          'stock.required'=> 'el stock es requerido',
          'alerts.required'=> 'ingrese el valor minimoi de existencias',
          'categoryid.not_in'=> 'elige un monbre de categoria diferente de elegir',

       ];
       $this->validate($rules, $messages);
      $product = Product::find($this->selected_id);
       $product ->Update([
        'name' => $this->name,
        'cost' => $this->cost,
        'price' => $this->price,
        'barcode' => $this->barcode,
        'stock' => $this->stock,
        'alerts' => $this->alerts,
        'category_id' => $this->categoryid
        
       ]);

       /*validar la imagen */

      if($this->image)
      {
         $customFileName = uniqid() . '_.' . $this->image->extension();
         $this->image->storeAs('public/products', $customFileName);
         
         $imageTemp =$product->image;  /*imagen temporal */
         $product->image =$customFileName;
         $product->save();

         if($imageTemp !=null)
         {
            if(file_exists('storage/products/' . $imageTemp)){
               unlink ('storage/products/' . $imageTemp);

            }
         }
      }
      $this->resetUI();
      $this->emit('product-updated', 'Producto Actulizado');

    }


    

    public function resetUI()
    {
      $this->name ='';
      $this->barcode ='';
      $this->cost ='';
      $this->price ='';
      $this->stock ='';
      $this->alerts ='';
      $this->categoryid='Elegir';
      $this->image = null;
      $this->search ='';
      $this->selected_id =0;

    }

    /*ELIMINAR */

    protected $listeners =['deleteRow'=> 'Destroy'];

    public function Destroy(Product $product)
    {
       $imageTemp = $product->image;
       $product->delete();
       if($imageTemp !=null){
         if(file_exists('storage/products/' . $imageTemp)){
            unlink ('storage/products/' . $imageTemp);
       }
    }

    $this->resetUI();
    $this->emit('product-deleted','producto eliminado');
}


}