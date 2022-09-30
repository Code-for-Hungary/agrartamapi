<?php

namespace App\Http\Controllers;

use App\Models\Alap;
use App\Models\Cegcsoport;
use App\Models\Forras;
use App\Models\Jogcim;
use App\Models\Megye;
use App\Models\Tamogatas;
use App\Models\Tamogatott;
use App\Models\Telepules;
use App\Traits\FileNameTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use OpenSpout\Common\Entity\Cell;
use OpenSpout\Reader\XLSX\Reader;
use OpenSpout\Writer\XLSX\Writer;

class ImportController extends Controller
{
    use FileNameTrait;

    private $jogcimCache = [];
    private $alapCache = [];
    private $forrasCache = [];
    private $megyeCache = [];
    private $tamogatottCache = [];
    private $cegcsoportCache = [];
    private $telepulesCache = [];

    private function n($char)
    {
        return ord($char) - 65;
    }

    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function __invoke(Request $request)
    {
        if ($request->input('password') === env('IMPORT_PASSWORD')) {
            $resp = [];
            if (($request->hasFile('cegcsoportimport') && $request->file('cegcsoportimport')->isValid()) ||
                ($request->hasFile('entitasimport') && $request->file('entitasimport')->isValid()) ||
                ($request->hasFile('import') && $request->file('import')->isValid())
                ) {
                if ($request->hasFile('cegcsoportimport')) {
                    $file = $request->file('cegcsoportimport');
                    $path = $file->storeAs('local', uniqid('cegcsoport') . '.' . $file->extension());

                    $ret = $this->cegcsoportImport(
                        Storage::path($path),
                        $file->getClientOriginalName()
                    );

                    if (env('IMPORT_REMOVE_UPLOAD', false)) {
                        @\unlink(Storage::path($path));
                    }

                    if ($ret['isError']) {
                        return redirect($ret['value']);
                    }
                    $resp[] = ['cégcsoport modified' => $ret['value']];
                }
                if ($request->hasFile('entitasimport')) {
                    $file = $request->file('entitasimport');
                    $path = $file->storeAs('local', uniqid('agrar') . '.' . $file->extension());

                    $ret = $this->entitasImport(
                        Storage::path($path),
                        $file->getClientOriginalName()
                    );

                    if (env('IMPORT_REMOVE_UPLOAD', false)) {
                        @\unlink(Storage::path($path));
                    }

                    if ($ret['isError']) {
                        return redirect($ret['value']);
                    }
                    $resp[] = ['entitás modified' => $ret['value']];
                }
                if ($request->hasFile('import')) {
                    $file = $request->file('import');
                    $path = $file->storeAs('local', uniqid('agrar') . '.' . $file->extension());

                    $ret = $this->import(
                        Storage::path($path),
                        $file->getClientOriginalName()
                    );

                    if (env('IMPORT_REMOVE_UPLOAD', false)) {
                        @\unlink(Storage::path($path));
                    }

                    if ($ret['isError']) {
                        return redirect($ret['value']);
                    }
                    $resp[] = ['modified' => $ret['value']];
                }
                return response($resp);
            }
            return response('Baad request', 400);
        } else {
            return response('Forbidden', 403);
        }
    }

