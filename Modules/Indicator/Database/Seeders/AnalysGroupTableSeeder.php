<?php

namespace Modules\Indicator\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Indicator\Entities\AnalysGroup;
use Modules\Directory\Entities\CatalogAnalys;
use Modules\Directory\Entities\CatalogIndicator;

class AnalysGroupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        \DB::transaction(function () {
            $this->createGeneralComprehensiveAnalysOfVenousBlood();
            $this->createGeneralUrineAnalys();
            $this->createHepaticComplex();
            $this->createLipidСomplex();
            $this->createRenalSamples();
            $this->createHOMAIRindex();
            $this->createRheumatoidComplex();
            $this->createThyroidScreening();
            $this->create25ОНVitaminD();
            $this->createTotalTestosterone();
            $this->createFreeTestosterone();
            $this->createHomocysteine();
            $this->createFerritin();
            $this->createTotalHelminthAntibodies();
            $this->createProgesterone();
            $this->createCortisol();
            $this->createEstradiol();
            $this->createTotalPSA();
            $this->createPSA();
            $this->createProlactin();
            $this->createIGF1();
        });
    }


    public function createGeneralComprehensiveAnalysOfVenousBlood()
    {
        $analys = CatalogAnalys::whereRaw("JSON_EXTRACT(name, '$.en') = 'General comprehensive analysis of venous blood with ESR and leukocyte blood formula'")->first();
        $indicators = CatalogIndicator::whereRaw("JSON_EXTRACT(name, '$.en') = 'Neutrophils % (Neu)'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'Leukocytes (WBC)'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'Neutrophils absolute number (Neu)'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'Lymphocytes absolute number (Lym)'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'Monocytes absolute number (Mon)'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'Eosinophils absolute number (Eos)'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'Basophils absolute number (Bas)'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'Erythrocytes (RBC)'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'Hematocrit (HCT)'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'Mean content of hemoglobin in erythrocyte (MCH)'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'Monocytes % (Mon)'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'Lymphocytes % (Lym)'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'Basophils % (Bas)'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'Hemoglobin (HGB)'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'Mean corpuscular volume (MCV)'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'Mean corpuscular hemoglobin concentration (MCHC)'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'Red cell distribution width SD (RDW-SD)'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'Red cell distribution width CV (RDW-CV)'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'Platelets (PLT)'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'Mean Platelet Volume (MPV)'")
            ->get();

        foreach ($indicators as $indicator) {
            AnalysGroup::create([
                'indicator_id' => $indicator->id,
                'analys_id' => $analys->id
            ]);
        }
    }

    public function createGeneralUrineAnalys()
    {
        $analys = CatalogAnalys::whereRaw("JSON_EXTRACT(name, '$.en') = 'General urine analysis'")->first();
        $indicators = CatalogIndicator::whereRaw("JSON_EXTRACT(name, '$.en') = 'Number'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'Color'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'Transparency'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'Ketones'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'Nitrites'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'Urobilinogen'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'Bilirubin'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'Protein'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'Glucose'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'Relative density'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'рН'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'Flat epithelium cells'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'Transitional epithelial cells'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'Kidney epithelial cells'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'Leukocytes'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'Unchanged erythrocytes '")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'Weakly changed erythrocytes '")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'Changed erythrocytes '")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'Cylinders'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'Mucus'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'Elements of yeast-like fungus'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'Bacteria'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'Salt'")
            ->get();

        foreach ($indicators as $indicator) {
            AnalysGroup::create([
                'indicator_id' => $indicator->id,
                'analys_id' => $analys->id
            ]);
        }
    }

    public function createHepaticComplex()
    {
        $analys = CatalogAnalys::whereRaw("JSON_EXTRACT(name, '$.en') = 'Hepatic complex'")->first();
        $indicators = CatalogIndicator::whereRaw("JSON_EXTRACT(name, '$.en') = 'DeRitis coefficient 1.13 CALC'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'Total bilirubin'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'Direct bilirubin'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'Indirect bilirubin'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'AST'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'ALT'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'AP'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'GGT'")
            ->get();

        foreach ($indicators as $indicator) {
            AnalysGroup::create([
                'indicator_id' => $indicator->id,
                'analys_id' => $analys->id
            ]);
        }
    }

    public function createLipidСomplex()
    {
        $analys = CatalogAnalys::whereRaw("JSON_EXTRACT(name, '$.en') = 'Lipid complex'")->first();
        $indicators = CatalogIndicator::whereRaw("JSON_EXTRACT(name, '$.en') = 'Triglycerides'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'High-density lipoproteins'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'Low-density lipoproteins'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'Lipoproteins of very low density'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'Atherogenicity coefficient'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'Total cholesterol'")
            ->get();

        foreach ($indicators as $indicator) {
            AnalysGroup::create([
                'indicator_id' => $indicator->id,
                'analys_id' => $analys->id
            ]);
        }
    }

    public function createRenalSamples()
    {
        $analys = CatalogAnalys::whereRaw("JSON_EXTRACT(name, '$.en') = 'Renal samples (creatinine, urea, uric acid)'")->first();
        $indicators = CatalogIndicator::whereRaw("JSON_EXTRACT(name, '$.en') = 'Urea'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'Uric acid'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'Urea nitrogen'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'Serum creatinine'")
            ->get();

        foreach ($indicators as $indicator) {
            AnalysGroup::create([
                'indicator_id' => $indicator->id,
                'analys_id' => $analys->id
            ]);
        }
    }

    public function createHOMAIRindex()
    {
        $analys = CatalogAnalys::whereRaw("JSON_EXTRACT(name, '$.en') = 'HOMA-IR index (blood glucose x insulin /22.5)'")->first();
        $indicators = CatalogIndicator::whereRaw("JSON_EXTRACT(name, '$.en') = 'Insulin'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'Blood glucose'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'HOMA-index'")
            ->get();

        foreach ($indicators as $indicator) {
            AnalysGroup::create([
                'indicator_id' => $indicator->id,
                'analys_id' => $analys->id
            ]);
        }
    }

    public function createRheumatoidComplex()
    {
        $analys = CatalogAnalys::whereRaw("JSON_EXTRACT(name, '$.en') = 'Rheumatoid complex'")->first();
        $indicators = CatalogIndicator::whereRaw("JSON_EXTRACT(name, '$.en') = 'Rheumatoid factor (RF)'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'C-reactive protein'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'Albumin'")
            ->get();

        foreach ($indicators as $indicator) {
            AnalysGroup::create([
                'indicator_id' => $indicator->id,
                'analys_id' => $analys->id
            ]);
        }
    }

    public function createThyroidScreening()
    {
        $analys = CatalogAnalys::whereRaw("JSON_EXTRACT(name, '$.en') = 'Thyroid screening: TSH, free T3, free T4'")->first();
        $indicators = CatalogIndicator::whereRaw("JSON_EXTRACT(name, '$.en') = 'TSH'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'Free T3'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'Free T4'")
            ->get();

        foreach ($indicators as $indicator) {
            AnalysGroup::create([
                'indicator_id' => $indicator->id,
                'analys_id' => $analys->id
            ]);
        }
    }

    public function create25ОНVitaminD()
    {
        $analys = CatalogAnalys::whereRaw("JSON_EXTRACT(name, '$.en') = '25 ОН Vitamin D'")->first();
        $indicators = CatalogIndicator::whereRaw("JSON_EXTRACT(name, '$.en') = '25 ОН Vitamin D'")
            ->get();

        foreach ($indicators as $indicator) {
            AnalysGroup::create([
                'indicator_id' => $indicator->id,
                'analys_id' => $analys->id
            ]);
        }
    }

    public function createTotalTestosterone()
    {
        $analys = CatalogAnalys::whereRaw("JSON_EXTRACT(name, '$.en') = 'Total testosterone'")->first();
        $indicators = CatalogIndicator::whereRaw("JSON_EXTRACT(name, '$.en') = 'Total testosterone'")
            ->get();

        foreach ($indicators as $indicator) {
            AnalysGroup::create([
                'indicator_id' => $indicator->id,
                'analys_id' => $analys->id
            ]);
        }
    }

    public function createFreeTestosterone()
    {
        $analys = CatalogAnalys::whereRaw("JSON_EXTRACT(name, '$.en') = 'Free testosterone'")->first();
        $indicators = CatalogIndicator::whereRaw("JSON_EXTRACT(name, '$.en') = 'Free testosterone'")
            ->get();

        foreach ($indicators as $indicator) {
            AnalysGroup::create([
                'indicator_id' => $indicator->id,
                'analys_id' => $analys->id
            ]);
        }
    }

    public function createHomocysteine()
    {
        $analys = CatalogAnalys::whereRaw("JSON_EXTRACT(name, '$.en') = 'Homocysteine'")->first();
        $indicators = CatalogIndicator::whereRaw("JSON_EXTRACT(name, '$.en') = 'Homocysteine'")
            ->get();

        foreach ($indicators as $indicator) {
            AnalysGroup::create([
                'indicator_id' => $indicator->id,
                'analys_id' => $analys->id
            ]);
        }
    }

    public function createFerritin()
    {
        $analys = CatalogAnalys::whereRaw("JSON_EXTRACT(name, '$.en') = 'Ferritin'")->first();
        $indicators = CatalogIndicator::whereRaw("JSON_EXTRACT(name, '$.en') = 'Ferritin'")
            ->get();

        foreach ($indicators as $indicator) {
            AnalysGroup::create([
                'indicator_id' => $indicator->id,
                'analys_id' => $analys->id
            ]);
        }
    }

    public function createTotalHelminthAntibodies()
    {
        $analys = CatalogAnalys::whereRaw("JSON_EXTRACT(name, '$.en') = 'Total helminth antibodies'")->first();
        $indicators = CatalogIndicator::whereRaw("JSON_EXTRACT(name, '$.en') = 'Lamblia (Lamblia Giardia) total antibodies Ig A, M, G'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'Opisthorchis (Opisthorchis felineus) Ig G'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'Trichinella (Trichinella spiralis) total antibodies Ig A, M, G'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'Echinococcus (Echinococcus granulosus) total antibodies Ig A, M, G'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'Ascaris (Ascaris lumbricoides) Ig G'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'Toxocara (Toxocara canis) Ig G'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'Borrelia (Borrelia burgdorferi) Ig G semiquantitative'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'Cysticercus (Taenia solium) Ig G'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'Toxoplasma (Toxoplasma gondii) Ig G quantitatively'")
            ->orWhereRaw("JSON_EXTRACT(name, '$.en') = 'Anisakida (Anisakis) Ig G'")
            ->get();

        foreach ($indicators as $indicator) {
            AnalysGroup::create([
                'indicator_id' => $indicator->id,
                'analys_id' => $analys->id
            ]);
        }
    }

    public function createProgesterone()
    {
        $analys = CatalogAnalys::whereRaw("JSON_EXTRACT(name, '$.en') = 'Progesterone'")->first();
        $indicators = CatalogIndicator::whereRaw("JSON_EXTRACT(name, '$.en') = 'Progesterone (PRG)'")
            ->get();

        foreach ($indicators as $indicator) {
            AnalysGroup::create([
                'indicator_id' => $indicator->id,
                'analys_id' => $analys->id
            ]);
        }
    }

    public function createCortisol()
    {
        $analys = CatalogAnalys::whereRaw("JSON_EXTRACT(name, '$.en') = 'Cortisol'")->first();
        $indicators = CatalogIndicator::whereRaw("JSON_EXTRACT(name, '$.en') = 'Blood cortisol'")
            ->get();

        foreach ($indicators as $indicator) {
            AnalysGroup::create([
                'indicator_id' => $indicator->id,
                'analys_id' => $analys->id
            ]);
        }
    }

    public function createEstradiol()
    {
        $analys = CatalogAnalys::whereRaw("JSON_EXTRACT(name, '$.en') = 'Estradiol'")->first();
        $indicators = CatalogIndicator::whereRaw("JSON_EXTRACT(name, '$.en') = 'Estradiol (E2)'")
            ->get();

        foreach ($indicators as $indicator) {
            AnalysGroup::create([
                'indicator_id' => $indicator->id,
                'analys_id' => $analys->id
            ]);
        }
    }

    public function createTotalPSA()
    {
        $analys = CatalogAnalys::whereRaw("JSON_EXTRACT(name, '$.en') = 'Total PSA'")->first();
        $indicators = CatalogIndicator::whereRaw("JSON_EXTRACT(name, '$.en') = 'Total PSA'")
            ->get();

        foreach ($indicators as $indicator) {
            AnalysGroup::create([
                'indicator_id' => $indicator->id,
                'analys_id' => $analys->id
            ]);
        }
    }

    public function createPSA()
    {
        $analys = CatalogAnalys::whereRaw("JSON_EXTRACT(name, '$.en') = 'PSA'")->first();
        $indicators = CatalogIndicator::whereRaw("JSON_EXTRACT(name, '$.en') = 'Ratio of PSA free to PSA total'")
            ->get();

        foreach ($indicators as $indicator) {
            AnalysGroup::create([
                'indicator_id' => $indicator->id,
                'analys_id' => $analys->id
            ]);
        }
    }

    public function createProlactin()
    {
        $analys = CatalogAnalys::whereRaw("JSON_EXTRACT(name, '$.en') = 'Prolactin'")->first();
        $indicators = CatalogIndicator::whereRaw("JSON_EXTRACT(name, '$.en') = 'Prolactin (PRL)'")
            ->get();

        foreach ($indicators as $indicator) {
            AnalysGroup::create([
                'indicator_id' => $indicator->id,
                'analys_id' => $analys->id
            ]);
        }
    }

    public function createIGF1()
    {
        $analys = CatalogAnalys::whereRaw("JSON_EXTRACT(name, '$.en') = 'IGF-1'")->first();
        $indicators = CatalogIndicator::whereRaw("JSON_EXTRACT(name, '$.en') = 'Somatomedin (IGF-1)'")
            ->get();

        foreach ($indicators as $indicator) {
            AnalysGroup::create([
                'indicator_id' => $indicator->id,
                'analys_id' => $analys->id
            ]);
        }
    }
}
