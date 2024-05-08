<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Suplier;
use App\Http\Resources\SuplierResource;
use Illuminate\Support\Facades\Validator;

class SuplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $suplier = Suplier::latest()->get();
        //render view with posts
        return new SuplierResource(
            true,
            'List Data Suplier',
            $suplier
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
            'nama_suplier' => 'required',
            'alamat' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $suplier = Suplier::create([
            'nama_suplier' => $request->nama_suplier,
            'alamat' => $request->alamat,
        ]);
        return new SuplierResource(true, 'Data Suplier
        Berhasil Ditambahkan!', $suplier);
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
        $suplier = Suplier::findOrfail($id);

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'Detail Data Suplier',
            'data'    => $suplier
        ], 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Suplier $suplier)
    {
        $validator = Validator::make($request->all(), [
            'nama_suplier' => 'required',
            'alamat' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $suplier = Suplier::findOrFail($suplier->id);

        if ($suplier) {

            //update buku
            $suplier->update([
                'nama_suplier' => $request->nama_suplier,
                'alamat' => $request->alamat,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Suplier Updated',
                'data'    => $suplier
            ], 200);
        }

        //data departemen not found
        return response()->json([
            'success' => false,
            'message' => 'Suplier Not Found',
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
        $suplier = Suplier::findOrfail($id);

        if ($suplier) {

            $suplier->delete();

            return response()->json([
                'success' => true,
                'message' => 'Suplier Deleted',
            ], 200);
        }

        //data departemen not found
        return response()->json([
            'success' => false,
            'message' => 'Suplier Not Found',
        ], 404);
    }
}
