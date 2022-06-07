   <script>      

   var listener = new window.keypress.Listener();
listener.simple_combo("f9", function(){
           console.log("f9");
    
           livewire.emit('saveSale')
})

     listener.simple_combo("f8", function() {
           document.getElementById('cash').value =''
           document.getElementById('cash').focus()
           document.getElementById('hidenTotal').value =''
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
