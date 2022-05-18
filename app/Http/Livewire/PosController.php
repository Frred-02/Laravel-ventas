<?php

namespace App\Http\Livewire;

use App\Models\Denomination;

use Darryldecode\cart\Facades\CartFacade as Cart;

use Livewire\Component;
use App\Models\Product;



use DB;

class PosController extends Component
{

    public $total, $itemsQuantity, $efectivo, $change ;

    public function mount()
    {
        $this->efectivo =0;
        $this->change =0;
        $this->total = Cart::getTotal();
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
        $this->efectivo += ($value == 0 ? $this->total : $value);
        $this->change = ($this->efectivo - $this->total);
    }
    protected $listeners =[
        'scan-code' => 'ScanCode',
        'removeItem'=> 'removeItem',
        'clearCart' => 'clearCart',
        'saveSale' => 'saveSale'
    ];

    public function ScanCode($barcode, $cant = 1)
   {
       /*dd($barcode);*/
       $product = Product::where ('barcode' , $barcode)->first();
    if($product == null || empty ($empty))
    {
        $this->emit('scan-notfound','el producto no esta registradoooo');
    } else 
    
    {

        if($this->InCart($product->id))
        {
           $this->increaseQty($product->id);
           return;
        }
         
        /*validacion */

      if($product->stock < 1)
      {
          $this->emit('no-stock' , 'stock insuficiente  ');
          return;
      }

      Cart::add($product->id, $product->name, $product->price, $cant, $product->image);
      $this->total = Cart::getTotal();

      $this->emit('scan-ok', 'Product agregado');


    }
   }
   /*metodo incart*/

   public function InCart($productId)
   {
       $exist = Cart::get($productId);
       if($exist)
       return true;
       else
       return false;
   }

    /*cantida de productos al agregar carrito de compras */
    public function increaseQty($productId, $cant = 1)
    {
        $title ='';
        $product = Product::find ($productId);
        $exist = Cart::get($productId);
        if ($exist)
          $title = 'cantidad  actualizada';
          else
          $title ='producto agregado';
 
          if ($exist)
          {
              if($product->stock < ($cant + $exist->quantity))
              {
                  $this->emit('no-stock', 'Stock insuficienrwe :/');
                  return;
              }
 
          }
 
          Cart:: add($product->id, $product->name, $product->price , $cant, $product->image);
 
          $this->total = Cart::getTotal();
          $this->itemsQuantity = Cart::getTotalQuantity();
          $this->emit('scan-ok' , $title);
    }

    public function updateQty($productId ,$cant = 1)
    {
        $title ='';
        $product = Product::find($productId);
        $exist = Cart::get($productId);
        if (exist)
          $title = 'cantidad  actualizada';
          else
          $title ='productio agregado';
 
          if($exist)
          {
              if($product->stock < $cant)
              {
                 $this->emit('no-stock', 'Stock insuficienrwe :/');
                 return;
              }
          }
 
          $this->removeItem($productId);
          if($cant > 0)
          {
             Cart:: add($product->id, $product->name, $product->price , $cant, $product->image);
 
             $this->total = Cart::getTotal();
             $this->itemsQuantity = Cart::getTotalQuantity();
             $this->emit('scan-ok' , $title);
 
          }

        }

          public function removeItem($productId)
          {
           Cart::remove($productId);
           $this->total = Cart::getTotal();
           $this->itemsQuantity =Cart::getTotalQuantity();
          $this->emit('scan-ok' , 'Producto eliminado');

}

       public function decreaseQty($productId)
         {
         $item = Cart::get($product);
           Cart::remove($productId);

             $newQty = ($item->quantity)-1;
             if($newQty > 0)

            Cart::add($item->id, $item->name, $item->price , $newQty, $item->attributes[0]);
              $this->total = Cart::getTotal();
             $this->itemsQuantity =Cart::getTotalQuantity();
              $this->emit('scan-ok' , 'cantidad actulizada');


}
            public function clearCart()
            {
                 Cart:: clear();
                    $this->efectivo =0;
                   $this->change=0;
                $this->total = Cart::getTotal();
                    $this->itemsQuantity =Cart::getTotalQuantity();
                       $this->emit('scan-ok' , 'carrito vacio');
            }



            public function saveSale()
            {
                if($this->total <=0)
                {
                    $this->emit('sale-error','agregar productos ala venta');
                    return;
                }
                if($this-efectivo <=0)
                {
                    $this->emit('sale-error','ingresar efectivo');
                    return;
                }
                if($this->total > $this->efectivo)
                {
                    $this->emit('sale-error','ele efectivo deve ser mayor o igual al total');
                    return;
                }
  
                DB::beginTransaction();
  
                      try {
  
                          $sale = Sale::create([
                           'total' => $this->total,
                           'items' => $this->itemsQuantity,
                           'cash' => $this->efectivo,
                           'change' => $this->change,
                           'user_id' => Auth()->user()->id
  
                          ]);
  
                          if($sale)
                          {
                              $item= Cart::getContent();
                              foreach($item as $item){
                                  SaleDetail::create([
                                      'price'=> $item->price,
                                      'quantity'=> $item->quantity,
                                      'product_id'=> $item->id,
                                      'sale_'=> $sale->id,
                                      
                                  ]);
  
                                  //actualizacion
                                  $product= Product::find($item->id);
                                  $product->stock= $product->stock - $item->quantity;
                                  $product->save();
  
  
                              }
                          }
  
                         /*------------- */
                          DB::commit();
                          Cart::clear();
                          $this->efectivo =0;
                          $this->change =0;
                          $this->total = Cart::getTotal();
                           $this->itemsQuantity =Cart::getTotalQuantity();
                           $this->emit ('sale-ok' ,'venta registrada con exito');
                           $this->emit ('print-ticket' ,$sale->id);
  
                      } catch(exception  $e )  {
  
                          DB::rollback();
                          $this->emit('sale-error' ,$e->getMessage());
  
  
                      }
  
            }

            
          public function printTicker($sale)
          {
              return Redirect::to("print://$sale->id");
          }
 
         
    }
 

    

