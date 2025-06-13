<?php

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Admin::where('username', 'superadmin')->first();

        if (is_null($admin)) {
            $admin           = new Admin();
            $admin->name     = "SUPER ADMIN";
            $admin->email    = "augusto.yepez@sppat.gob.ec";
            $admin->username = "superadmin";
            $admin->password = Hash::make('admin123456*');
            $admin->save();
        }

        $admin = Admin::where('username', 'diana.betun')->first();

        if (is_null($admin)) {
            $admin           = new Admin();
            $admin->name     = "DIANA BETUN";
            $admin->email    = "diana.betun@sppat.gob.ec";
            $admin->username = "diana.betun";
            $admin->password = Hash::make('diana.betun123');
            $admin->save();
        }

        $admin = Admin::where('username', 'gabriela.romero')->first();

        if (is_null($admin)) {
            $admin           = new Admin();
            $admin->name     = "GABRIELA ROMERO";
            $admin->email    = "gabriela.romero@sppat.gob.ec";
            $admin->username = "gabriela.romero";
            $admin->password = Hash::make('gabriela.romero123');
            $admin->save();
        }

        $admin = Admin::where('username', 'gilda.saker')->first();

        if (is_null($admin)) {
            $admin           = new Admin();
            $admin->name     = "GILDA SAKER";
            $admin->email    = "gilda.saker@sppat.gob.ec";
            $admin->username = "gilda.saker";
            $admin->password = Hash::make('gilda.saker123');
            $admin->save();
        }

        $admin = Admin::where('username', 'mateo.alarcon')->first();

        if (is_null($admin)) {
            $admin           = new Admin();
            $admin->name     = "MATEO ALARCON";
            $admin->email    = "mateo.alarcon@sppat.gob.ec";
            $admin->username = "mateo.alarcon";
            $admin->password = Hash::make('mateo.alarcon123');
            $admin->save();
        }

        $admin = Admin::where('username', 'mercedes.tapia')->first();

        if (is_null($admin)) {
            $admin           = new Admin();
            $admin->name     = "MERCEDES TAPIA";
            $admin->email    = "mercedes.tapia@sppat.gob.ec";
            $admin->username = "mercedes.tapia";
            $admin->password = Hash::make('mercedes.tapia123');
            $admin->save();
        }

        $admin = Admin::where('username', 'nicole.riofrio')->first();

        if (is_null($admin)) {
            $admin           = new Admin();
            $admin->name     = "NICOLE RIOFRIO";
            $admin->email    = "nicole.riofrio@sppat.gob.ec";
            $admin->username = "nicole.riofrio";
            $admin->password = Hash::make('nicole.riofrio123');
            $admin->save();
        }

        $admin = Admin::where('username', 'rebeca.serrano')->first();

        if (is_null($admin)) {
            $admin           = new Admin();
            $admin->name     = "REBECA SERRANO";
            $admin->email    = "rebeca.serrano@sppat.gob.ec";
            $admin->username = "rebeca.serrano";
            $admin->password = Hash::make('rebeca.serrano123');
            $admin->save();
        }

        $admin = Admin::where('username', 'sandra.caiza')->first();

        if (is_null($admin)) {
            $admin           = new Admin();
            $admin->name     = "SANDRA CAIZA";
            $admin->email    = "sandra.caiza@sppat.gob.ec";
            $admin->username = "sandra.caiza";
            $admin->password = Hash::make('sandra.caiza123');
            $admin->save();
        }

        $admin = Admin::where('username', 'jaime.aguilar')->first();

        if (is_null($admin)) {
            $admin           = new Admin();
            $admin->name     = "JAIME AGUILAR";
            $admin->email    = "jaime.aguilar@sppat.gob.ec";
            $admin->username = "jaime.aguilar";
            $admin->password = Hash::make('jaime.aguilar123');
            $admin->save();
        }

        $admin = Admin::where('username', 'camila.toala')->first();

        if (is_null($admin)) {
            $admin           = new Admin();
            $admin->name     = "CAMILA TOALA";
            $admin->email    = "camila.toala@sppat.gob.ec";
            $admin->username = "camila.toala";
            $admin->password = Hash::make('camila.toala123');
            $admin->save();
        }

        $admin = Admin::where('username', 'silvia.cisneros')->first();

        if (is_null($admin)) {
            $admin           = new Admin();
            $admin->name     = "SILVIA CISNEROS";
            $admin->email    = "silvia.cisneros@sppat.gob.ec";
            $admin->username = "silvia.cisneros";
            $admin->password = Hash::make('silvia.cisneros123');
            $admin->save();
        }

        $admin = Admin::where('username', 'jose.garcia')->first();

        if (is_null($admin)) {
            $admin           = new Admin();
            $admin->name     = "JOSÉ GARCÍA";
            $admin->email    = "jose.garcia@sppat.gob.ec";
            $admin->username = "jose.garcia";
            $admin->password = Hash::make('jose.garcia123');
            $admin->save();
        }

        $admin = Admin::where('username', 'elsa.romero')->first();

        if (is_null($admin)) {
            $admin           = new Admin();
            $admin->name     = "ELSA ROMERO";
            $admin->email    = "elsa.romero@sppat.gob.ec";
            $admin->username = "elsa.romero";
            $admin->password = Hash::make('elsa.romero123');
            $admin->save();
        }

        $admin = Admin::where('username', 'alejandro.ortega')->first();

        if (is_null($admin)) {
            $admin           = new Admin();
            $admin->name     = "ALEJANDRO ORTEGA";
            $admin->email    = "alejandro.ortega@sppat.gob.ec";
            $admin->username = "alejandro.ortega";
            $admin->password = Hash::make('alejandro.ortega123');
            $admin->save();
        }

        $admin = Admin::where('username', 'jaime.aguilera')->first();

        if (is_null($admin)) {
            $admin           = new Admin();
            $admin->name     = "JAIME AGUILERA";
            $admin->email    = "jaime.aguilera@sppat.gob.ec";
            $admin->username = "jaime.aguilera";
            $admin->password = Hash::make('jaime.aguilera123');
            $admin->save();
        }

        $admin = Admin::where('username', 'gianella.rodriguez')->first();

        if (is_null($admin)) {
            $admin           = new Admin();
            $admin->name     = "GIANELLA RODRIGUEZ";
            $admin->email    = "gianella.rodriguez@sppat.gob.ec";
            $admin->username = "gianella.rodriguez";
            $admin->password = Hash::make('gianella.rodriguez123');
            $admin->save();
        }

        $admin = Admin::where('username', 'juan.vasconez')->first();

        if (is_null($admin)) {
            $admin           = new Admin();
            $admin->name     = "JUAN VASCONEZ";
            $admin->email    = "juan.vasconez@sppat.gob.ec";
            $admin->username = "juan.vasconez";
            $admin->password = Hash::make('juan.vasconez123');
            $admin->save();
        }

        $admin = Admin::where('username', 'napoleon.proano')->first();

        if (is_null($admin)) {
            $admin           = new Admin();
            $admin->name     = "NAPOLEON PROAÑO";
            $admin->email    = "napoleon.proano@sppat.gob.ec";
            $admin->username = "napoleon.proano";
            $admin->password = Hash::make('napoleon.proano123');
            $admin->save();
        }

        $admin = Admin::where('username', 'andrea.proano')->first();

        if (is_null($admin)) {
            $admin           = new Admin();
            $admin->name     = "ANDREA PROAÑO";
            $admin->email    = "andrea.proano@sppat.gob.ec";
            $admin->username = "andrea.proano";
            $admin->password = Hash::make('andrea.proano123');
            $admin->save();
        }

        $admin = Admin::where('username', 'diana.burbano')->first();

        if (is_null($admin)) {
            $admin           = new Admin();
            $admin->name     = "DIANA BURBANO";
            $admin->email    = "diana.burbano@sppat.gob.ec";
            $admin->username = "diana.burbano";
            $admin->password = Hash::make('diana.burbano123');
            $admin->save();
        }
    }
}
