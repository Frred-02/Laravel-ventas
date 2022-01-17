<?php

namespace App\Http\Livewire;
use App\Models\Denomination;
use App\Models\Product;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use Livewire\Component;

class PosController extends Component
{


    public $total,$itemsQuantity, $efectivo, $change;
    
    public function mount()
    {
        $this->efectivo =0;
        $this->change =0;
        $this->total = cart::getTotal();
        $this->itemsQuantity = Cart::getTotalQuantity();

    }




    public function render()
    {
         return view('livewire.pos.component',[
             'denominations' => Denomination::orderBy('value', 'desc')->get(),
             'cart' => Cart::getContent()->sortBy('name')

         ])  
        ->extends('layouts.theme.app')
        ->section('content');
    }

    public function ACash($value)
    {
        $this->efectivo += ($value == 0 ? $this -> total : $value);
        $this -> change = ($this->efectivo - $this->total);
    }
    

    protected $listeners =[
        'scan-code' => 'Scancode',
        'removeItem'=> 'removeItem',
        'clearCart' => 'clearCart',
        'saveSale' => 'saveSale'
    ];
     



    public function ScanCode ($barcode, $cant = 1)
   {
    $product = Product::where('barcode', $barcode)->first();
    if($product == null || empty ($empty))
    {
        $this->emit('scan-notfount','el producto no esta registrado');
    } else {

        if($this->Incart($product->id))
        {
           $this->increaseQty($product-id);
           return;
        }
         
        /*validacion */

      if($product->stock <1)
      {
          $this->emit('no-stock' , 'stock insuficiente =( ');
          return;
      }

      Cart:: add($product->id, $product->name, $product->price, $cant,$product->imge);
      $this->total =Cart::getTotal();

      $this->emit('scan-ok', 'Product agregado');


    }
   }

   /*metodo incart*/

   public function InCart($productId)
   {
       $exist = Cart::get ($product);
       if($exist)
       return true;
       else
       return false;
   }
}


