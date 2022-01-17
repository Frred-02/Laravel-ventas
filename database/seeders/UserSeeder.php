<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create ([
            'name'=>'fred',
            'phone'=> '124578',
            'email'=> 'fred@hotmail.com',
            'profile'=> 'ADMIN',
            'status'=> 'ACTIVE',
            'password'=> bcrypt ('123')
   
           ]);
   
   
           User::create ([
               'name'=>'EMPLEADO',
               'phone'=> '124578',
               'email'=> 'kerneld@hotmail.com',
               'profile'=> 'EMPLOYEE',
               'status'=> 'ACTIVE',
               'password'=> bcrypt ('12')
      
              ]);
    }
}
