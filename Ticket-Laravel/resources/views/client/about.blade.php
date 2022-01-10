@extends('layouts.app')
@section('title', 'Home')
@section('styles')
  <link href="{{ asset('vendor/select2/dist/css/select2.min.css') }}" rel="stylesheet"/>
  <style>
    .select2-container .select2-selection--single {
      display: block;
      width: 100%;
      height: calc(1.5em + .75rem + 2px);
      padding: .375rem .75rem;
      font-size: 1rem;
      font-weight: 400;
      line-height: 2;
      color: #6e707e;
      background-color: #fff;
      background-clip: padding-box;
      border: 1px solid #d1d3e2;
      border-radius: .35rem;
      transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
      color: #6e707e;
      line-height: 28px;
    }

    .select2-container .select2-selection--single .select2-selection__rendered {
      display: block;
      padding-left: 0;
      padding-right: 0;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
      margin-top: -2px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
      height: calc(1.5em + .75rem + 2px);
      position: absolute;
      top: 1px;
      right: 1px;
      width: 20px;
    }

    
  </style>
@endsection
@section('content')
<div class="card-content">
  <div class="image-content">
      <img src="img/poh1.png" alt="PO Haryanto">
  </div>
  <div class="text-content">
      <h1 style="color: rgb(9, 48, 133);"><b>Tentang PO Haryanto</b></h1>
      <p style="color: rgb(9, 48, 133);">PO Haryanto adalah salah satu perusahaan otobus antar kota antar provinsi dan pariwisata yang berbasis di Kudus, Jawa Tengah serta memiliki kantor cabang di Tangerang dan Bangak, Solo. PO Haryanto sudah cukup dikenal sejak dirintis pada tahun 2002. Nama Perusahaan Otobus (PO) ini diambil dari nama pemilik perusahaannya, Bapak Haji Haryanto. Berawal dari pekerjaannya di kesatuan angkatan udara sebagai pengemudi yang mengangkut alat berat, meriam, beras dan perminyakan, H. Haryanto kemudian memulai usaha sampingan. Usaha tersebut adalah usaha angkutan umum dan H. Haryanto juga menjadi perwakilan PO Sumber Urip. Pada 2002, beliau membuka PO Haryanto yang kini dikenal untuk kecepatannya dan memiliki banyak pelanggan setia dari kalangan anak muda.</p>
  </div>
</div>
@endsection
@section('script')
  <script src="{{ asset('vendor/select2/dist/js/select2.full.min.js') }}"></script>
  <script>
    if(jQuery().select2) {
      $(".select2").select2();
    }
  </script>
@endsection
