<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ErrorCategory;

class ErrorCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ErrorCategory::create([
            'category_name' => 'Categoría A',
            'description' => 'Circunstancias o incidentes capaces de causar un error'
        ]);
        ErrorCategory::create([
            'category_name' => 'Categoría B',
            'description' => 'El error ocurrió, pero no alcanzó al paciente'
        ]);
        ErrorCategory::create([
            'category_name' => 'Categoría C',
            'description' => 'El error alcanzó al paciente, pero no le causó daño'
        ]);
        ErrorCategory::create([
            'category_name' => 'Categoría D',
            'description' => 'El error alcanzó al paciente pero no le causó daño, sin embargo requirió de monitorización para comprobar que no sufrió daño'
        ]);
        ErrorCategory::create([
            'category_name' => 'Categoría E',
            'description' => 'El error contribuyó o causó daño temporal al paciente y requirió intervención'
        ]);
        ErrorCategory::create([
            'category_name' => 'Categoría F',
            'description' => 'El error contribuyó o causó daño temporal al paciente y requirió o prolongó la hospitalización'
        ]);
        ErrorCategory::create([
            'category_name' => 'Categoría G',
            'description' => 'El error contribuyó o causó daño permanente al paciente'
        ]);
        ErrorCategory::create([
            'category_name' => 'Categoría H',
            'description' => 'El error comprometió la vida del paciente y se requirió intervención para mantener su vida'
        ]);
        ErrorCategory::create([
            'category_name' => 'Categoría I',
            'description' => 'El error contribuyó o causó la muerte del paciente'
        ]);
    }
}
