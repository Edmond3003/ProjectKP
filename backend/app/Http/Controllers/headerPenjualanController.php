<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\headerPenjualan;
use App\Models\detailPenjualan;
use App\Models\masterAtk;
use App\Http\Resources\HeaderPenjualanResource;
use Illuminate\Support\Facades\Validator;

class headerPenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $headerjual =headerPenjualan::with('cabang')->latest()->get();

        return new HeaderPenjualanResource(
            true,
            'List Data Header',
            $headerjual
        );
    }




    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tgl' => 'required',
            'id_cabang' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $headerjual = headerPenjualan::create([
            'tgl' => $request->tgl,
            'id_cabang' => $request->id_cabang,
            'status' => $request->status,
        ]);
        return new HeaderPenjualanResource(true, 'Data Header
        Berhasil Ditambahkan!', $headerjual);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //find post by ID
        $headerjual = headerPenjualan::findOrfail($id);

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'Detail Data Header',
            'data'    => $headerjual
        ], 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, headerPenjualan $headerjual)
    {
        $validator = Validator::make($request->all(), [
            'tgl' => 'required',
            'id_cabang' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $header = headerPenjualan::findOrFail($headerjual->id);

        if ($headerjual) {

            //update buku
            $headerjual->update([
                'tgl' => $request->tgl,
                'id_cabang' => $request->id_cabang,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Header Updated',
                'data'    => $headerjual
            ], 200);
        }

        //data departemen not found
        return response()->json([
            'success' => false,
            'message' => 'Header Not Found',
        ], 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        detailPenjualan::where('hdr_pn', $id)->delete();
        $headerjual = headerPenjualan::findOrfail($id);

        if ($headerjual) {

            $headerjual->delete();

            return response()->json([
                'success' => true,
                'message' => 'Header Deleted',
            ], 200);
        }

        //data departemen not found
        return response()->json([
            'success' => false,
            'message' => 'Header Not Found',
        ], 404);
    }

    public function verif(headerPenjualan $headerjual){
        $status = 1;
        $headerjual = headerPenjualan::findOrFail($headerjual->id);

        if ($headerjual) {
            // Cari detail pembelian dengan hdr_pm yang sama dengan ID header pembelian
            $detailjuals = detailPenjualan::where('hdr_pn', $headerjual->id)->get();

            // Jika detail pembelian tidak ditemukan
            if ($detailjuals->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Verifikasi gagal: Detail penjualan tidak ditemukan.',
                ], 404);
            }

            // Update status header pembelian
            $headerjual->update([
                'status' => $status,
            ]);

            foreach ($detailjuals as $detailjual) {
                $masterAtk = masterAtk::findOrFail($detailjual->id_barang);
                $masterAtk->update([
                    'total' => $masterAtk->total - $detailjual->qty,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Header Updated',
                'data'    => $headerjual
            ], 200);
        }

        // Data header pembelian tidak ditemukan
        return response()->json([
            'success' => false,
            'message' => 'Header Not Found',
        ], 404);
    }

    public function cariVerif()
    {
        $headerjual = headerPenjualan::with('cabang')
                        ->where('status', 0) // Filter berdasarkan status 0
                        ->latest()
                        ->get();

        return new HeaderPenjualanResource(
            true,
            'List Data Header dengan Status 0',
            $headerjual
        );
    }
}
