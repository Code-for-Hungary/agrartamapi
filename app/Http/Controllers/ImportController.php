<?php

namespace App\Http\Controllers;

use App\Http\Resources\TamogatasExcelResource;
use App\Models\Alap;
use App\Models\Cegcsoport;
use App\Models\Forras;
use App\Models\Jogcim;
use App\Models\Megye;
use App\Models\Tamogatas;
use App\Models\Tamogatott;
use App\Models\Telepules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class ImportController extends Controller
{
    private $jogcimCache = [];
    private $alapCache = [];
    private $forrasCache = [];
    private $megyeCache = [];
    private $tamogatottCache = [];
    private $cegcsoportCache = [];
    private $telepulesCache = [];

    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        if ($request->input('password') === env('IMPORT_PASSWORD')) {
            if ($request->hasFile('import') && $request->file('import')->isValid()) {
                $file = $request->file('import');
                $path = $file->storeAs('local', uniqid('agrar') . '.' . $file->extension());

                $errorurl = $this->import(Storage::path($path));
                if ($errorurl) {
                    return redirect($errorurl);
                }
                return response('OK');
            } else {
                return response('Baad request', 400);
            }
        } else {
            return response('Forbidden', 403);
        }
    }

    protected function import($filepath)
    {
        $valtozottak = [];

        $this->fillCaches();

        $excel = new Spreadsheet();
        $errorsheet = $excel->setActiveSheetIndex(0);
        foreach (TamogatasExcelResource::getHeader() as $head) {
            $errorsheet->setCellValue($head['col'] . '1', $head['data']);
        }
        $errow = 2;

        $in = IOFactory::load($filepath);
        $sheet = $in->setActiveSheetIndex(0);
        $maxrow = $sheet->getHighestDataRow('T');
        for ($row = 2; $row <= $maxrow; ++$row) {
            $error = [];

            DB::beginTransaction();

            $id = $sheet->getCell('A' . $row)->getValue();

            $ev = (int)$sheet->getCell('B' . $row)->getValue();
            if (!$ev || $ev < 2010 || $ev > (int)date('Y')) {
                $error[] = 'Hibás év';
            }

            $nev = (string)$sheet->getCell('C' . $row)->getValue();
            if (!$nev) {
                $error[] = 'Üres név';
            }

            $irszam = (string)$sheet->getCell('F' . $row)->getValue();
            if (!$irszam) {
                $error[] = 'Üres ir.szám';
            }

            $varos = (string)$sheet->getCell('G' . $row)->getValue();
            if (!$varos) {
                $error[] = 'Üres város';
            }

            $utca = (string)$sheet->getCell('H' . $row)->getValue();
            if (!$utca) {
                $error[] = 'Üres utca';
            }

            $is_firm = (boolean)$sheet->getCell('E' . $row)->getValue();
            $gender = (string)$sheet->getCell('D' . $row)->getValue();
            if (!$is_firm) {
                if ($gender !== 'male' && $gender !== 'female') {
                    $error[] = 'Természetes személynek nincs megadva gender';
                }
            } else {
                $gender = '';
            }

            if (!$error) {
                $megye_id = $sheet->getCell('I' . $row)->getValue();
                $megye = $this->getMegye($megye_id);

                $telepules = $this->getTelepules($irszam, $varos);

                $cegcsoport_id = $sheet->getCell('K' . $row)->getValue();
                $cegcsoport_name = $sheet->getCell('L' . $row)->getValue();
                $cegcsoport = $this->getCegcsoport($cegcsoport_id, $cegcsoport_name);
                if ($cegcsoport === false) {
                    $error[] = 'Új cégcsoport, de nincs név megadva';
                }

                $tamogatott_id = $sheet->getCell('M' . $row)->getValue();
                $tamogatott_name = $sheet->getCell('N' . $row)->getValue();
                $tamogatott = $this->getTamogatott($tamogatott_id, $tamogatott_name);
                if ($tamogatott === false) {
                    $error[] = 'Új támogatott entitás, de nincs név megadva';
                }

                $jogcim_name = $sheet->getCell('O' . $row)->getValue();
                $jogcim_sorrend = $sheet->getCell('P' . $row)->getValue();
                if (!$jogcim_name) {
                    $error[] = 'Nincs jogcím név megadva';
                }

                $alap_name = $sheet->getCell('Q' . $row)->getValue();
                if (!$alap_name) {
                    $error[] = 'Nincs alap név megadva';
                }

                $forras_name = $sheet->getCell('R' . $row)->getValue();
                if (!$forras_name) {
                    $error[] = 'Nincs forrás név megadva';
                }

                if ($id) {
                    $tam = Tamogatas::find($id);
                    if (!$tam) {
                        $error[] = 'Ismeretlen ID';
                    }
                } else {
                    $tam = new Tamogatas();
                }
                $is_landbased = (boolean)$sheet->getCell('S' . $row)->getValue();
                $osszeg = (int)$sheet->getCell('T' . $row)->getValue();
            }

            if ($error) {
                DB::rollBack();

                foreach (TamogatasExcelResource::getHeader() as $head) {
                    $errorsheet->setCellValue(
                        $head['col'] . $errow,
                        $sheet->getCell($head['col'] . $row)->getValue()
                    );
                }
                $errorsheet->setCellValue('U' . $errow, implode('; ', $error));
                $errow++;
            } else {
                $tam->ev = $ev;
                $tam->name = $nev;
                $tam->gender = $gender;
                $tam->is_firm = $is_firm;
                $tam->irszam = '';
                $tam->varos = '';
                $tam->utca = $utca;
                $tam->megye()->associate($megye);
                $tam->cegcsoport()->associate($cegcsoport);
                $tam->tamogatott()->associate($tamogatott);
                $tam->jogcim()->associate($this->getJogcim($jogcim_name, $jogcim_sorrend));
                $tam->alap()->associate($this->getAlap($alap_name));
                $tam->forras()->associate($this->getForras($forras_name));
                $tam->telepules()->associate($telepules);
                $tam->is_landbased = $is_landbased;
                $tam->osszeg = $osszeg;
                $tam->evesosszeg = 0;
                $tam->point_lat = 0.0;
                $tam->point_long = 0.0;
                $tam->save();

                $valtozottak[(string)$ev . $nev . $telepules->id . $utca] = [
                    'ev' => $ev,
                    'nev' => $nev,
                    'telepules' => $telepules->id,
                    'utca' => $utca
                ];
                DB::commit();
            }
        }

        $this->recalcEvesosszeg($valtozottak);

        if ($errow > 2) {
            $writer = IOFactory::createWriter($excel, 'Xlsx');
            $filename = 'agrar_' . Str::uuid() . '.xlsx';
            $pathfilename = public_path('storage/') . $filename;
            $writer->save($pathfilename);
            return asset('storage/' . $filename, true);
        }
        return false;
    }

    protected function fillCaches()
    {
        foreach (Jogcim::get() as $item) {
            $this->jogcimCache[$item->name] = $item;
        }
        foreach (Alap::get() as $item) {
            $this->alapCache[$item->name] = $item;
        }
        foreach (Forras::get() as $item) {
            $this->forrasCache[$item->name] = $item;
        }
        foreach (Megye::get() as $item) {
            $this->megyeCache['+' . $item->id] = $item;
        }
        foreach (Tamogatott::get() as $item) {
            $this->tamogatottCache[$item->id] = $item;
        }
        foreach (Cegcsoport::get() as $item) {
            $this->cegcsoportCache[$item->id] = $item;
        }
        foreach (Telepules::get() as $item) {
            $this->telepulesCache[$item->irszam . $item->name] = $item;
        }
    }

    /**
     * @param $id
     * @return false|Megye
     */
    protected function getMegye($id)
    {
        if (array_key_exists('+' . $id, $this->megyeCache)) {
            return $this->megyeCache['+' . $id];
        }
        return null;
    }

    protected function getCegcsoport($id, $name)
    {
        if (!$id) {
            return null;
        }
        if (array_key_exists($id, $this->cegcsoportCache)) {
            return $this->cegcsoportCache[$id];
        }
        if (!$name) {
            return false;
        }
        $cegcs = new Cegcsoport();
        $cegcs->id = $id;
        $cegcs->name = $name;
        $cegcs->save();
        $this->cegcsoportCache[$id] = $cegcs;
        return $cegcs;
    }

    protected function getTamogatott($id, $name)
    {
        if (!$id) {
            return null;
        }
        if (array_key_exists($id, $this->tamogatottCache)) {
            return $this->tamogatottCache[$id];
        }
        if (!$name) {
            return false;
        }
        $tam = new Tamogatott();
        $tam->id = $id;
        $tam->name = $name;
        $tam->save();
        $this->tamogatottCache[$id] = $tam;
        return $tam;
    }

    protected function getJogcim($name, $sorrend)
    {
        if (array_key_exists($name, $this->jogcimCache)) {
            return $this->jogcimCache[$name];
        }
        $jogcim = new Jogcim();
        $jogcim->name = $name;
        $jogcim->sorrend = $sorrend;
        $jogcim->save();
        $this->jogcimCache[$name] = $jogcim;
        return $jogcim;
    }

    protected function getAlap($name)
    {
        if (array_key_exists($name, $this->alapCache)) {
            return $this->alapCache[$name];
        }
        $alap = new Alap();
        $alap->name = $name;
        $alap->save();
        $this->alapCache[$name] = $alap;
        return $alap;
    }

    protected function getForras($name)
    {
        if (array_key_exists($name, $this->forrasCache)) {
            return $this->forrasCache[$name];
        }
        $forras = new Forras();
        $forras->name = $name;
        $forras->save();
        $this->forrasCache[$name] = $forras;
        return $forras;
    }

    protected function getTelepules($irszam, $name)
    {
        $kulcs = $irszam . $name;
        if (array_key_exists($kulcs, $this->telepulesCache)) {
            return $this->telepulesCache[$kulcs];
        }
        $telepules = new Telepules();
        $telepules->irszam = $irszam;
        $telepules->name = $name;
        $telepules->save();
        $this->telepulesCache[$kulcs] = $telepules;
        return $telepules;
    }

    private function recalcEvesosszeg($valtozottak)
    {
        foreach ($valtozottak as $item) {
            DB::beginTransaction();

            $evesosszeg = Tamogatas::where('ev', $item['ev'])
                ->where('name', $item['nev'])
                ->where('telepules_id', $item['telepules'])
                ->where('utca', $item['utca'])
                ->sum('osszeg');

            $sorok = Tamogatas::select()
                ->where('ev', $item['ev'])
                ->where('name', $item['nev'])
                ->where('telepules_id', $item['telepules'])
                ->where('utca', $item['utca'])
                ->get();
            foreach ($sorok as $sor) {
                $sor->evesosszeg = $evesosszeg;
                $sor->save();
            }

            DB::commit();
        }
    }

}
