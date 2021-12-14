<?php

namespace App\Http\Controllers;

use App\order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;

class OperatorController extends Controller
{
    public function get()
    {
        $operator = User::paginate(10);
    
        return response( $operator,200);        
    }
    public function store ( Request $request)
    {
        $validatedData = Validator::make( $request->all(),[
            'code' => 'unique:users,code',
            'cc'    =>'unique:users,cc'
        ],
        [
            'code.unique' => 'El Código ya exsite .',
            'cc.unique' => 'La Identificación ya exsite .',
        ]);
        if ($validatedData->fails()) {
           return response()->json(['data'=>$validatedData->messages()->all()[0]],422);
        }
        $operator = new user($request->except('id'));
        $operator->save();


    }

    public function update( Request $request)
    {
        $validatedData = Validator::make( $request->all(),[
            'code' => 'unique:users,code,'.$request->id,
            'cc'    =>'unique:users,cc,'.$request->id
        ],
        [
            'code.unique' => 'El Código ya exsite .',
            'cc.unique' => 'La Identificación ya exsite .',
        ]);
        if ($validatedData->fails()) {
           return response()->json(['data'=>$validatedData->messages()->all()[0]],422);
        }
        $operator = User::findOrFail($request->id);
        $operator->update($request->all());
    }
    public function destroy(Request $request)
    {
         $order = order::where('users_id',$request->id)->exists();
         if(!$order){
            $operator = User::find($request->id);
            $operator->delete();
         }else {
            return response()->json(['data'=>[]],422);
         }
    }
}
