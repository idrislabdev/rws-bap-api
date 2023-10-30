<?php

namespace App\Http\Controllers\SARPEN\Transaksi;

use App\Helper\UtilityHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\TrBaSarpenCustomResource;
use App\Http\Resources\TrBaSarpenGambarResource;
use App\Http\Resources\TrBaSarpenResource;
use App\Models\MaBaSarpenTemplate;
use App\Models\MaNomorDokumen;
use App\Models\MaOloKlien;
use App\Models\MaPengguna;
use App\Models\MaSarpenTemplate;
use App\Models\TrBaSarpen;
use App\Models\TrBaSarpenAkses;
use App\Models\TrBaSarpenCatuDayaGenset;
use App\Models\TrBaSarpenCatuDayaMcb;
use App\Models\TrBaSarpenGambar;
use App\Models\TrBaSarpenLahan;
use App\Models\TrBaSarpenRack;
use App\Models\TrBaSarpenRuangan;
use App\Models\TrBaSarpenService;
use App\Models\TrBaSarpenTower;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;
use PDF2;

class BeritaAcaraController extends Controller
{
    private $_hari = ['SENIN', 'SELASA', 'RABU', 'KAMIS', 'JUMAT', 'SABTU', 'MINGGU'];
    private $_month = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    public function index()
    {
        $data = TrBaSarpen::with('pembuat')->with('managerWholesale')->with('parafWholesale');
        if (isset($_GET['page'])) {

            if (isset($_GET['q']) && $_GET['q'] !== '') {
                $q = $_GET['q'];
                $data = $data->whereRaw("(no_dokumen like '%$q%' or 
                                          nama_sto like '%$q%' or 
                                          nama_site like '%$q%' or
                                          regional like '%$q%' or
                                          nama_klien like '%$q%'
                                          )");
            }

            if (isset($_GET['group'])) {
                $data = $data->where('group', $_GET['group']);
            }

            if (isset($_GET['type'])) {
                if($_GET['type'] === 'site')
                    $data = $data->where('type', 'site');

                if($_GET['type'] === 'sto')
                    $data = $data->where('type', 'sto');

                if($_GET['type'] === 'no_order')
                    $data = $data->where('type', 'no_order');
            }

            if (Auth::user()->role === 'WITEL') {
                $data = $data->where('site_witel', Auth::user()->site_witel);
            } else if (Auth::user()->role === 'RWS' || Auth::user()->role === 'ROOT')  {
                if (isset($_GET['site_witel']))
                    $data = $data->where('site_witel', $_GET['site_witel']);
            }
            
            if (isset($_GET['status'])) {
                if ($_GET['status'] != 'own_action') {
                    $data = $data->where('status', $_GET['status']);
                } else {
                    $user_id = Auth::user()->id;
                    $data = $data->whereRaw("(manager_witel = '$user_id' and status = 'proposed') or
                                            (paraf_wholesale = '$user_id' and status = 'ttd_witel') or
                                            (manager_wholesale = '$user_id' and status = 'paraf_wholesale')
                                            ");
                }

                if ($_GET['status'] == 'draft') {
                    $data = $data->where('created_by', Auth::user()->id);
                }
            }
                



            $per_page = 50;
            if (isset($_GET['per_page']))
                $per_page = $_GET['per_page'];

            $data = $data->orderByDesc('tanggal_buat')->paginate($per_page)->onEachSide(5);
        } else {
            if (isset($_GET['group'])) {
                $data = $data->where('group', $_GET['group']);
            }
            $data = $data->get();
        }

        return TrBaSarpenCustomResource::collection(($data))->additional([
            'success' => true,
            'message' => null,
        ]);
    }

    public function store(Request $request)
    {
        $v = Validator::make($request->all(), [
            'template_id' => 'required',
        ]);

        if ($v->fails()) {
            return response()->json([
                'data' => null,
                'succes' => false,
                'message' => $v->errors()
            ], 422);
        }

        try {

            $template = MaSarpenTemplate::find($request->template_id);

            // $manager_witel = MaPengguna::find($template->manager_witel);
            $manager_witel_data = new \stdClass(); 
            $manager_witel_data->id = null;
            $manager_witel_data->nama_lengkap = null;
            $manager_witel_data->lokasi_kerja = null;
            $manager_witel_data->jabatan = null;
            $manager_witel_data->jabatan = null;
            $manager_witel_data->ttd_image = null;
            $manager_witel_data->status_dokumen = null;
            $manager_witel_data->approved_at = null;

            $paraf_wholesale = MaPengguna::find($template->paraf_wholesale);
            $paraf_wholesale_data = new \stdClass(); 
            $paraf_wholesale_data->id = $paraf_wholesale->id;
            $paraf_wholesale_data->nama_lengkap = $paraf_wholesale->nama_lengkap;
            $paraf_wholesale_data->lokasi_kerja = $paraf_wholesale->lokasi_kerja;
            $paraf_wholesale_data->jabatan = $paraf_wholesale->jabatan;
            $paraf_wholesale_data->jabatan = $paraf_wholesale->jabatan;
            $paraf_wholesale_data->ttd_image = $paraf_wholesale->ttd_image;
            $paraf_wholesale_data->status_dokumen = null;
            $paraf_wholesale_data->approved_at = null;

            $manager_wholesale = MaPengguna::find($template->manager_wholesale);
            $manager_wholesale_data = new \stdClass(); 
            $manager_wholesale_data->id = $manager_wholesale->id;
            $manager_wholesale_data->nama_lengkap = $manager_wholesale->nama_lengkap;
            $manager_wholesale_data->lokasi_kerja = $manager_wholesale->lokasi_kerja;
            $manager_wholesale_data->jabatan = $manager_wholesale->jabatan;
            $manager_wholesale_data->ttd_image = $manager_wholesale->ttd_image;
            $manager_wholesale_data->status_dokumen = null;
            $manager_wholesale_data->approved_at = null;

            
            $data = new TrBaSarpen();
            $data->group = $template->group;
            $data->type = $template->sto_site;
            $data->paraf_wholesale = $template->paraf_wholesale;
            $data->paraf_wholesale_data  = json_encode($paraf_wholesale_data, JSON_PRETTY_PRINT);
            $data->manager_wholesale = $template->manager_wholesale;
            $data->manager_wholesale_data  = json_encode($manager_wholesale_data, JSON_PRETTY_PRINT);
            $data->manager_witel_data  = json_encode($manager_witel_data, JSON_PRETTY_PRINT);
            $data->tanggal_buat = date('Y-m-d');
            $data->status = 'DRAFT';
            $data->setting  = json_encode($template, JSON_PRETTY_PRINT);
            $data->created_by = Auth::user()->id;
            $data->site_witel = Auth::user()->site_witel;
            // $data->updated_by = Auth::user()->id;
            $data->id = Uuid::uuid4()->toString();

            if ($template->group == 'TELKOM') {
                $klien = MaOloKlien::find('1cd01e5f-9550-474b-b56b-c9529fa3a5e7');
                $klien_data = new \stdClass(); 
                $klien_data->id = $klien->id;
                $klien_data->nama = null;
                $klien_data->jabatan = null;
                $klien_data->lokasi_kerja = null;
                $klien_data->nama_perusahaan = null;
                $data->klien_data  = json_encode($klien_data, JSON_PRETTY_PRINT);
                $data->klien = $klien->id;
            }

          
            $data->save();

            return (new TrBaSarpenResource($data))->additional([
                'success' => true,
                'message' => 'Data Baru Telah Dibuat'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => null,
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $data = TrBaSarpen::with([
                'pembuat', 
                'managerWholesale', 
                'parafWholesale', 
                'towers', 
                'ruangans',
                'lahans',
                'services',
                'akseses',
                'catuDayaMcbs',
                'catuDayaGensets',
                'racks',
                'gambars'
            ])->findOrFail($id);

            $data->setting = json_decode($data->setting);
            $data->manager_witel_data = json_decode($data->manager_witel_data);
            $data->paraf_wholesale_data = json_decode($data->paraf_wholesale_data);
            $data->manager_wholesale_data = json_decode($data->manager_wholesale_data);

            if ($data->klien_data !== null) {
                $data->klien_data = json_decode($data->klien_data);
            } else {
                $klien = new \stdClass(); 
                $klien->nama = null;
                $klien->jabatan = null;
                $klien->lokasi_kerja = null;
                $klien->nama_perusahaan = null;
                $data->klien_data = $klien;
            }

            return (new TrBaSarpenResource($data))->additional([
                'success' => true,
                'message' => 'suksess'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => null,
                'success' => false,
                'message' => $th->getMessage(),
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $data = TrBaSarpen::findOrFail($id);
            $setting = json_decode($data->setting);

            if ($request->has('manager_witel_data'))
                $data->manager_witel_data  = json_encode($request->manager_witel_data, JSON_PRETTY_PRINT);

            if ($request->has('manager_witel'))
                $data->manager_witel  = $request->manager_witel;

            if ($request->has('klien_data')) {
                $data->klien_data  = json_encode($request->klien_data, JSON_PRETTY_PRINT);
                $data->nama_klien = $request->klien_data['nama_perusahaan'];
            }
                $data->klien_data  = json_encode($request->klien_data, JSON_PRETTY_PRINT);

            if ($request->has('klien'))
                $data->klien  = $request->klien;    
            
            if ($request->has('nama_site'))
                $data->nama_site  = $request->nama_site;

            if ($request->has('nomor_order'))
                $data->nomor_order  = $request->nomor_order;

            if ($request->has('no_dokumen_klien'))
                $data->no_dokumen_klien  = $request->no_dokumen_klien;

            if ($request->has('nama_sto'))
                $data->nama_sto  = $request->nama_sto;

            if ($request->has('nomor_order'))
                $data->nomor_order  = $request->nomor_order;
            
            if ($request->has('alamat'))
                $data->alamat  = $request->alamat;

            if ($request->has('latitude'))
                $data->latitude  = $request->latitude;

            if ($request->has('longitude'))
                $data->longitude  = $request->longitude;

            if ($request->has('regional'))
                $data->regional  = $request->regional;

            if ($request->has('catatan'))
                $data->catatan  = $request->catatan;

            if ($request->has('site'))
                $data->site  = $request->site;

            if ($request->has('sto'))
                $data->sto  = $request->sto;

            $data->updated_by = Auth::user()->id;

            if ($request->has('towers') && $setting->tower) {
                TrBaSarpenTower::where('sarpen_id', $data->id)->delete();
                $towers = $request->towers;
                $no = 1;
                foreach ($towers as $tower) {
                    if ($tower['type_jenis_antena'] !== null) {
                        $data_tower = new TrBaSarpenTower();
                        $data_tower->sarpen_id = $data->id;
                        $data_tower->no = $no;
                        $data_tower->type_jenis_antena = $tower['type_jenis_antena'];
                        $data_tower->status_antena = $tower['status_antena'];
                        $data_tower->ketinggian_meter = $tower['ketinggian_meter'];
                        $data_tower->diameter_meter = $tower['diameter_meter'];
                        $data_tower->jumlah_antena = $tower['jumlah_antena'];
                        $data_tower->tower_leg_mounting_position = $tower['tower_leg_mounting_position'];
                        $data_tower->save();
                        $no++;
                    }
                    
                }
            }

            if ($request->has('racks') && $setting->rack) {
                TrBaSarpenRack::where('sarpen_id', $data->id)->delete();
                $racks = $request->racks;
                $no = 1;
                foreach ($racks as $rack) {
                    if ($rack['nomor_rack'] !== null) {
                        $data_rack = new TrBaSarpenRack();
                        $data_rack->sarpen_id = $data->id;
                        $data_rack->no = $no;
                        $data_rack->nomor_rack = $rack['nomor_rack'];
                        $data_rack->type_rack = $rack['type_rack'];
                        $data_rack->jumlah_perangkat = $rack['jumlah_perangkat'];
                        $data_rack->type_perangkat = $rack['type_perangkat'];
                        $data_rack->save();
                        $no++;
                    }
                    
                }
            }

            if ($request->has('ruangans') && $setting->ruangan) {
                TrBaSarpenRuangan::where('sarpen_id', $data->id)->delete();
                $ruangans = $request->ruangans;
                $no = 1;
                foreach ($ruangans as $ruangan) {
                    if ($ruangan['nama_ruangan'] !== null) {
                        $data_ruangan = new TrBaSarpenRuangan();
                        $data_ruangan->sarpen_id = $data->id;
                        $data_ruangan->no = $no;
                        $data_ruangan->nama_ruangan = $ruangan['nama_ruangan'];
                        $data_ruangan->peruntukan_ruangan = $ruangan['peruntukan_ruangan'];
                        $data_ruangan->bersama_tersendiri = $ruangan['bersama_tersendiri'];
                        $data_ruangan->terkondisi = $ruangan['terkondisi'];
                        $data_ruangan->status_kepemilikan_ac = $ruangan['status_kepemilikan_ac'];
                        $data_ruangan->panjang_meter = $ruangan['panjang_meter'];
                        $data_ruangan->lebar_meter = $ruangan['lebar_meter'];
                        $data_ruangan->save();
                        $no++;
                    }
                    
                }
            }

            if ($request->has('lahans') && $setting->lahan) {
                TrBaSarpenLahan::where('sarpen_id', $data->id)->delete();
                $lahans = $request->lahans;
                $no = 1;
                foreach ($lahans as $lahan) {
                    if ($lahan['nama_lahan'] !== null) {
                        $data_lahan = new TrBaSarpenLahan();
                        $data_lahan->sarpen_id = $data->id;
                        $data_lahan->no = $no;
                        $data_lahan->nama_lahan = $lahan['nama_lahan'];
                        $data_lahan->peruntukan_lahan = $lahan['peruntukan_lahan'];
                        $data_lahan->panjang_meter = $lahan['panjang_meter'];
                        $data_lahan->lebar_meter = $lahan['lebar_meter'];
                        $data_lahan->save();
                        $no++;
                    }
                    
                }
            }

            if ($request->has('catu_daya_gensets') && $setting->catu_daya_genset) {
                TrBaSarpenCatuDayaGenset::where('sarpen_id', $data->id)->delete();
                $catu_dayas = $request->catu_daya_gensets;
                $no = 1;
                foreach ($catu_dayas as $catu_daya) {
                    if ($catu_daya['merk_type_genset'] !== null) {
                        $data_catu = new TrBaSarpenCatuDayaGenset();
                        $data_catu->sarpen_id = $data->id;
                        $data_catu->no = $no;
                        $data_catu->merk_type_genset = $catu_daya['merk_type_genset'];
                        $data_catu->kapasitas_kva = $catu_daya['kapasitas_kva'];
                        $data_catu->utilisasi_beban = $catu_daya['utilisasi_beban'];
                        $data_catu->pemilik_genset = $catu_daya['pemilik_genset'];
                        $data_catu->koneksi_ke_telkomsel = $catu_daya['koneksi_ke_telkomsel'];
                        $data_catu->save();
                        $no++;
                    }
                    
                }
            }

            if ($request->has('catu_daya_mcbs') && $setting->catu_daya_mcb) {
                TrBaSarpenCatuDayaMcb::where('sarpen_id', $data->id)->delete();
                $catu_dayas = $request->catu_daya_mcbs;
                $no = 1;
                foreach ($catu_dayas as $catu_daya) {
                    if ($catu_daya['peruntukan'] !== null) {
                        $data_catu = new TrBaSarpenCatuDayaMcb();
                        $data_catu->sarpen_id = $data->id;
                        $data_catu->no = $no;
                        $data_catu->peruntukan = $catu_daya['peruntukan'];
                        $data_catu->mcb_amp = $catu_daya['mcb_amp'];
                        $data_catu->jumlah_phase = $catu_daya['jumlah_phase'];
                        $data_catu->voltage = $catu_daya['voltage'];
                        $data_catu->catatan = $catu_daya['catatan'];
                        $data_catu->save();
                        $no++;
                    }
                    
                }
            }

            if ($request->has('services') && $setting->service) {
                TrBaSarpenService::where('sarpen_id', $data->id)->delete();
                $services  = $request->services;
                $no = 1;
                foreach ($services as $service) {
                    if ($service['nama_service'] !== null) {
                        $data_service = new TrBaSarpenService();
                        $data_service->sarpen_id = $data->id;
                        $data_service->no = $no;
                        $data_service->nama_service = $service['nama_service'];
                        $data_service->port_pe = $service['port_pe'];
                        $data_service->keterangan = $service['keterangan'];
                        $data_service->save();
                        $no++;
                    } 
                }
            }

            if ($request->has('akseses') && $setting->akses) {
                TrBaSarpenAkses::where('sarpen_id', $data->id)->delete();
                $akseses  = $request->akseses;
                $no = 1;
                foreach ($akseses as $akses) {
                    if ($akses['peruntukan_akses'] !== null) {
                        $data_service = new TrBaSarpenAkses();
                        $data_service->sarpen_id = $data->id;
                        $data_service->no = $no;
                        $data_service->peruntukan_akses = $akses['peruntukan_akses'];
                        $data_service->panjang_meter = $akses['panjang_meter'];
                        $data_service->arah_akses = $akses['arah_akses'];
                        $data_service->save();
                        $no++;
                    } 
                }
            }

            $data->save();
            DB::commit();

            return (new TrBaSarpenResource($data))->additional([
                'success' => true,
                'message' => 'Data Berhasil Dirubah'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => null,
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function totalSarpen($group) 
    {
        $role = Auth::user()->role;


        $count = new \stdClass(); 
        $draft = TrBaSarpen::where('status', 'draft')->where('group', $group);
        $count->draft = $draft->where('created_by', Auth::user()->id)->count();

        $proposed = TrBaSarpen::where('status', 'proposed')->where('group', $group);
        if ($role === 'WITEL') {
            $count->proposed = $proposed->where('site_witel', Auth::user()->site_witel)->count();
        } else {
            $count->proposed = $proposed->count();
        }

        $rejected = TrBaSarpen::where('status', 'rejected')->where('group', $group);
        if ($role === 'WITEL') {
            $count->rejected = $rejected->where('site_witel', Auth::user()->site_witel)->count();
        } else {
            $count->rejected = $rejected->count();
        }

        $ttd_witel = TrBaSarpen::where('status', 'ttd_witel')->where('group', $group);
        if ($role === 'WITEL') {
            $count->ttd_witel = $ttd_witel->where('site_witel', Auth::user()->site_witel)->count();
        } else {
            $count->ttd_witel = $ttd_witel->count();
        }

        $ttd_wholesale = TrBaSarpen::where('status', 'ttd_wholesale')->where('group', $group);
        if ($role === 'WITEL') {
            $count->ttd_wholesale = $ttd_wholesale->where('site_witel', Auth::user()->site_witel)->count();
        } else {
            $count->ttd_wholesale = $ttd_wholesale->count();
        }

        $paraf_wholesale = TrBaSarpen::where('status', 'paraf_wholesale')->where('group', $group);
        if ($role === 'WITEL') {
            $count->paraf_wholesale = $paraf_wholesale->where('site_witel', Auth::user()->site_witel)->count();
        } else {
            $count->paraf_wholesale = $paraf_wholesale->count();
        }        

        $own_action = TrBaSarpen::where('group', $group);
        if ($role === 'WITEL') {
            $own_action = $own_action->where('site_witel', Auth::user()->site_witel);
        } 
        $user_id =  Auth::user()->id;
        $count->own_action  = $own_action->whereRaw("
                                (manager_witel = '$user_id' and status = 'proposed') or
                                (paraf_wholesale = '$user_id' and status = 'ttd_witel') or
                                (manager_wholesale = '$user_id' and status = 'paraf_wholesale')")->count();

        return response()->json([
            'data' => $count,
            'success' => true,
            'message' => null,
        ], 200);
    }

    public function proposed($id)
    {
        $data = TrBaSarpen::find($id);

        if(!$data)
        {
            return response()->json([
                'status' => false,
                'message' => 'Data Tidak Ditemukan',
                'data' => null
            ], 404);
        }

        if ($data->manager_witel === null) 
        {
            return response()->json([
                'status' => false,
                'message' => 'Manager Witel Belum Diisi',
                'data' => null
            ], 422);
        }

        DB::beginTransaction();
        try {
            $data->status = 'proposed';
            $data->save();
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'success',
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'data' => $e->getMessage(),
                'success' => true,
                'message' => 'error',
            ], 400);
        }
    }

    public function ttdWitel($id)
    {
        $data = TrBaSarpen::find($id);
        if(!$data)
        {
            return response()->json([
                'status' => false,
                'message' => 'Data Tidak Ditemukan',
                'data' => null
            ], 404);
        }

        $data_manager_witel = MaPengguna::find($data->manager_witel);
        if ($data_manager_witel->ttd_image === null) 
        {
            return response()->json([
                'status' => false,
                'message' => 'Anda Belum Set Tanda Tangan, Silahkan Set Tanda Tangan Anda Terlebih Dahulu',
                'data' => null
            ], 422);
        }

        DB::beginTransaction();
        try {
            $data->status = 'ttd_witel';
            $manager_witel = json_decode($data->manager_witel_data);
            $manager_witel->status_dokumen = 'APPROVED';
            $manager_witel->ttd_image = $data_manager_witel->ttd_image;
            $data->manager_witel_data  = json_encode($manager_witel, JSON_PRETTY_PRINT);

            $data->save();
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'success',
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'data' => $e->getMessage(),
                'success' => true,
                'message' => 'error',
            ], 400);
        }
    }

    public function parafWholesale($id)
    {
        $data = TrBaSarpen::find($id);
        if(!$data)
        {
            return response()->json([
                'status' => false,
                'message' => 'Data Tidak Ditemukan',
                'data' => null
            ], 404);
        }

        $pengguna = MaPengguna::find($data->paraf_wholesale);
        if ($pengguna->ttd_image === null) 
        {
            return response()->json([
                'status' => false,
                'message' => 'Anda Belum Set Tanda Tangan, Silahkan Set Tanda Tangan Anda Terlebih Dahulu',
                'data' => null
            ], 422);
        }

        DB::beginTransaction();
        try {
            $data->status = 'paraf_wholesale';

            $paraf_wholesale = json_decode($data->paraf_wholesale_data);
            $paraf_wholesale->status_dokumen = 'APPROVED';
            $paraf_wholesale->ttd_image = $pengguna->ttd_image;

            $data->paraf_wholesale_data  = json_encode($paraf_wholesale, JSON_PRETTY_PRINT);

            $no_dokumen = UtilityHelper::checkNomorDokumen();
            $dokumen = new MaNomorDokumen();
            $dokumen->id = Uuid::uuid4()->toString();
            $dokumen->no_dokumen = $no_dokumen;
            $dokumen->tipe_dokumen = 'SARPEN';
            $dokumen->tgl_dokumen = date('Y-m-d');
            $dokumen->save();
            $data->no_dokumen = $no_dokumen;

            $data->save();
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'success',
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'data' => $e->getMessage(),
                'success' => true,
                'message' => 'error',
            ], 400);
        }
    }

    public function ttdWholesale($id)
    {
        $data = TrBaSarpen::find($id);

        if(!$data)
        {
            return response()->json([
                'status' => false,
                'message' => 'Data Tidak Ditemukan',
                'data' => null
            ], 404);
        }

        $pengguna = MaPengguna::find($data->manager_wholesale);
        if ($pengguna->ttd_image === null) 
        {
            return response()->json([
                'status' => false,
                'message' => 'Anda Belum Set Tanda Tangan, Silahkan Set Tanda Tangan Anda Terlebih Dahulu',
                'data' => null
            ], 422);
        }

        DB::beginTransaction();
        try {
            $data->status = 'ttd_wholesale';

            $manager_wholesale = json_decode($data->manager_wholesale_data);
            $manager_wholesale->status_dokumen = 'APPROVED';
            $manager_wholesale->ttd_image = $pengguna->ttd_image;

            $data->manager_wholesale_data  = json_encode($manager_wholesale, JSON_PRETTY_PRINT);

            $data->save();
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'success',
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'data' => $e->getMessage(),
                'success' => true,
                'message' => 'error',
            ], 400);
        }
    }


    public function rejected($id)
    {
        $data = TrBaSarpen::find($id);

        if(!$data)
        {
            return response()->json([
                'status' => false,
                'message' => 'Data Tidak Ditemukan',
                'data' => null
            ], 404);
        }

        DB::beginTransaction();
        try {
            $data->status = 'rejected';
            $data->rejected_by = Auth::user()->id;

            $data->save();
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'success',
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'data' => $e->getMessage(),
                'success' => true,
                'message' => 'error',
            ], 400);
        }
    }

