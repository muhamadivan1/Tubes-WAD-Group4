<?php

namespace App\Http\Controllers;

use App\Models\Rute;
use App\Models\Category;
use App\Models\Pemesanan;
use App\Models\Transportasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class PemesananController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() //mengarahkan ke view index client dengan data dari model Rute
    {
        $ruteAwal = Rute::orderBy('start')->get()->groupBy('start');
        if (count($ruteAwal) > 0) { //mensortir berdasarkan rute awal
            foreach ($ruteAwal as $key => $value) {
                $data['start'][] = $key;
            }
        } else {
            $data['start'] = [];
        }
        $ruteAkhir = Rute::orderBy('end')->get()->groupBy('end');
        if (count($ruteAkhir) > 0) {    //mensortir berdasarkan rute akhir
            foreach ($ruteAkhir as $key => $value) {
                $data['end'][] = $key;
            }
        } else {
            $data['end'] = [];
        }
        $category = Category::orderBy('name')->get();   //mengambil seluruh data category
        return view('client.index', compact('data', 'category'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function about()
    {
        return view('client.about');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->category) {   //memanggil category berdasarakan request user
            $category = Category::find($request->category);
            $data = [
                'start' => $request->start,
                'end' => $request->end,
                'category' => $category->id,
                'waktu' => $request->waktu,
            ];
            $data = Crypt::encrypt($data);
            return redirect()->route('show', ['id' => $category->slug, 'data' => $data]);
        } else {    //memvalidasi rute_id dan waktu harus diisi oleh user
            $this->validate($request, [
                'rute_id' => 'required',
                'waktu' => 'required',
            ]);
            // membuat kode pemesanan acak
            $huruf = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
            $kodePemesanan = strtoupper(substr(str_shuffle($huruf), 0, 7));

            $rute = Rute::with('transportasi.category')->find($request->rute_id);
            // $jumlah_kursi = $rute->transportasi->jumlah + 2;
            // $kursi = (int) floor($jumlah_kursi / 5);
            // $kode = "ABCDE";
            // $kodeKursi = strtoupper(substr(str_shuffle($kode), 0, 1) . rand(1, $kursi));

            $waktu = $request->waktu . " " . $rute->jam;

            Pemesanan::Create([ 
                'kode' => $kodePemesanan,
                // 'kursi' => $request,
                'waktu' => $waktu,
                'total' => $rute->harga,
                'rute_id' => $rute->id,
                'penumpang_id' => Auth::user()->id
            ]);
            // mengarahkan kembali ke menu sebelumnya dengan status sukses
            return redirect()->back()->with('success', 'Pemesanan Tiket ' . $rute->transportasi->category->name . ' Success!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, $data)    //menampilkan data ke table
    {
        $data = Crypt::decrypt($data);
        $category = Category::find($data['category']);
        $rute = Rute::with('transportasi')->where('start', $data['start'])->where('end', $data['end'])->get();
        if ($rute->count() > 0) {   //mensortir data yg ingin ditampilkan
            foreach ($rute as $val) {
                $pemesanan = Pemesanan::where('rute_id', $val->id)->where('waktu')->count();
                if ($val->transportasi) {   //mensortir data yg ingin ditampilkan
                    $kursi = Transportasi::find($val->transportasi_id)->jumlah - $pemesanan;
                    if ($val->transportasi->category_id == $category->id) { //mensortir data yg ingin ditampilkan
                        $dataRute[] = [
                            'harga' => $val->harga,
                            'start' => $val->start,
                            'end' => $val->end,
                            'tujuan' => $val->tujuan,
                            'transportasi' => $val->transportasi->name,
                            'kode' => $val->transportasi->kode,
                            'kursi' => $kursi,
                            'waktu' => $data['waktu'],
                            'id' => $val->id,
                        ];
                    }
                }
            }
            sort($dataRute);
        } else {
            $dataRute = [];
        }
        $id = $category->name;
        return view('client.show', compact('id', 'dataRute'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)   //menapilkan data yg ingin diedit berdasarkan id
    {
        $data = Crypt::decrypt($id); //crypt:untuk mendecript suatu id
        $rute = Rute::find($data['id']); //rute:model 
        $transportasi = Transportasi::find($rute->transportasi_id);
        return view('client.kursi', compact('data', 'transportasi'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function pesan($kursi, $data)    //menambahkan data ke table pemesanan
    {
        $d = Crypt::decrypt($data);
        $huruf = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
        $kodePemesanan = strtoupper(substr(str_shuffle($huruf), 0, 7));

        $rute = Rute::with('transportasi.category')->find($d['id']);

        $waktu = $d['waktu'] . " " . $rute->jam;

        Pemesanan::Create([
            'kode' => $kodePemesanan,
            'kursi' => $kursi,
            'waktu' => $waktu,
            'total' => $rute->harga,
            'rute_id' => $rute->id,
            'penumpang_id' => Auth::user()->id
        ]);

        return redirect('/')->with('success', 'Pemesanan Tiket ' . $rute->transportasi->category->name . ' Success!');
    }
}
