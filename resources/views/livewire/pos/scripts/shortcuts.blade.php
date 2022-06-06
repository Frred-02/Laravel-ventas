   <script>      

   var listener = new window.keypress.listener();
listener.simple_combo("F9", function(){
           console.log("F9");
    
           livewire.emit('saveSale')
})

     listener.simple_combo("f8", function() {
           document.getElementById('cash').value =''
           document.getElementById('cash').focus()
})

listener.simple_combo("f4", function() {
           var total = parsefloat(document.getElementById('hiddenTotal').value)
           if(total > 0){
            Confirm(0, 'clearCart' , 'seguro de elimianar carrito')

               
           }else
           {

            noty('agrega produdctos ala ventas')

           }
})
</script>