    protected function cegcsoportImport($filepath, $orgfilename)
    {
        $writer = new Writer();
        $outfilename = $this->getFileName('cegcsoport_');
        $writer->openToFile($outfilename['fullfilename']);

        try {
            DB::beginTransaction();

            $waserror = false;
            $reader = new Reader();
            $reader->open($filepath);
            foreach ($reader->getSheetIterator() as $sheet) {
                if ($sheet->getIndex() === 0) {
                    $rowcnt = 0;
                    $modifiedcnt = 0;
                    foreach ($sheet->getRowIterator() as $row) {
                        $error = [];

                        $cells = $row->getCells();

                        if ($rowcnt === 0) {
                            $writer->addRow($row);
                        } else {
                            $id = $cells[$this->n('A')]->getValue();
                            if (!$id) {
                                $error[] = 'Nincs ID megadva';
                            }
                            $name = $cells[$this->n('B')]->getValue();
                            if (!$name) {
                                $error[] = 'Nincs név megadva';
                            }
                            if ($error) {
                                $row->addCell(Cell::fromValue(implode(';', $error)));
                                $writer->addRow($row);
                                $waserror = true;
                            } else {
                                $obj = Cegcsoport::find($id);
                                if (!$obj) {
                                    $obj = new Cegcsoport();
                                }
                                $obj->id = $id;
                                $obj->name = $name;
                                $obj->save();
                                $modifiedcnt++;
                            }
                        }
                        $rowcnt++;
                    }
                }
            }
            DB::commit();
            $reader->close();
            $writer->close();
            if ($waserror) {
                return [
                    'isError' => true,
                    'value' => asset('storage/' . $outfilename['filename'], true)
                ];
            }
            return [
                'isError' => false,
                'value' => $modifiedcnt
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    protected function entitasImport($filepath, $orgfilename)
    {
        $writer = new Writer();
        $outfilename = $this->getFileName('entitas_');
        $writer->openToFile($outfilename['fullfilename']);

        try {
            DB::beginTransaction();

            $waserror = false;
            $reader = new Reader();
            $reader->open($filepath);
            foreach ($reader->getSheetIterator() as $sheet) {
                if ($sheet->getIndex() === 0) {
                    $rowcnt = 0;
                    $modifiedcnt = 0;
                    foreach ($sheet->getRowIterator() as $row) {
                        $error = [];

                        $cells = $row->getCells();

                        if ($rowcnt === 0) {
                            $writer->addRow($row);
                        } else {
                            $id = $cells[$this->n('A')]->getValue();
                            if (!$id) {
                                $error[] = 'Nincs ID megadva';
                            }
                            $name = $cells[$this->n('B')]->getValue();
                            if (!$name) {
                                $error[] = 'Nincs név megadva';
                            }
                            $kurl = $cells[$this->n('C')]->getValue();
                            $irszam = $cells[$this->n('D')]->getValue();
                            $varos = $cells[$this->n('E')]->getValue();
                            $utca = $cells[$this->n('F')]->getValue();
                            if ($error) {
                                $row->addCell(Cell::fromValue(implode(';', $error)));
                                $writer->addRow($row);
                                $waserror = true;
                            } else {
                                $obj = Tamogatott::find($id);
                                if (!$obj) {
                                    $obj = new Tamogatott();
                                }
                                $obj->id = $id;
                                $obj->name = $name;
                                if ($kurl) {
                                    $obj->kurl = $kurl;
                                }
                                if ($irszam) {
                                    $obj->irszam = $irszam;
                                }
                                if ($varos) {
                                    $obj->varos = $varos;
                                }
                                if ($utca) {
                                    $obj->utca = $utca;
                                }
                                $obj->save();
                                $modifiedcnt++;
                            }
                        }
                        $rowcnt++;
                    }
                }
            }
            DB::commit();
            $reader->close();
            $writer->close();
            if ($waserror) {
                return [
                    'isError' => true,
                    'value' => asset('storage/' . $outfilename['filename'], true)
                ];
            }
            return [
                'isError' => false,
                'value' => $modifiedcnt
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    protected function import($filepath, $orgfilename)
    {
        $writer = new Writer();
        $outfilename = $this->getFileName();
        $writer->openToFile($outfilename['fullfilename']);

        $valtozottak = [];

        Log::channel('agrarimport')->debug('import started: ' . $orgfilename . ' (' . $filepath . ')');

        $this->fillCaches();

        try {
            DB::beginTransaction();

            $waserror = false;
            $reader = new Reader();
            $reader->open($filepath);
            foreach ($reader->getSheetIterator() as $sheet) {
                if ($sheet->getIndex() === 0) {
                    $rowcnt = 0;
                    $modifiedcnt = 0;
                    foreach ($sheet->getRowIterator() as $row) {
                        $error = [];

                        $cells = $row->getCells();

                        if ($rowcnt === 0) {
                            $writer->addRow($row);
                        }
                        else {
                            $id = $cells[$this->n('A')]->getValue();
                            if (!$id) {
                                $error[] = 'Nincs ID megadva, új felvitel nincs implementálva';
                            }

                            $cegcsoport_id = $cells[$this->n('K')]->getValue();
                            $cegcsoport_name = $cells[$this->n('L')]->getValue();
                            $cegcsoport = $this->getCegcsoport($cegcsoport_id, $cegcsoport_name);
                            if ($cegcsoport === false) {
                                $error[] = 'Új cégcsoport, de nincs név megadva';
                            }

                            $tamogatott_id = $cells[$this->n('M')]->getValue();
                            $tamogatott_name = $cells[$this->n('N')]->getValue();
                            $tamogatott = $this->getTamogatott($tamogatott_id, $tamogatott_name);
                            if ($tamogatott === false) {
                                $error[] = 'Új támogatott entitás, de nincs név megadva';
                            }

                            if ($error) {
                                $row->addCell(Cell::fromValue(implode(';', $error)));
                                $writer->addRow($row);
                                $waserror = true;
                            }
                            else {
                                if ($cegcsoport || $tamogatott) {
                                    $sql = 'UPDATE tamogatas SET ';
                                    $sets = [];
                                    $params = [];
                                    if ($cegcsoport) {
                                        $sets[] = 'cegcsoport_id=?';
                                        $params[] = $cegcsoport->id;
                                    }
                                    if ($tamogatott) {
                                        $sets[] = 'tamogatott_id=?';
                                        $params[] = $tamogatott->id;
                                    }
                                    $sql .= implode(',', $sets);
                                    $sql .= ' WHERE id=?';
                                    $params[] = $id;
                                    if (DB::update($sql, $params)) {
                                        $modifiedcnt++;
                                        $valtozottak[] = $id;
                                    }
                                }
                            }
                        }
                        $rowcnt++;
                    }
                }
            }
            DB::commit();
            $reader->close();
            $writer->close();
            Log::channel('agrarimport')->debug($valtozottak);
            Log::channel('agrarimport')->debug('import ended. modified: ' . $modifiedcnt);
            if ($waserror) {
                return [
                    'isError' => true,
                    'value' => asset('storage/' . $outfilename['filename'], true)
                    ];
            }
            return [
                'isError' => false,
                'value' => $modifiedcnt
            ];
        }
        catch (\Exception $e) {
            DB::rollBack();
            throw $e;
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
