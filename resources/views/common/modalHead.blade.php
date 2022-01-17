{{-- wire:ignore.self para evitar que la ventana se cierre --}}
{{-- data-keyboard="false" = para evitar que usen el escape --}}
<div wire:ignore.self class="modal fade" id="theModal" tabindex="-1" data-keyboard="false" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white"><b>{{ $componentName }}</b> |
                    {{ $selected_id > 0 ? 'EDITAR' : 'CREAR' }}
                </h5>
                <h6 class="text-center text-warning" wire:loading></h6> {{-- wire:loading = cuando se este cargando --}}
            </div>
            <div class="modal-body">