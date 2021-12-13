<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        $operator = new user($request->except('id'));
        $operator->save();
    }

    public function update( Request $request)
    {
        $operator = User::findOrFail($request->id);
        $operator->update($request->all());
      
        return response()->json($operator);

    }
    public function destroy(Request $request)
    {
        # code...
    }
}
