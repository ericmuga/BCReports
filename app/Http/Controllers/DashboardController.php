<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{


   public function getLocalSales(Request $request)
   {
      $request->validate([
                           'to'=>'required',
                           'from'=>'required'
                         ]);

        //get last invoice from main


        $exclude_PG=['HD-PACKGNG','HD-MISCELN','JF-MISCBEF','JF-MISCPRK'];


    $S_lines=DB::connection('sales')
                    ->table('FCL$Sales Invoice Line')
                    ->selectRaw('[Posting Group],SUM([Quantity (Base)]) as Quantity')
                    ->where('FCL$Sales Invoice Header.Posting Date',$request->to)
                    ->where('Type',2)
                    ->whereNotIn('Posting Group',$exclude_PG)
                    ->join('FCL$Sales Invoice Header','Document No_','=','FCL$Sales Invoice Header.No_')
                    ->where('FCL$Sales Invoice Header.Salesperson Code','<>','270')
                    ->groupBy('Posting Group')
                    ->get();

       $M_lines=DB::connection('main')
                ->table('FCL$Sales Invoice Line')
                ->selectRaw('[Posting Group],SUM([Quantity (Base)]) as Quantity')
                ->where('FCL$Sales Invoice Header.Posting Date','>=',$request->from)
                ->where('FCL$Sales Invoice Header.Posting Date','<=',Carbon::parse($request->to)->subDays(1))
                ->where('Type',2)
                ->whereNotIn('Posting Group',$exclude_PG)
                ->join('FCL$Sales Invoice Header','Document No_','=','FCL$Sales Invoice Header.No_')
                ->where('FCL$Sales Invoice Header.Salesperson Code','<>','270')
                ->groupBy('Posting Group')
                ->get();

        $M_CNLines=DB::connection('main')
                    ->table('FCL$Sales Cr_Memo Line')
                    ->selectRaw('[Posting Group],SUM([Quantity (Base)]) as Quantity')
                    ->where('FCL$Sales Cr_Memo Header.Posting Date','>=',$request->from)
                    ->where('FCL$Sales Cr_Memo Header.Posting Date','<=',Carbon::parse($request->to)->subDays(1))
                    ->where('Type',2)
                    ->whereNotIn('Posting Group',$exclude_PG)
                    ->join('FCL$Sales Cr_Memo Header','Document No_','=','FCL$Sales Cr_Memo Header.No_')
                    ->where('FCL$Sales Cr_Memo Header.Salesperson Code','<>','270')
                    ->groupBy('Posting Group')
                    ->get();

                    foreach ($M_lines as $key => $value)
                    {
                        $value->Quantity-=$M_CNLines->get($key)?$M_CNLines->get($key)->Quantity:0;

                    }

      // Main, fetch anything that doesn't come from Sales server from day -1



      dd($M_lines);

   }

    public function index(Request $request)

    {
            return inertia('Dashboard');

    }
}
