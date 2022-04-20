<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\debit;
use Exception;
use Log;

class DebitController extends Controller

{
    
    public function list()
    {
        $debits = Debit::where('user_id', auth()->user()->id)
            ->whereNull('deleted_at') //리스트선택삭제시 DB삭제안되게 하기위헤
            ->with('user')
            ->orderBy('id', 'desc')->paginate(5);

        // dump($debits);
        return response()->json($debits);
    }

    public function store(Request $request)
    {
        // dd(auth()->user());
        // dd($request->all());
        $validation = $request->validate([
            'amount'=>'required'
        ]);
        $user = auth()->user();
        // dd($user);
        $debit = new Debit();
        $debit->user_id = $user->id;
        $debit->amount = $validation['amount'];
        $debit->balance = $user->balance;;
        $debit->status = 0;
        $debit->save();
    
        return response()->json($debit);
    }

    public function delete(Request $request)
    {
          $debits = Debit::where('id', $request->input('id'))->first();
                      // ->update(['deleted_at' => now()]);
        try {
          if($debits->status !== 0){
            $debits->deleted_at = now();
            $debits->save();
            return response() -> json("success");
          }
          if($debits->status == 0){
              throw new Exception('error');
          } 
        } catch (\Throwable $th) {
            return response() -> json("error");
        }  
    }

    public function allDelete(Request $request)
    {
      // Log::info($request->input('ids'));
    
      if(!$request->input('ids')) {
        return response() -> json("err");
      }
      
      foreach ($request->input('ids') as $key => $value) {
        
        $debit = Debit::where('id', $value['id'])->first();
                  
        try {
          if($debit->status !== 0){
            $debit->deleted_at = now();
            $debit->save();
            // return response() -> json("success");
          }
          if($debit->status == 0){
              throw new Exception('error');
          } 
        } catch (\Throwable $th) {
            return response() -> json("error");
        }         
      }
      return response() -> json("success");
    }
    
    public function debit()
    {
        return view('debit.debit');
    }

    public function debitlist()
    {
        $debits = Debit::where('user_id', auth()->user()->id)
                        ->whereNull('deleted_at') //리스트선택삭제시 DB삭제안되게 하기위헤
                        ->with('user')
                        ->orderBy('id', 'desc')->paginate(5);

        // dump($debits);
        return view('debit.list', ['debits'=>$debits]);
    }

    // public function store(Request $request)
    // {
    //     // dd(auth()->user());
    //     // dd($request->all());
    //     $validation = $request->validate([
    //         'inputMoney'=>'required'
    //     ]);
    //     $user = auth()->user();
    //     // dd($user);
    //     $debit = new Debit();
    //     $debit->user_id = $user->id;
    //     $debit->amount = $validation['inputMoney'];
    //     $debit->balance = 0;
    //     $debit->status = 0;
    //     $debit->save();
    
    //     return redirect()->route('debit.list');
    // }

    public function massDelete(Request $request)
    {
        // dump($request->all());
        if(!$request->input('chk')) {
            // dump(1);
        
            
        return redirect() -> route('debit.list');
        }
        
        foreach ($request->input('chk') as $key => $value) {
            // dump($value);
            Debit::wherein('id', $request->input('chk')) //where은 다중선택삭제가 안됨
                    -> update(['deleted_at' => now()]);
            

        return redirect() -> route('debit.list');
            
        }
    }

    // public function delete(Request $request)
    // {
    //     // dd($request->all());
    //     Debit::where('id', $request->input('id'))
    //              ->update(['deleted_at' => now()]);
    //     // dump($request->all());
    //     return redirect() -> route('debit.list');
    // }
}