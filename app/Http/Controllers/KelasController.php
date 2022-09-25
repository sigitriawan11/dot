<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class KelasController extends Controller
{
    protected $rules = [
        'name' => 'required'
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $kelas = $request->search ? Kelas::where('name', 'like', '%' . $request->search . '%')->latest()->get() : Kelas::latest()->get();

        return response()->json([
            'status' => true,
            'data' => $kelas
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $kelas = Kelas::where('name', $request->name)->first();

        if($kelas){
            return response()->json([
                'status' => false,
                'message' => [
                    'name' => ['Data already exists']
                ]
            ]);
        }

        $validator = Validator::make($request->all(), $this->rules);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ]);
        }

        $validated = $validator->validated();
        $validated['slug'] = Str::slug($validated['name']);

        $kelas = Kelas::create($validated);

        return response()->json([
            'status' => true,
            'message' => 'Berhasil menambah data',
            'data' => $kelas
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $kelas = Kelas::where('slug', $slug)->first();

        if(!$kelas){
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        return response()->json([
            'status' => true,
            'data'  => $kelas
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
        $kelas = Kelas::where('slug', $slug)->first();

        if(!$kelas){
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        if($request->name != $kelas->name){
            $cek = Kelas::where('name', $request->name)->first();

            if($cek){
                return response()->json([
                    'status' => false,
                    'message' => [
                        "name" => ['Data already exists']
                    ]
                ]);
            }
        }

        $validator = Validator::make($request->all(), $this->rules);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ]);
        }

        $validated = $validator->validated();
        $validated['slug'] = Str::slug($validated['name']);

        $kelas->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'Berhasil merubah data',
            'data' => $kelas
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        $kelas = Kelas::where('slug', $slug)->first();

        if(!$kelas){
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        Kelas::destroy($kelas->id);

        return response()->json([
            'status' => true,
            'message' => 'Berhasil menghapus data'
        ]);
    }
}
