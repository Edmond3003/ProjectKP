<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\headerPembelian;
use App\Models\detailPembelian;
use App\Models\masterAtk;
use App\Http\Resources\HeaderPembelianResource;
use Illuminate\Support\Facades\Validator;

class headerPembelianController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $header =headerPembelian::with('suplier')->latest()->get();

        return new HeaderPembelianResource(
            true,
            'List Data Header',
            $header
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
            'id_suplier' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $header = headerPembelian::create([
            'tgl' => $request->tgl,
            'id_suplier' => $request->id_suplier,
            'status' => $request->status,
        ]);
        return new HeaderPembelianResource(true, 'Data Header
        Berhasil Ditambahkan!', $header);
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
        $header = headerPembelian::findOrfail($id);

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'Detail Data Header',
            'data'    => $header
        ], 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, headerPembelian $header)
    {
        $validator = Validator::make($request->all(), [
            'tgl' => 'required',
            'id_suplier' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $header = headerPembelian::findOrFail($header->id);

        if ($header) {

            //update buku
            $header->update([
                'tgl' => $request->tgl,
                'id_suplier' => $request->id_suplier,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Header Updated',
                'data'    => $header
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
        detailPembelian::where('hdr_pm', $id)->delete();
        $header = headerPembelian::findOrfail($id);

        if ($header) {

            $header->delete();

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

    public function verif(headerPembelian $header){
        $status = 1;
        $header = headerPembelian::findOrFail($header->id);

        if ($header) {
            // Cari detail pembelian dengan hdr_pm yang sama dengan ID header pembelian
            $details = detailPembelian::where('hdr_pm', $header->id)->get();

            // Jika detail pembelian tidak ditemukan
            if ($details->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Verifikasi gagal: Detail pembelian tidak ditemukan.',
                ], 404);
            }

            // Update status header pembelian
            $header->update([
                'status' => $status,
            ]);

            foreach ($details as $detail) {
                $masterAtk = masterAtk::findOrFail($detail->id_barang);
                $masterAtk->update([
                    'total' => $masterAtk->total + $detail->qty,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Header Updated',
                'data'    => $header
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
        $header = HeaderPembelian::with('suplier')
                        ->where('status', 0) // Filter berdasarkan status 0
                        ->latest()
                        ->get();

        return new HeaderPembelianResource(
            true,
            'List Data Header dengan Status 0',
            $header
        );
    }
}
