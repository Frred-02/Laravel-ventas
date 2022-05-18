   <script>      

   var listener = new window.Keypress.Listener();
listener.simple_combo("f9", function(){
    
           livewire.emit('saveSale')
})

     listener.simple_combo("f8", function() {
           document.getElementById('cash').value =''
           document.getElementById('cash').focus()
})

listener.simple_combo("f4", function() {
           var total = parseFloat(document.getElementById('hiddenTotal').value)
           if(total > 0){
               Confirm(0, 'clearCart' , 'seguro de elimianar carrito')

               
           }else
           {

            noty('agrega produdctos ala ventas')

           }
})
</script>
