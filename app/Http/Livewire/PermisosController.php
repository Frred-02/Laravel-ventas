<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\RoLe;

use Livewire\withPagination;
use App\Models\User;
use DB;

class PermisosController extends Component
{
    use withPagination;
     

     public $permissionName ,$search, $selected_id,$pageTitle, $componentName ;
     private $pagination =10;

public function PaginationView()
 
{
    return  'vendor.livewire.bootstrap';
}

      public function mount()
      {
          $this->pageTitle ='LISTADO';
          $this->componentName ='Permisos';

      }


    public function render()
    {

       if(strLen($this->search)> 0)
       $permisos = Permission:: where('name','like', '%' . $this->search . '%')->paginate($this->pagination);

       else
           $permisos  = Permission::orderBy('name','asc')->paginate($this->pagination);



        return view('livewire.permisos.component',[
            'permisos' => $permisos
        ]) 
        ->extends('layouts.theme.app')
        ->section('content');
    }

public function CreatePermission()
{
    $rules =['permissionName'=> 'required|min:2|unique:permissions,name'];
    $messages =['permissionName.required'=> 'el nombre del permiso es requerido',
            'permissionName.unique'=> 'el permiso ya existe',
           'permissionName.min'=> 'el nombre del role debe tener al menos 2 caracteres'    
        ];

        $this->validate ($rules , $messages);
        Permission::create (['name'=> $this->permissionName]);

        $this->emit('permiso -added' , 'se registro el permiso con exito');
        $this->resetUi();

}

public function Edit(Permission  $permiso)
{
    //$role =Role::find($id);
    $this->selected_id = $permiso->id;
    $this->permissionName= $permiso->name;

    $this->emit('show-modal','Show modal');
}

public function UpdatePermission()
{


     $rules = ['permissionName'=> 'required|min:2|unique:roles,name, {$this-selected_id}'];

    $messages =['permissionName.required'=> 'el nombre del permiso es requerido',
            'permissionName.unique'=> 'el permiso ya existe',
           'permissionName.min'=> 'el nombre del permiso debe tener al menos 2 caracteres'    
        ];

        $this->validate($rules, $messages);
        $permiso = Permission::find($this->selected_id);
        $permiso->name = $this->permissionName;
        $permiso->save();

        $this->emit('permiso-update' , 'se actualizo con exito');
        $this->resetUi();



}

     protected $listeners =['destroy'=> 'Destroy'];

     public function Destroy ($id)
     {
         $rolesCount = Permission ::find ($id)->getRoleNames()->count();
         if($rolesCount  > 0)
         {
             $this->emit('permiso-error', 'nose se puede elimimar carajo no tienes permiso asociados');
             return;
         }

         Permission::find($id)->delete();
         $this->emit('permiso-delete', 'se elemino con exito');
             return;
     }
     
      public function AsignarRoles($rolesList)

      {
          if($this->userSelected > 0)
          {
              $user = User::find ($this->userSelected);
              if($user){
              $user->syncRoles($rolesList);
              $this->emit('msg-ok','Roles asignados correctamente');
              $this->resetImput();


              }
          }
      }


      public function resetUI()
      {
          $this->permissionName='';
          $this->search = '';
          $this->selected_id =0;
          $this->resetValidation();
      }


}

