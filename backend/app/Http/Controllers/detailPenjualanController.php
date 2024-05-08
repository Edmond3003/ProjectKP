<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\detailPenjualan;
use App\Http\Resources\DetailPenjualanResource;
use Illuminate\Support\Facades\Validator;

class detailPenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $detail =detailPenjualan::with('masteratk', 'header')->latest()->get();

        return new DetailPenjualanResource(
            true,
            'List Data Detail',
            $detail
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
            'hdr_pn' => 'required',
            'id_barang' => 'required',
            'qty' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $detail = detailPenjualan::create([
            'hdr_pn' => $request->hdr_pn,
            'id_barang' => $request->id_barang,
            'qty' => $request->qty,
        ]);
        return new DetailPenjualanResource(true, 'Data Header
        Berhasil Ditambahkan!', $detail);
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
        $detail = detailPenjualan::findOrfail($id);

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'Detail Data detail pembelian',
            'data'    => $detail
        ], 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, detailPenjualan $detailjual)
    {
        $validator = Validator::make($request->all(), [
            'hdr_pn' => 'required',
            'id_barang' => 'required',
            'qty' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $detailjual = detailPenjualan::findOrFail($detailjual->id);

        if ($detailjual) {

            //update buku
            $detailjual->update([
                'hdr_pn' => $request->hdr_pn,
                'id_barang' => $request->id_barang,
                'qty' => $request->qty,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Detail Updated',
                'data'    => $detailjual
            ], 200);
        }

        //data departemen not found
        return response()->json([
            'success' => false,
            'message' => 'Detail Not Found',
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
        $detailjual = detailPenjualan::findOrfail($id);

        if ($detailjual) {

            $detailjual->delete();

            return response()->json([
                'success' => true,
                'message' => 'Detail Deleted',
            ], 200);
        }

        //data departemen not found
        return response()->json([
            'success' => false,
            'message' => 'Detail Not Found',
        ], 404);
    }

    public function showDetail($hdr_pn)
    {
        try {
            // Mengambil data detail pembelian berdasarkan id_header dengan relasi ke header dan barang
            $details = detailPenjualan::with(['header', 'masteratk'])
                ->where('hdr_pn', $hdr_pn)
                ->get();

            // Jika data ditemukan
            if ($details->count() > 0) {
                return new DetailPenjualanResource(
                    true,
                    'List Data Detail',
                    $details
                );
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Detail pembelian tidak ditemukan',
                    'data' => null
                ], 404);
            }
        } catch (\Exception $e) {
            // Jika terjadi error
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
