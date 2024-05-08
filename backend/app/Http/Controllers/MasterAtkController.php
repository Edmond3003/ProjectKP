<?php

namespace App\Http\Controllers;

use App\Models\masterAtk;
use Illuminate\Http\Request;
use App\Http\Resources\BarangResource;
use Illuminate\Support\Facades\Validator;



class MasterAtkController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $master =masterAtk::with('kelompok')->latest()->get();

        return new BarangResource(
            true,
            'List Data Jadwal',
            $master
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
        $total = 0;
        $validator = Validator::make($request->all(), [
            'nama_barang' => 'required|unique:master_atks',
            'id_kelompok' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $master = masterAtk::create([
            'nama_barang' => $request->nama_barang,
            'id_kelompok' => $request->id_kelompok,
            'total' => $total,
        ]);
        return new BarangResource(true, 'Data Barang
        Berhasil Ditambahkan!', $master);
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
        $master = masterAtk::findOrfail($id);

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'Detail Data Buku',
            'data'    => $master
        ], 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, masterAtk $master)
    {
        $validator = Validator::make($request->all(), [
            'nama_barang' => 'required',
            'id_kelompok' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $master = masterAtk::findOrFail($master->id);

        if ($master) {

            //update buku
            $master->update([
                'nama_barang' => $request->nama_barang,
                'id_kelompok' => $request->id_kelompok,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Barang Updated',
                'data'    => $master
            ], 200);
        }

        //data departemen not found
        return response()->json([
            'success' => false,
            'message' => 'Barang Not Found',
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
        $master = masterAtk::findOrfail($id);

        if ($master) {

            $master->delete();

            return response()->json([
                'success' => true,
                'message' => 'Barang Deleted',
            ], 200);
        }

        //data departemen not found
        return response()->json([
            'success' => false,
            'message' => 'Barang Not Found',
        ], 404);
    }
}
