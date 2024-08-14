<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReportTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('report_templates')->insert([
            'template_text' => '<div> Sample template text with %%KEYWORD%%. </div>',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
