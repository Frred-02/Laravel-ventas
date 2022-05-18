<script>
   $(' .tblscroll').nicescroll({

        cursoscolor: "#515365",
        cursorwidth:"30px",
        background: "rgba(20,20,20,0,3)",
        cursorborder: "0px",
        cursorborderradius:3

   })

   function Confirm(id,eventName,  text) {
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
                window.livewire.emit('eventName', id)
                Swal.close()
            }
        })
    }

    </script>