    public function preview($id)
    {
        $data = TrBaSarpen::with(
            [
                'pembuat', 
                'managerWholesale', 
                'managerWitel',
                'parafWholesale', 
                'towers', 
                'ruangans',
                'lahans',
                'services',
                'akseses',
                'catuDayaMcbs',
                'catuDayaGensets',
                'racks'
            ]
        )
        ->find($id);
        // $setting = MaSarpenTemplate::find($id);

        $tgl_dokumen = $data->tanggal_buat;
        $hari = date('N', strtotime($tgl_dokumen));
        $tgl = date('j', strtotime($tgl_dokumen));
        $bulan = date('n', strtotime($tgl_dokumen));
        $tahun = date('Y', strtotime($tgl_dokumen));
        $hari = date('N', strtotime($tgl_dokumen));

        $setting = json_decode($data->setting);
        $manager_witel = json_decode($data->manager_witel_data);
        $paraf_wholesale = json_decode($data->paraf_wholesale_data);
        $manager_wholesale = json_decode($data->manager_wholesale_data);

        $format_tanggal = new \stdClass();
        $format_tanggal->hari = $this->_hari[$hari-1];
        $format_tanggal->tgl = strtoupper(UtilityHelper::terbilang($tgl));
        $format_tanggal->tgl_nomor = $tgl;
        $format_tanggal->bulan = $this->_month[$bulan-1];
        $format_tanggal->tahun_nomor = $tahun;
        $format_tanggal->tahun = strtoupper(UtilityHelper::terbilang($tahun));


        $klien = json_decode($data->klien_data);
        $paraf_data = json_decode($data->paraf_data);
        $site_survey = new \stdClass();
        $site_survey->nama_site = $data->nama_site;
        $site_survey->nama_sto = $data->nama_sto;
        $site_survey->nomor_order = $data->nomor_order;
        $site_survey->alamat = $data->alamat;
        $site_survey->latitude = $data->latitude;
        $site_survey->longitude = $data->longitude;
        $site_survey->regional = $data->regional;
            


        $pdf = PDF2::loadView('sarpen', [
            'setting'           => $setting,
            'format_tanggal'    => $format_tanggal,
            'tgl_dokumen'       => $tgl_dokumen,
            'manager_wholesale' => $manager_wholesale,
            'paraf_wholesale'   => $paraf_wholesale,
            'manager_witel'     => $manager_witel,
            'klien'             => $klien,
            'site_survey'       => $site_survey,
            'status'            => $data->status,
            'towers'            => $data->towers,
            'ruangans'          => $data->ruangans,
            'lahans'            => $data->lahans,
            'services'          => $data->services,
            'akseses'           => $data->akseses,
            'catu_daya_mcbs'    => $data->catuDayaMcbs,
            'catu_daya_gensets' => $data->catuDayaGensets,
            'racks'             => $data->racks,
            'catatan'           => $data->catatan,
            'paraf_data'        => $paraf_data,
            'no_dokumen'        => $data->no_dokumen,
            'no_dokumen_klien'  => $data->no_dokumen_klien,

        ])->setPaper('a4');
        return $pdf->stream('berita_acara_sar[em.pdf');
    }

