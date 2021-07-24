<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        User::create([
            'email' => 'admin@admin.com',
            'first_name' => 'admin',
            'last_name' => 'admin',
            'address'=>'monastir',
            'phone' => '52985080',
            'password' =>bcrypt('admin'),
            'role' =>  'admin',
            'status'=>true,
            'photo'=>'https://www.e-xpertsolutions.com/wp-content/plugins/all-in-one-seo-pack/images/default-user-image.png'
        ]);


    }
}
