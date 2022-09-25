<?php

namespace App\Http\Controllers;

use App\Models\Hobby;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

use function PHPUnit\Framework\returnValueMap;

class HobbyController extends Controller
{
    protected $rules = [
        'siswa_id' => 'required|numeric',
        'name' => 'required',
        'nis' => 'required'
    ];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $hobby = Hobby::latest()->get();

        return response()->json([
            'status' => true,
            'data' => $hobby
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
        $validated['code_hobby_user'] = 'HOBBY' . Str::random(10);

        $hobby = Hobby::create($validated);

        return response()->json([
            'status' => true,
            'message' => 'Berhasil menambah data',
            'data' => $hobby
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($code_hobby_user)
    {
        $hobby = Hobby::where('code_hobby_user', $code_hobby_user)->first();

        if(!$hobby){
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => $hobby
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($code_hobby_user)
    {
        $hobby = Hobby::where('code_hobby_user', $code_hobby_user)->first();

        if(!$hobby){
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => $hobby
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $code_hobby_user)
    {
        $hobby = Hobby::where('code_hobby_user', $code_hobby_user)->first();

        if(!$hobby){
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
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

        $hobby->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'Berhasil merubah data',
            'data' => $hobby
        ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($code_hobby_user)
    {
        $hobby = Hobby::where('code_hobby_user', $code_hobby_user)->first();

        if(!$hobby){
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        Hobby::destroy($hobby->id);

        return response()->json([
            'status' => true,
            'message' => 'Berhasil menghapus data'
        ]);
    }
}
