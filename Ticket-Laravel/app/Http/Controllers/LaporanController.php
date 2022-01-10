<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    public function index() //mengarahkan ke laporan pemesanan
    {
        $pemesanan = Pemesanan::with('rute', 'penumpang')->orderBy('created_at', 'desc')->get();
        return view('server.laporan.index', compact('pemesanan'));
    }

    public function petugas()   //mengarahkan view ke petugas
    {
        return view('client.petugas');
    }

    public function kode(Request $request)  //mengarahkan ke route show
    {
        return redirect()->route('transaksi.show', $request->kode);
    }

    public function show($id)   //menampilkan/memanggil data berdasarkan id
    {
        $data = Pemesanan::with('rute.transportasi.category', 'penumpang')->where('kode', $id)->first();
        if ($data) {
            return view('server.laporan.show', compact('data'));
        } else {
            return redirect()->back()->with('error', 'Kode Transaksi Tidak Ditemukan!');
        }
    }

    public function pembayaran($id) //memasukkan data pembayaran
    {
        Pemesanan::find($id)->update([
            'status' => 'Sudah Bayar',
            'petugas_id' => Auth::user()->id
        ]);

        return redirect()->back()->with('success', 'Pembayaran Ticket Success!');
    }

    public function history()   //memanggil data history
    {
        $pemesanan = Pemesanan::with('rute.transportasi')->where('penumpang_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();
        return view('client.history', compact('pemesanan'));
    }
}
