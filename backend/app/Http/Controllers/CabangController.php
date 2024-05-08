<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cabang;
use App\Http\Resources\CabangResource;
use Illuminate\Support\Facades\Validator;

class CabangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cabang = Cabang::latest()->get();
        //render view with posts
        return new CabangResource(
            true,
            'List Data Cabang',
            $cabang
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
            'nama_cabang' => 'required',
            'alamat_cab' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $cabang = Cabang::create([
            'nama_cabang' => $request->nama_cabang,
            'alamat_cab' => $request->alamat_cab,
        ]);
        return new CabangResource(true, 'Data Cabang
        Berhasil Ditambahkan!', $cabang);
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
        $cabang = Cabang::findOrfail($id);

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'Detail Data cabang',
            'data'    => $cabang
        ], 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cabang $cabang)
   {
        $validator = Validator::make($request->all(), [
            'nama_cabang' => 'required',
            'alamat_cab' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $cabang = Cabang::findOrFail($cabang->id);

        if ($cabang) {

            //update buku
            $cabang->update([
                'nama_cabang' => $request->nama_cabang,
                'alamat_cab' => $request->alamat_cab,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cabang Updated',
                'data'    => $cabang
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
        $cabang = Cabang::findOrfail($id);

        if ($cabang) {

            $cabang->delete();

            return response()->json([
                'success' => true,
                'message' => 'Cabang Deleted',
            ], 200);
        }

        //data departemen not found
        return response()->json([
            'success' => false,
            'message' => 'Cabang Not Found',
        ], 404);
    }
}
