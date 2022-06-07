<div class="row sales layout-top-spacing">

	<div class="col-sm-12 ">
		<div class="widget widget-chart-one">
			<div class="widget-heading">
				<h4 class="card-title"><b>{{$componentName}}</b>
            </h4>			
				
			</div>			
					
			<div class="widget-content">	
                
            
                  <div class="form inline">
                       <div class="form-group mr-5">
                           <select wire:model = "role" class="form-control"></select>
                           <option value="Elegir
                           "selected >==Selecciona el Role ==></option>
                           @foreach($roles  as role)
                           <option value="{{$role->id}}">{{$role->name}} </option>
                           @endforeach
                       </div>

                       <button wire:click.prevent="SyncAll()" type="button" class="btn btn-dark mbmobile
					    inblock mr-5">Sincronizar todos</button>

						<button onclick="Revocar()" type="button" class="btn btn-dark mbmobile
					    mr-5">Revocar todos</button>
                  </div>

				

					
                       <div class="row mt-3">


                         <div class="col-sm-12">


						 <div class="table-responsive">
					<table  class="table table-bordered table-striped  mt-1">
						<thead class="text-white" style="background: #3B3F5C">
							<tr>
								<th class="table-th text-white">ID</th>	
								<th class="table-th text-center text-white">PERMISO</th>
								<th class="table-th text-center text-white">ROLES CON PERMISO</th>
							</tr>
						</thead>
						<tbody>						
							<tr>
								

							</tr>

							
						</tbody>
					</table>
					pagination

					

				</div>



						 </div>

				        </div>
				

				

			</div>
		</div>
	</div>
	include form
</div>





<script>
	document.addEventListener('DOMContentLoaded', function () {  

		//events
		

		

	})


	
</script>
