<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\types_work;
use App\status;
use App\order;
use App\order_work;
use App\User;

class OrderController extends Controller
{
  
   public function SaveTypesWork( Request $request )
   {
      $validatedData = Validator::make( $request->all(),[
         'code' => 'unique:types_work,code',
     ],
     [
         'code.unique' => 'El Código ya exsite .',
     ]);
     if ($validatedData->fails()) {
        return response()->json(['data'=>$validatedData->messages()->all()[0]],422);
     }
    $types_work = new types_work($request->all());
    $types_work->save();
   }

   public function SaveOrderWork(Request $request)
   {
      $validatedData = Validator::make( $request->all(),[
         'order_number' => 'unique:order_work,order_number',
     ],
     [
         'order_number.unique' => 'El numero de orden ya exsite .',
     ]);
     if ($validatedData->fails()) {
        return response()->json(['data'=>$validatedData->messages()->all()[0]],422);
     }
     $order=new  order_work($request->all());
     $order->save();

   }

   public function SaveWorkOrderAssignment( Request $request)
   {
     order::create([
        'order_work_id' => $request->order_work_id,
        'users_id'=> $request->users_id,
        'date_assignment' =>$request->date_assignment
     ]);
     
      $order = order_work::where('id',$request->order_work_id)->first();
      $order->status_id = $request->status_id;
      $order->save();
   }
   public function Getsupplement()
   {

      $status =  status::get(['id', 'name']);
      $types_work =types_work::get(['id', 'name']);
      $operator = User::get(['id', 'name']);
      $order_works =order_work::all();
      $order_work=[];
      foreach ($order_works as $key => $value) {

          array_push($order_work,[
                'id'=> $value['id'],
                'code_order'=> $value['number_code']
          ]);
      }

      return response()->json([
                'status'=>$status,
                'types_work'=>$types_work,
                'operator'=>$operator,
                'order_work'=>$order_work
            ],200);

   }

   public function getOrder()
   {
    $order = order::with(['orderwork.ordertype','orderwork.status','operator'])->paginate(10);
    return response($order,200);
   }

   public function GetDateOrderWork( Request $request)
   {
      $order_work = order_work::where('id',$request->id)->get(['created_at']);
      return response()->json([
            'date'=>$order_work
           ],200);
   }

   public function UpdateOrder( Request $request)
   {
       $order = order::findOrFail($request->id);
       $order->update($request->only('date_execution','value_work'));
    
       $order_work = order_work::where('id',$request->id_order)->first();
       $order_work->status_id = $request->status_id;
       $order_work->save();
   }
   
}
