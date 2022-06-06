<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\RoLe;

use Livewire\withPagination;
use App\Models\User;
use DB;

class RolesController extends Component
{
    use withPagination;
     

     public $roleName ,$search, $selected_id,$pageTitle, $componentName ;
     private $pagination =5;

public function PaginationView()
 
{
    return  'vendor.livewire.bootstrap';
}

      public function mount()
      {
          $this->pageTitle ='LISTADO';
          $this->componentName ='Roles';

      }


    public function render()
    {

       if(strLen($this->search)> 0)
       $roles = RoLe:: where('name','like', '%' . $this->search . '%')->paginate($this->pagination);

       else
           $roles = RoLe::orderBy('name','asc')->paginate($this->pagination);



        return view('livewire.roles.component',[
            'roles' => $roles
        ]) 
        ->extends('layouts.theme.app')
        ->section('content');
    }

public function CreateRole()
{
    $rules =['roleName'=> 'required|min:2|unique:roles,name'];
    $messages =['roleName.required'=> 'el nombre del role es requerido',
            'roleName.unique'=> 'el role ya existe',
           'roleName.min'=> 'el nombre del role debe tener al menos 2 caracteres'    
        ];

        $this->validate ($rules , $messages);
        RoLe::create (['name'=> $this->roleName]);

        $this->emit('role-added' , 'se registro el role con exito');
        $this->resetUi();

}

public function Edit(RoLe $role)
{
    //$role =Role::find($id);
    $this->selected_id = $role->id;
    $this->roleName= $role->name;

    $this->emit('show-modal','Show modal');
}

public function UpdateRole()
{


     $rules = ['roleName'=> 'required|min:2|unique:roles,name, {$this-selected_id}'];

    $messages =['roleName.required'=> 'el nombre del role es requerido',
            'roleName.unique'=> 'el role ya existe',
           'roleName.min'=> 'el nombre del role debe tener al menos 2 caracteres'    
        ];

        $this->validate($rules, $messages);
        $role = RoLe::find($this->selected_id);
        $role->name = $this->roleName;
        $role->save();

        $this->emit('role-update' , 'se actualizo con exito');
        $this->resetUi();



}

     protected $listeners =['destroy'=> 'Destroy'];

     public function Destroy ($id)
     {
         $permissionsCount = RoLe ::find ($id)->permissions->count();
         if($permissionsCount  > 0)
         {
             $this->emit('role-error', 'nose se puede elimimar carajo no tienes permiso asociados');
             return;
         }

         RoLe::find($id)->delete();
         $this->emit('role-delete', 'se elemino con exito');
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
          $this->roleName='';
          $this->search = '';
          $this->selected_id =0;
          $this->resetValidation();
      }


}
