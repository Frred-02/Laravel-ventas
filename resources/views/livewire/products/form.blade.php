@include('common.modalHead')

<div class="row">

<div class="col-sm-12 col-md-8">

      <div class="form-grou">
   <label >Nombre</label> 
       <input type="text" wire:model.lazy="name" class="form-control" placeholder="ingrese producto">
      @error('name') <span class="text-danger er">{{ $message}}</span>@enderror

    </div>
</div>

<div class="col-sm-12 col-md-4">

      <div class="form-grou">
        <label >Codigo</label> 
        <input type="text" wire:model.lazy="barcode" class="form-control" placeholder="ingrese barra">
         @error('barcode') <span class="text-danger er">{{ $message}}</span>@enderror

      </div>
</div>


<div class="col-sm-12 col-md-4">

      <div class="form-grou">
   <label >Costo</label> 
       <input type="text" data-type='currency' wire:model.lazy="cost" class="form-control" placeholder="ingrese costo">
      @error('cost') <span class="text-danger er">{{ $message}}</span>@enderror

    </div>
</div>


<div class="col-sm-12 col-md-4">

      <div class="form-grou">
   <label >Precio</label> 
       <input type="text" data-type='currency' wire:model.lazy="price" class="form-control" placeholder="ingrese precio">
      @error('price') <span class="text-danger er">{{ $message}}</span>@enderror

    </div>
</div>


<div class="col-sm-12 col-md-4">

      <div class="form-grou">
   <label >Stock</label> 
       <input type="number"  wire:model.lazy="stock" class="form-control" placeholder="0">
      @error('stock') <span class="text-danger er">{{ $message}}</span>@enderror

    </div>
</div>

<div class="col-sm-12 col-md-4">

      <div class="form-grou">
   <label >Alertas</label> 
       <input type="text"  wire:model.lazy="alerts" class="form-control" placeholder=" -10">
      @error('alerts') <span class="text-danger er">{{ $message}}</span>@enderror

    </div>
</div>

<div class="col-sm-12 col-md-4">

<div class="form-group">

<label >Categoria</label>
<!--para elegir-->

<select wire:model='categoryid' class="form-control">

<option value="Elegir" disabled>Elegir</option>
@foreach($categories as $category)
<option value="{{$category->id}}" >{{$category->name}}</option>
@endforeach
</select>
@error('categoryid') <span class="text-danger er">{{ $message}}</span>@enderror




</div>

</div>

<div class="col-sm-12 col-md-8">

<div class="form-group custom-file">
    <input type="file"  class="custom-file-input form-control" wire:model="image"
    accept ="image/x-png, image/gif, image/jpeg"
    >
    <label class="custom-file-label">Imagen {{$image}}</label>
    @error('alerts') <span class="text-danger er">{{ $message}}</span>@enderror

    
</div>
</div>


</div>

@include('common.modalFooter')

