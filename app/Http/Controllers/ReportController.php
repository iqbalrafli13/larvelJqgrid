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
            $search .=" nofaktur like '%$global_search%' or";
            $search .=" tanggal like '%$global_search%' or";
            $search .=" nama like '%$global_search%' or";
            $search .=" genders.nama like '%$global_search%' or";
            $search .=" phone like '%$global_search%' or";
            $search .=" address like '%$global_search%' or";
            $search .=" saldo like '%$global_search%' ";
        }else{
            if(isset($request->nofaktur)){
                $nofaktur = $request->nofaktur;
                $search .=" nofaktur like '%$nofaktur%' and";
            }
            if(isset($request->tanggal)){
                $tanggal = date("Y-m-d", strtotime($request->tanggal));
                $search .=" tanggal = '$tanggal' and";
            }
            if(isset($request->nama)){
                $nama = $request->nama;
                $search .=" nama like '%$nama%' and";
            }
            if(isset($request->gender)){
                $gender = $request->gender;
                $search .=" gender_id = '$gender' and";
            }
            if(isset($request->phone)){
                $phone = $request->phone;
                $search .=" phone like '%$phone%' and";
            }
            if(isset($request->address)){
                $address = $request->address;
                $search .=" address like '%$address%' and";
            }
            if(isset($request->saldo)){
                $saldo = $request->saldo;
                $search .=" saldo like '%$saldo%' and";
            }
            $search .= " 1 ";
        }
        $sidx = $request->sidx;
        $sord = $request->sord;
        $start = $request->from;
        $limit = $request->to;

        
        // if ($sidx == 'gender') {
        //     $sidx = "gender.nama";
        // }else {
        //     $sidx = "transaksi.".$sidx;
        // }
    
        $query = Transaction:: orderBy($sidx, $sord);

        $transactions = $query->with('details')->with('gender')->whereRaw($search)->skip($start)->take($limit)->get();
        $responce = new \stdClass();
    
        // echo $SQL; return 0;
        $i = 0;
        $data = [];
        // foreach ($transactions as $transaksi) {
        //     $curency = number_format($transaksi->saldo,0,',','.');
        //     $transaksi->detail =[];
        //     foreach ($transaksi->details as $detail) {
        //         # code...
        //     }
        //     while ($detail = mysqli_fetch_assoc($sqldetail)){
        //         $transaksi->detail[$j]=$detail;
        //         $j++;
        //     }
    
        //     $data[$i] = $transaksi;
        //     $i++;
        // }
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
