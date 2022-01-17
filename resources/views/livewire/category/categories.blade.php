
<div class="row sales layout-top-spacing">
    <div class="col-sm-12">
        <div class="widget widget-chart-one">
            <div class="widget-heading">
                <h4 class="card-title">
                    <b>{{$componentName}} | {{$pageTitle}}</b>
                </h4>
                <ul class="tabs tab-pills">
                    <li>
                        <a href="javascript:void(0)"
                        class="tabmenu bg-dark" data-toggle="modal" data-target="#theModal">Agregar Registro</a>
                    </li>
                </ul>
            </div>
        
            <!--comentario agregamos nuestro buscador -->
            @include('common.searchBox')
            <div class="widget-content">
                <div class="table-responsive">
                    <table class="table  table-bordered table striped mt-1">
                    <thead class="text-white" style="background:#3B3F5C">

                        <tr>
                      <th class="table-th text-white">DESCRIPCION</th>
                      <th class="table-th text-white text-center">IMAGEN</th>
                       <th class="table-th text-white  text-center">ACTIONS</th>
                      
                    

                        </tr>

                           </thead>

                        <body>
                            @foreach($categories as $category)
                            <tr>
                                <!--comentario-->
                                <td><h6>{{$category->name}}</h6></td>

                                
                                <td class="text-center">
                                    <span>
                                        <img src="{{ asset('storage/categories/' .$category->imagen) }}" alt="imagen de ejemplo" height="70" width="80" class="rounded">
                                    </span>
                                
                                <td class="text-center">
                                    <a href="javascript:void(0)"
                                    wire:click.prevent="Edit({{ $category->id }})"
                                    class="btn btn-info mtmobile" title="Edit">
                                    <i class ="fas fa-edit"> </i>
                                    </a>
                                    

                                    
                                    @if($category->products->count() < 1 )

                                    <a href="javascript:void(0)"
                                    
                                     onclick="Confirm('{{$category->id}}')"
                                    class="btn btn-danger" title="Delete">
                                    <i class ="fas fa-trash"> </i>
                                    </a>  
                                    @endif
                                   
                                    
                                    
                                 
                                </td>
                            </tr>
                            @endforeach
                        </body>
                    </table>
                    {{ $categories->links() }}
                    <!--php artisan livewire:publish --pagination-->
                
                </div>
            </div>
        </div>
    </div>
    <!--comentario -->
    @include('livewire.category.form')
</div>

<script>
    document.addEventListener('DOMContentLoaded', function(){
        
        window.livewire.on('show-modal' , msg =>{
            $('#theModal').modal('show')
        });
        window.livewire.on('category-added' , msg =>{
            $('#theModal').modal('hide')
        });
         window.livewire.on('category-updated' , msg =>{
            $('#theModal').modal('hide')
        });   

        $(`#theModal`).on('hidden.bs.modal',function (e){
    $('.er').css('display','none')
        })     
        
    });

</script>

<script type="text/javascript">
    function Confirm(id,products) {
        if(products > 0)
        {
            swal ('nose puede eliminar estan relacionado')
            return;
        }
        // body...        
        Swal({
            title: 'CONFIRMAR BORRADO',
            text: '¿Está Seguro de Querer Eliminar la Categoria Seleccionada?',
            type: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Cerrar',
            cancelButtonColor: '#fff',
            confirmButtonColor: '#3B3F5C',
            confirmButtonText: 'Aceptar'
        }).then(function(result) {
            // body...
            if(result.value){
                window.livewire.emit('deleteRow', id)
                Swal.close()
            }
        })
    }
</script>




