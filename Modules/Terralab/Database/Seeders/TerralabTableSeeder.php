<?php

namespace Modules\Terralab\Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Arr;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Modules\Catalog\Entities\CatalogEntry;
use Modules\Directory\Entities\CatalogLaboratory;
use Modules\Terralab\Entities\OrderTest;
use Modules\Terralab\Entities\OrderTestOriginalResult;
use Modules\Terralab\Entities\OrderTestResult;
use Modules\Terralab\Entities\PatientLaboratoryOrder;

class TerralabTableSeeder extends Seeder
{
    public $faker;

    public function __construct(Faker $faker)
    {
        $this->faker = $faker;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $doctors = User::where(['role_id' => Role::DOCTOR_ROLE_ID])->get();

        DB::transaction(function () use ($doctors) {
            foreach ($doctors as $doctor) {
                $patient = User::InRandomOrder()->where(['role_id' => Role::PATIENT_ROLE_ID])->first();
                $laboratories = CatalogLaboratory::InRandomOrder()->get();
                $this->createLaboratoryOrders($doctor, $patient, $laboratories);
            }
        });
    }

    private function createLaboratoryOrders(User $doctor, User $patient, $laboratories)
    {
        foreach($laboratories as $laboratory) {
            for ($i = 1; $i <= rand(1, 5); $i++) {
                $patientLaboratoryOrder = PatientLaboratoryOrder::create([
                    'patient_id' => $patient->id,
                    'laboratory_id' => $laboratory->id,
                    'doctor_id' => $doctor->id,
                    'menstrphase' => $this->faker->randomElement(array_keys(PatientLaboratoryOrder::SELECTOR_DATA['menstrphases'])),
                    'descr'=> $this->faker->sentence(),
                    'order_number' => $this->faker->randomNumber(5, false),
                    'order_status' => Arr::random(PatientLaboratoryOrder::getAvailableStatuses())
                ]);

                $indicators = ["30610", "30611", "30626"];
                $this->createOrderTests($patientLaboratoryOrder, $indicators);
            }
        }

    }

    private function createOrderTests(PatientLaboratoryOrder $patientLaboratoryOrder, $indicators)
    {
        foreach ($indicators as $indicator) {
            $ready = $this->faker->boolean();
            $pdf = ($ready === true ? $this->faker->boolean() : false);

            $orderTest = OrderTest::create([
                'order_laboratory_id' => $patientLaboratoryOrder->id,
                'indicator_id' => $indicator,
                'name' => $this->faker->word(),
                'material' => $this->faker->word(),
                'ready' => $ready,
                'pdf' => $pdf
            ]);
            $this->createOrderTestResults($orderTest);
            $this->createOrderTestOriginalResults($orderTest);
        }
    }

    private function createOrderTestResults(OrderTest $orderTest)
    {
        for ($i = 1; $i <= rand(1, 5); $i++) {
            OrderTestResult::create([
                'order_test_id' => $orderTest->id,
                'material_name' => $this->faker->word(),
                'test_name' => $this->faker->word(),
                'test_code' => $orderTest->indicator_id,
                'gis' => null,
                'indicator_name' => $this->faker->word(),
                'unit_name' => $this->faker->word(),
                'result' => $this->faker->randomFloat(2, 5, 30),
                'norm_text' => $this->faker->word(),
                'ubnormal_flag' => 0,
                'payload' => null,
                'order' => 1,
                'date_ready' => $this->faker->date(),
                'done_employee_name' => $this->faker->name(),
                'done_employee_id' => $this->faker->uuid(),
                'done_employee_post' => $this->faker->jobTitle()
            ]);
        }
    }

    private function createOrderTestOriginalResults(OrderTest $orderTest)
    {
        for ($i = 1; $i <= rand(1, 3); $i++) {
            OrderTestOriginalResult::create([
                'order_test_id' => $orderTest->id,
                'uri' => $this->faker->url()
            ]);
        }
    }
}
