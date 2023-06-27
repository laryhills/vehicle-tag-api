<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TagsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create tags
        $tagsToCreate = [
           [
                'tag_no' => 'WERGYH',
                'staff_name' => 'Daniel Belza',
                'email' => 'daniel@bible.com',
                'phone' => '9026901600',
                'appointment' => 'Wiseman',
                'department' => 'Kings Palace',
                'address' => 'Babylon',
                'vehicle_type' => 'Carriage',
                'vehicle_model' => 'Carriage 2021',
                'vehicle_color' => 'Gold',
                'vehicle_plate_no' => 'OID-45ID',
                'vehicle_chasis_no' => '2345677',
                'authorized_staff_name' => 'Hilary',
                'authorized_staff_appointment' => 'Intern',
                'slug' => 'wergyh',
            ],
            [
                'tag_no' => 'GHFYU098',
                'staff_name' => 'Gabriel Geeks',
                'email' => 'gab@colabs.com',
                'phone' => '08059584934',
                'appointment' => 'Lecturer',
                'department' => 'Computer Scicence',
                'address' => 'Colabs, Kaduna',
                'vehicle_type' => 'Brabus',
                'vehicle_model' => 'Brabus GT-800',
                'vehicle_color' => 'Black',
                'vehicle_plate_no' => 'POIJ-9JEU',
                'vehicle_chasis_no' => '123456789',
                'authorized_staff_name' => 'Hilary',
                'authorized_staff_appointment' => 'Director',
                'slug' => 'ghfyu098',
            ],
            [
                'tag_no' => 'LLODJ090',
                'staff_name' => 'Jessy',
                'email' => 'jessy@example.com',
                'phone' => '09026901600',
                'appointment' => 'Director',
                'department' => 'SECURITY',
                'address' => 'Dantata Estate, Goni Gora',
                'vehicle_type' => 'Ferrari',
                'vehicle_model' => 'Ferrari 999',
                'vehicle_color' => 'Red',
                'vehicle_plate_no' => 'GJDIJ-JR78',
                'vehicle_chasis_no' => '123456789',
                'authorized_staff_name' => 'Hilary',
                'authorized_staff_appointment' => 'Boss',
                'slug' => 'yujgoll',                  
           ] 
            ];
        foreach($tagsToCreate as $tag){
            \App\Models\Tag::firstOrCreate($tag);
        }

    }
}
