<script>


try {

    onScan.attachTo(document , {
    suffixkeyCodes: [13],
    onScan: function(barcode){
        console.log(barcode)
        window.livewire.emit('scan-code',barcode)
    },
    onScanError:function(e){
        console.log(e)
    }
})
    consola.log('Scanner ready')
    
} catch (e) {
    

    consola.log('error de lectura' ,e)
}


    </script>