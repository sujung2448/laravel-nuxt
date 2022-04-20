<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\credit;
use Exception;
use Log;

class CreditController extends Controller

{

    public function list()
    {
        $credits = Credit::where('user_id', auth()->user()->id)
            ->whereNull('deleted_at') //리스트선택삭제시 DB삭제안되게 하기위헤
            ->with('user')
            ->orderBy('id', 'desc')->paginate(5);

        return response() -> json($credits);
    }

    public function delete(Request $request)
    {
          $credits = Credit::where('id', $request->input('id'))->first();
                      // ->update(['deleted_at' => now()]);
        try {
          if($credits->status !== 0){
            $credits->deleted_at = now();
            $credits->save();
            return response() -> json("success");
          }
          if($credits->status == 0){
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
        
        $credit = Credit::where('id', $value['id'])->first();
                  
        try {
          if($credit->status !== 0){
            $credit->deleted_at = now();
            $credit->save();
            // return response() -> json("success");
          }
          if($credit->status == 0){
              throw new Exception('error');
          } 
        } catch (\Throwable $th) {
            return response() -> json("error");
        }         
      }
      return response() -> json("success");
    }

    

    public function credit()
    {
        return view('credit.credit');
    }

    public function creditlist()
    {
        $credits = Credit::where('user_id', auth()->user()->id)
            ->whereNull('deleted_at') //리스트선택삭제시 DB삭제안되게 하기위헤
            ->with('user')
            ->orderBy('id', 'desc')->paginate(5);

        // dump($credits);
        return view('credit.list', ['credits'=>$credits]);
    }

    public function store(Request $request)
    {
        // dump(auth()->user());
        // dd($request->all());
        // sleep(5);
        $validation = $request->validate([
            'amount'=>'required'
        ]);
        $user = auth()->user();
        // dd($user);
        $credit = new Credit();
        $credit->user_id = $user->id;
        $credit->amount = $validation['amount'];
        $credit->balance = $user->balance;
        $credit->status = 0;
        $credit->save();

        return response()->json($credit);
    }

    public function massDelete(Request $request)
    {
        // dump($request->all());
        if(!$request->input('chk')) {
            // dump(1);
        
            
        return redirect() -> route('credit.list');
        }
        
        foreach ($request->input('chk') as $key => $value) {
            // dump($value);
            Credit::wherein('id', $request->input('chk')) //where은 다중선택삭제가 안됨
                    -> update(['deleted_at' => now()]);
            

        return redirect() -> route('credit.list');
            
        }
    }

    // public function delete(Request $request)
    // {
    //     // dd($request->all());
    //     Credit::where('id', $request->input('id'))
    //              ->update(['deleted_at' => now()]);
    //     // dump($request->all());
    //     return redirect() -> route('credit.list');
    // }


}







// return view('users', ['users'=>$users]);