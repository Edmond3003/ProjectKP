<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kelompok;
use App\Http\Resources\KelompokResource;
use Illuminate\Support\Facades\Validator;

class KelompokController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kelompok = Kelompok::latest()->get();
        //render view with posts
        return new KelompokResource(
            true,
            'List Data Kelompok',
            $kelompok
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
            'nama_kelompok' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $kelompok = Kelompok::create([
            'nama_kelompok' => $request->nama_kelompok,
        ]);
        return new KelompokResource(true, 'Data Kelompok
        Berhasil Ditambahkan!', $kelompok);
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
        $kelompok = Kelompok::findOrfail($id);

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'Detail Data Kelompok',
            'data'    => $kelompok
        ], 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Kelompok $kelompok)
    {
        $validator = Validator::make($request->all(), [
            'nama_kelompok' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $kelompok = Kelompok::findOrFail($kelompok->id);

        if ($kelompok) {

            //update buku
            $kelompok->update([
                'nama_kelompok' => $request->nama_kelompok,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Kelompok Updated',
                'data'    => $kelompok
            ], 200);
        }

        //data departemen not found
        return response()->json([
            'success' => false,
            'message' => 'Kelompok Not Found',
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
        $kelompok = Kelompok::findOrfail($id);

        if ($kelompok) {

            $kelompok->delete();

            return response()->json([
                'success' => true,
                'message' => 'Kelompok Deleted',
            ], 200);
        }

        //data departemen not found
        return response()->json([
            'success' => false,
            'message' => 'Kelompok Not Found',
        ], 404);
    }
}
