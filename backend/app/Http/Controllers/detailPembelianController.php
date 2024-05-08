<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\detailPembelian;
use App\Http\Resources\DetailPembelianResource;
use Illuminate\Support\Facades\Validator;

class detailPembelianController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $detail =detailPembelian::with('masteratk', 'header')->latest()->get();

        return new DetailPembelianResource(
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
            'hdr_pm' => 'required',
            'id_barang' => 'required',
            'qty' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $detail = detailPembelian::create([
            'hdr_pm' => $request->hdr_pm,
            'id_barang' => $request->id_barang,
            'qty' => $request->qty,
        ]);
        return new DetailPembelianResource(true, 'Data Header
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
        $detail = detailPembelian::findOrfail($id);

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
    public function update(Request $request, detailPembelian $detail)
    {
        $validator = Validator::make($request->all(), [
            'hdr_pm' => 'required',
            'id_barang' => 'required',
            'qty' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $detail = detailPembelian::findOrFail($detail->id);

        if ($detail) {

            //update buku
            $detail->update([
                'hdr_pm' => $request->hdr_pm,
                'id_barang' => $request->id_barang,
                'qty' => $request->qty,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Detail Updated',
                'data'    => $detail
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
        $detail = detailPembelian::findOrfail($id);

        if ($detail) {

            $detail->delete();

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

    public function showDetail($hdr_pm)
    {
        try {
            // Mengambil data detail pembelian berdasarkan id_header dengan relasi ke header dan barang
            $details = detailPembelian::with(['header', 'masteratk'])
                ->where('hdr_pm', $hdr_pm)
                ->get();

            // Jika data ditemukan
            if ($details->count() > 0) {
                return new DetailPembelianResource(
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
