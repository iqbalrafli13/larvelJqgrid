<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class ReportController extends Controller
{

    public function data_report(Request $request)
    {
        $search="";
        if (isset($request->global_search) && strlen($request->global_search)) {
            $global_search = $request->global_search;
            $search .=" transactions.nofaktur like '%$global_search%' or";
            $search .=" transactions.tanggal like '%$global_search%' or";
            $search .=" transactions.nama like '%$global_search%' or";
            $search .=" genders.nama like '%$global_search%' or";
            $search .=" transactions.phone like '%$global_search%' or";
            $search .=" transactions.address like '%$global_search%' or";
            $search .=" transactions.saldo like '%$global_search%' ";
        }else{
            if(isset($request->nofaktur)){
                $nofaktur = $request->nofaktur;
                $search .=" transactions.nofaktur like '%$nofaktur%' and";
            }
            if(isset($request->tanggal)){
                $tanggal = date("Y-m-d", strtotime($request->tanggal));
                $search .=" transactions.tanggal = '$tanggal' and";
            }
            if(isset($request->nama)){
                $nama = $request->nama;
                $search .=" transactions.nama like '%$nama%' and";
            }
            if(isset($request->gender)){
                $gender = $request->gender;
                $search .=" transactions.gender_id = '$gender' and";
            }
            if(isset($request->phone)){
                $phone = $request->phone;
                $search .=" transactions.phone like '%$phone%' and";
            }
            if(isset($request->address)){
                $address = $request->address;
                $search .=" transactions.address like '%$address%' and";
            }
            if(isset($request->saldo)){
                $saldo = $request->saldo;
                $search .=" transactions.saldo like '%$saldo%' and";
            }
            $search .= " 1 ";
        }
        $page = $request->page;
        $limit = $request->rows;
        $sidx = $request->sidx;
        $sord = $request->sord;

        if (! $sidx){
            $sidx = 1;
        }
        if ($sidx == 'gender'){
            $sidx = 'gender_id';
        }
        $query = Transaction::orderBy($sidx, $sord);
        $count= $query->count();
        if ($count > 0 && $limit > 0) {
            $total_pages = ceil($count / $limit);
        } else {
            $total_pages = 0;
        }
        if ($page > $total_pages)
            $page = $total_pages;
        $start = $limit * $page - $limit;
        if ($start < 0)  $start = 0;

        $transactions = $query->select('transactions.*','genders.nama as gender')
        ->leftJoin('genders', function($join) {
            $join->on(  'transactions.gender_id','=','genders.id');
          })->with('details') ->whereRaw($search)->skip($start)->take($limit)->get();
        
        $responce = new \stdClass();
        return json_encode($transactions);
    
    }
    public function export(Request $request)
    {
        $data = static::data_report($request);
        return view('export',compact("data"));
    }
    public function report(Request $request)
    {
        $data = static::data_report($request);
        return view('report',compact("data"));
    }
}