    public function uploadDokumen(Request $request, $id) 
    {
        $data = TrBaSarpen::find($id);

        if(!$data)
        {
            return response()->json([
                'status' => false,
                'message' => 'Data Tidak Ditemukan',
                'data' => null
            ], 404);
        }

        DB::beginTransaction();
        try {
            $url = $this->prosesUpload($request->file('file'));

            $data->status = 'finished';
            $data->dokumen_sirkulir = $url;
            $data->save();

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'success',
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'data' => $e->getMessage(),
                'success' => true,
                'message' => 'error',
            ], 400);
        }

    }

    public function destroy($id)
    {
        $data = TrBaSarpen::find($id);

        if(!$data)
        {
            return response()->json([
                'status' => false,
                'message' => 'Data Tidak Ditemukan',
                'data' => null
            ], 404);
        }

        $data->delete();

        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => $data
        ], 200);
    }

    public function bulkProses(Request $request)
    {
        if ($request->status == 'delete') {
            TrBaSarpen::whereIn('id', $request->ids)->delete();
        } else if ($request->status == 'proposed') {
            foreach ($request->ids as $id) {
                try {
                    DB::beginTransaction();
                    $data = TrBaSarpen::find($id);
                   
                    if ($data->manager_witel === null) 
                    {
                        DB::rollBack();
                        return response()->json([
                            'status' => false,
                            'message' => 'Terdapat Berita Sarpen Yang Belum Diisi Manager Witel',
                            'data' => null
                        ], 422);
                    }

                    $data->status = 'proposed';    
                    $data->save();
                    DB::commit();
                }  catch (\Exception $e) {
                    DB::rollback();
                    return response()->json([
                        'data' => $e->getMessage(),
                        'success' => true,
                        'message' => 'error',
                    ], 500);
                }
            }
        } else if ($request->status == 'rejected') {
            TrBaSarpen::whereIn('id', $request->ids)->update(array('status' => 'rejected'));
        } else if ($request->status == 'ttd_witel') {
            foreach ($request->ids as $id) {
                try {
                    DB::beginTransaction();
                    $data = TrBaSarpen::find($id);
                    $data->status = 'ttd_witel';
    
                    $data_manager_witel = MaPengguna::find($data->manager_witel);
                   
                    if ($data_manager_witel->ttd_image === null) 
                    {
                        DB::rollBack();
                        return response()->json([
                            'status' => false,
                            'message' => 'Anda Belum Set Tanda Tangan, Silahkan Set Tanda Tangan Anda Terlebih Dahulu',
                            'data' => null
                        ], 422);
                    }
                    
                    $manager_witel = json_decode($data->manager_witel_data);
                    $manager_witel->status_dokumen = 'APPROVED';
                    $manager_witel->ttd_image = $data_manager_witel->ttd_image;
                    $data->manager_witel_data  = json_encode($manager_witel, JSON_PRETTY_PRINT);
    
                    $data->save();
                    DB::commit();
                }  catch (\Exception $e) {
                    DB::rollback();
                    return response()->json([
                        'data' => $e->getMessage(),
                        'success' => true,
                        'message' => 'error',
                    ], 500);
                }
            }
        } else if ($request->status == 'paraf_wholesale') {
            foreach ($request->ids as $id) {
                try {
                    DB::beginTransaction();
                    $data = TrBaSarpen::find($id);
                    $data->status = 'paraf_wholesale';
    
                    $pengguna = MaPengguna::find($data->paraf_wholesale);
                   
                    if ($pengguna->ttd_image === null) 
                    {
                        DB::rollBack();
                        return response()->json([
                            'status' => false,
                            'message' => 'Anda Belum Set Tanda Tangan, Silahkan Set Tanda Tangan Anda Terlebih Dahulu',
                            'data' => null
                        ], 422);
                    }
                    
                    $data->status = 'paraf_wholesale';

                    $paraf_wholesale = json_decode($data->paraf_wholesale_data);
                    $paraf_wholesale->status_dokumen = 'APPROVED';
                    $paraf_wholesale->ttd_image = $pengguna->ttd_image;

                    $data->paraf_wholesale_data  = json_encode($paraf_wholesale, JSON_PRETTY_PRINT);

                    $no_dokumen = UtilityHelper::checkNomorDokumen();
                    $dokumen = new MaNomorDokumen();
                    $dokumen->id = Uuid::uuid4()->toString();
                    $dokumen->no_dokumen = $no_dokumen;
                    $dokumen->tipe_dokumen = 'SARPEN';
                    $dokumen->tgl_dokumen = date('Y-m-d');
                    $dokumen->save();
                    
                    $data->no_dokumen = $no_dokumen;
                    $data->save();
                    DB::commit();
                }  catch (\Exception $e) {
                    DB::rollback();
                    return response()->json([
                        'data' => $e->getMessage(),
                        'success' => true,
                        'message' => 'error',
                    ], 500);
                }
            }
        } else if ($request->status == 'ttd_wholesale') {
            foreach ($request->ids as $id) {
                try {
                    DB::beginTransaction();
                    $data = TrBaSarpen::find($id);
                    $data->status = 'ttd_wholesale';
    
                    $pengguna = MaPengguna::find($data->manager_wholesale);
                   
                    if ($pengguna->ttd_image === null) 
                    {
                        DB::rollBack();
                        return response()->json([
                            'status' => false,
                            'message' => 'Anda Belum Set Tanda Tangan, Silahkan Set Tanda Tangan Anda Terlebih Dahulu',
                            'data' => null
                        ], 422);
                    }
                    
                    $data->status = 'ttd_wholesale';

                    $manager_wholesale = json_decode($data->manager_wholesale_data);
                    $manager_wholesale->status_dokumen = 'APPROVED';
                    $manager_wholesale->ttd_image = $pengguna->ttd_image;

                    $data->manager_wholesale_data  = json_encode($manager_wholesale, JSON_PRETTY_PRINT);
                    $data->save();
                    DB::commit();
                }  catch (\Exception $e) {
                    DB::rollback();
                    return response()->json([
                        'data' => $e->getMessage(),
                        'success' => true,
                        'message' => 'error',
                    ], 500);
                }
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => []
        ], 200);

    }

    public function uploadGambar(Request $request, $id)
    {
        $v = Validator::make($request->all(), [
            'gambar' => 'required'
        ]);

        if ($v->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'error',
                'data' => $v->errors()
            ], 422);
        }


        $data = TrBaSarpen::where('id', $id)->first();

        if (!$data) {
            return response()->json([
                'status' => false,
                'message' => 'Data Tidak Ditemukan',
                'data' => null
            ], 404);
        }

        DB::beginTransaction();
        try {

            $counter = TrBaSarpenGambar::where('sarpen_id', $id)->max("no");

            $url = $this->prosesUploadLampiran($request->file('gambar'));

            $counter++;
            $data = new TrBaSarpenGambar();
            $data->sarpen_id = $id;
            $data->no = $counter;
            $data->gambar_url = $url;
            $data->save();

            DB::commit();

            return (new TrBaSarpenGambarResource($data))->additional([
                'success' => true,
                'message' => 'Data Lampiran Berhasil Diupdate',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'data' => $e->getMessage(),
                'success' => true,
                'message' => 'error',
            ], 400);
        }
    }

    public function deleteGambar($id, $no)
    {
        $data = TrBaSarpenGambar::where('sarpen_id', $id)->where('no', $no)->first();

        if(!$data)
        {
            return response()->json([
                'status' => false,
                'message' => 'Data Tidak Ditemukan',
                'data' => null
            ], 404);
        }

        $path = public_path().'/sarpen-gambar/'.$data->gambar_url;
        if(file_exists($path))
            unlink($path);

        TrBaSarpenGambar::where('sarpen_id', $id)->where('no', $no)->delete();
        
        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => $data
        ], 200);
    }

    public function gambarSarpen($name)
    {
        $storagePath = public_path().'/sarpen-gambar/'.$name;
        return response()->file($storagePath);
    }

    public function dokumenSirkulir($name)
    {
        $storagePath = public_path().'/sarpen-sirkulir/'.$name;
        return response()->file($storagePath);
    }

    private function prosesUploadLampiran($file)
    {
        $nama_file = Uuid::uuid4()->toString();


        $file->move('sarpen-gambar/', $nama_file . '.' . $file->getClientOriginalExtension());

        return $nama_file . '.' . $file->getClientOriginalExtension();
    }


    private function prosesUpload($file)
    {
        $nama_file = Uuid::uuid4()->toString();


        $file->move('sarpen-sirkulir/', $nama_file . '.' . $file->getClientOriginalExtension());

        return $nama_file . '.' . $file->getClientOriginalExtension();
    }
}
