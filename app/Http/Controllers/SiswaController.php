<?php

namespace App\Http\Controllers;

use App\Models\Hobby;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SiswaController extends Controller
{
    protected $rules = [
        'nis' => 'required|unique:siswas|numeric',
        'name' => 'required',
        'phone' => 'required|numeric|unique:siswas',
        'address' => 'required',
        'date' => 'required|date',
        'kelas_id' => 'required|numeric'
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $siswa = $request->search ? Siswa::where('name', 'like', '%' . $request->search . '%')
                                    ->orWhere('nis', 'like', '%' . $request->search . '%')->latest()->get()
                                    : Siswa::latest()->get();

        return response()->json([
            'status' => true,
            'data' => $siswa
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
        $validator = Validator::make($request->all(), $this->rules);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ]);
        }

        $validated = $validator->validated();

        $siswa = Siswa::create($validated);

        return response()->json([
            'status' => true,
            'message' => 'Berhasil menambah data',
            'data' => $siswa
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($nis)
    {
        $siswa = Siswa::where('nis', $nis)->first();

        if(!$siswa){
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        return response()->json([
            'status' => true,
            'data'  => $siswa
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($nis)
    {
        $siswa = Siswa::where('nis', $nis)->first();

        if(!$siswa){
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        return response()->json([
            'status' => true,
            'data'  => $siswa
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $nis)
    {
        $siswa = Siswa::where('nis', $nis)->first();

        if(!$siswa){
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        $rules = $this->rules;

        if($request->nis == $siswa->nis){
            $rules['nis'] = 'required|numeric';
        }
        if($request->phone == $siswa->phone){
            $rules['phone'] = 'required|numeric';
        }

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ]);
        }

        $validated = $validator->validated();

        $siswa->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'Berhasil merubah data',
            'data' => $siswa
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($nis)
    {
        $siswa = Siswa::where('nis', $nis)->first();

        if(!$siswa){
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        $hobbies = Hobby::where('nis', $nis)->get();

        if($hobbies){
            foreach($hobbies as $hobby){
                Hobby::destroy($hobby->id);
            }
        }


        Siswa::destroy($siswa->id);

        return response()->json([
            'status' => true,
            'message' => 'Berhasil menghapus data'
        ]);
    }
}
