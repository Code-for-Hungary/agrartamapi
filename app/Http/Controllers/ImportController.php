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
use Illuminate\Http\Request;
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

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
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
            }
            else {
                return response('Baad request', 400);
            }
        }
        else {
            return response('Forbidden', 403);
        }
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
        $tam = new Tamogatas();
        $tam->id = $id;
        $tam->name = $name;
        $tam->save();
        $this->tamogatottCache[$id] = $tam;
        return $tam;
    }

    protected function getJogcim($name)
    {
        if (array_key_exists($name, $this->jogcimCache)) {
            return $this->jogcimCache[$name];
        }
        $jogcim = new Jogcim();
        $jogcim->name = $name;
        $jogcim->save();
        $this->jogcimCache[$name] = $name;
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

    protected function import($filepath)
    {
        $this->fillCaches();

        $excel = new Spreadsheet();
        $errorsheet = $excel->setActiveSheetIndex(0);
        foreach (TamogatasExcelResource::getHeader() as $head) {
            $errorsheet->setCellValue($head['col'] . '1', $head['data']);
        }
        $errow = 2;

        $in = IOFactory::load($filepath);
        $sheet = $in->getActiveSheet();
        $maxrow = $sheet->getHighestRow();
        for ($row = 2; $row <= $maxrow; ++$row) {

            $error = [];

            $id = $sheet->getCell('A' . $row)->getValue();

            $ev = (int)$sheet->getCell('B' . $row)->getValue();
            if (!$ev || ($ev < 2010 && $ev > date('Y'))) {
                $error[] = 'Hibás év';
            }

            $nev = (string)$sheet->getCell('C' . $row)->getValue();
            if (!$nev) {
                $error[] = 'Üres név';
            }

            $is_firm = (boolean)$sheet->getCell('E' . $row)->getValue();
            $gender = (string)$sheet->getCell('D' . $row)->getValue();
            if (!$is_firm) {
                if ($gender !== 'male' && $gender !== 'female') {
                    $error[] = 'Természetes személynek nincs megadva gender';
                }
            }
            else {
                $gender = '';
            }

            $megye_id = $sheet->getCell('I' . $row)->getValue();
            $megye = $this->getMegye($megye_id);

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
            if (!$jogcim_name) {
                $error[] = 'Nincs jogcím név megadva';
            }

            $alap_name = $sheet->getCell('P' . $row)->getValue();
            if (!$alap_name) {
                $error[] = 'Nincs alap név megadva';
            }

            $forras_name = $sheet->getCell('Q' . $row)->getValue();
            if (!$forras_name) {
                $error[] = 'Nincs forrás név megadva';
            }

            if ($id) {
                $tam = Tamogatas::find($id);
                if (!$tam) {
                    $error[] = 'Ismeretlen ID';
                }
            }
            else {
                $tam = new Tamogatas();
            }

            if ($error) {
                foreach (TamogatasExcelResource::getHeader() as $head) {
                    $errorsheet->setCellValue(
                        $head['col'] . $errow,
                        $sheet->getCell($head['col'] . $row)->getValue()
                    );
                }
                $errorsheet->setCellValue('T' . $errow, implode('; ', $error));
                $errow++;
            }
            else {
                $tam->ev = $ev;
                $tam->name = $nev;
                $tam->gender = $gender;
                $tam->is_firm = $is_firm;
                $tam->irszam = $sheet->getCell('F' . $row)->getValue();
                $tam->varos = $sheet->getCell('G' . $row)->getValue();
                $tam->utca = $sheet->getCell('H' . $row)->getValue();
                $tam->megye()->associate($megye);
                $tam->cegcsoport()->associate($cegcsoport);
                $tam->tamogatott()->associate($tamogatott);
                $tam->jogcim()->associate($this->getJogcim($jogcim_name));
                $tam->alap()->associate($this->getAlap($alap_name));
                $tam->forras()->associate($this->getForras($forras_name));
                $tam->is_landbased = (boolean)$sheet->getCell('R' . $row)->getValue();
                $tam->osszeg = (int)$sheet->getCell('S' . $row)->getValue();
            }
        }
        if ($errow > 2) {
            $writer = IOFactory::createWriter($excel, 'Xlsx');
            $filename = 'agrar_' . Str::uuid() . '.xlsx';
            $pathfilename = public_path('storage/') . $filename;
            $writer->save($pathfilename);
            return asset('storage/' . $filename, true);
        }
        return false;
    }

}
