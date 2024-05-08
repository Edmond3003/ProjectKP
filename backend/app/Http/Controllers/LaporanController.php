<?php

namespace App\Http\Controllers;

use App\Models\masterAtk;
use App\Models\headerPembelian;
use App\Models\detailPembelian;
use App\Models\headerPenjualan;
use App\Models\detailPenjualan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function getDataLaporan(Request $request)
    {
        // Validasi request
        $request->validate([
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:start_date'
        ]);

        $startDate = $request->input('tanggal_awal');
        $endDate = $request->input('tanggal_akhir');

        // Ambil data pembelian
        $dataPembelian = detailPembelian::with(['header', 'masteratk'])
            ->selectRaw('master_atks.nama_barang as nama_barang, header_pembelians.tgl as tanggal, sum(detail_pembelians.qty) as jumlah_masuk, 0 as jumlah_keluar')
            ->join('master_atks', 'detail_pembelians.id_barang', '=', 'master_atks.id')
            ->join('header_pembelians', 'detail_pembelians.hdr_pm', '=', 'header_pembelians.id')
            ->whereBetween('header_pembelians.tgl', [$startDate, $endDate])
            ->where('header_pembelians.status', 1)
            ->groupBy('master_atks.nama_barang', 'header_pembelians.tgl');

        // Ambil data penjualan
        $dataPenjualan = detailPenjualan::with(['header', 'masteratk'])
            ->selectRaw('master_atks.nama_barang as nama_barang, header_penjualans.tgl as tanggal, 0 as jumlah_masuk, sum(detail_penjualans.qty) as jumlah_keluar')
            ->join('master_atks', 'detail_penjualans.id_barang', '=', 'master_atks.id')
            ->join('header_penjualans', 'detail_penjualans.hdr_pn', '=', 'header_penjualans.id')
            ->whereBetween('header_penjualans.tgl', [$startDate, $endDate])
            ->where('header_penjualans.status', 1)
            ->groupBy('master_atks.nama_barang', 'header_penjualans.tgl');

        // Gabungkan data pembelian dan penjualan
        $dataLaporan = $dataPembelian->unionAll($dataPenjualan)->get();

        // Susun ulang data untuk memenuhi kebutuhan
        $formattedData = [];

        foreach ($dataLaporan as $laporan) {
            $key = $laporan->tanggal . '_' . $laporan->nama_barang;

            if (!isset($formattedData[$key])) {
                $formattedData[$key] = [
                    'tanggal' => $laporan->tanggal,
                    'nama_barang' => $laporan->nama_barang,
                    'jumlah_masuk' => $laporan->jumlah_masuk,
                    'jumlah_keluar' => $laporan->jumlah_keluar
                ];
            } else {
                $formattedData[$key]['jumlah_masuk'] += $laporan->jumlah_masuk;
                $formattedData[$key]['jumlah_keluar'] += $laporan->jumlah_keluar;
            }
        }

        // Ubah format array menjadi indexed array
        $result = array_values($formattedData);

        return response()->json($result);
    }

    public function getDataByTanggalNamaBarangBeli(Request $request)
    {
        // Validasi input
        $request->validate([
            'tanggal' => 'required|date',
            'nama_barang' => 'required|string',
        ]);

        // Ambil tanggal dan nama barang dari request
        $tanggal = $request->input('tanggal');
        $nama_barang = $request->input('nama_barang');

        // Query untuk mencari data berdasarkan tanggal dan nama barang
        $result = detailPembelian::with(['header.suplier'])
            ->join('header_pembelians', 'detail_pembelians.hdr_pm', '=', 'header_pembelians.id')
            ->join('master_atks', 'detail_pembelians.id_barang', '=', 'master_atks.id')
            ->join('supliers', 'header_pembelians.id_suplier', '=', 'supliers.id')
            ->selectRaw('supliers.nama_suplier as suplier, SUM(detail_pembelians.qty) as qty')
            ->where('header_pembelians.tgl', $tanggal)
            ->where('master_atks.nama_barang', $nama_barang)
            ->groupBy('supliers.nama_suplier')
            ->get();

        return response()->json($result);
    }

    public function getDataByTanggalNamaBarangJual(Request $request)
    {
        // Validasi input
        $request->validate([
            'tanggal' => 'required|date',
            'nama_barang' => 'required|string',
        ]);

        // Ambil tanggal dan nama barang dari request
        $tanggal = $request->input('tanggal');
        $nama_barang = $request->input('nama_barang');

        // Query untuk mencari data berdasarkan tanggal dan nama barang
        $result = detailPenjualan::with(['header.cabang'])
            ->join('header_penjualans', 'detail_penjualans.hdr_pn', '=', 'header_penjualans.id')
            ->join('master_atks', 'detail_penjualans.id_barang', '=', 'master_atks.id')
            ->join('cabangs', 'header_penjualans.id_cabang', '=', 'cabangs.id')
            ->selectRaw('cabangs.nama_cabang as cabang, SUM(detail_penjualans.qty) as qty')
            ->where('header_penjualans.tgl', $tanggal)
            ->where('master_atks.nama_barang', $nama_barang)
            ->groupBy('cabangs.nama_cabang')
            ->get();

        return response()->json($result);
    }
}
