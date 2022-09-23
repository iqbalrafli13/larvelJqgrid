<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Detailtransaction;
use App\Models\Transaction;

class DetailtransactionController extends Controller
{
    public function index(Request $request,$id)
    {
        $page = $request->page;
        $limit = $request->rows;
        $sidx = $request->sidx;
        $sord = $request->sord;
        if (! $sidx){
            $sidx = 'id';
        }
        $query = Detailtransaction::where('transaction_id',$id)->orderBy($sidx, $sord);
        $count= $query->count();
        if ($count > 0 && $limit > 0) {
            $total_pages = ceil($count / $limit);
        } else {
            $total_pages = 0;
        }
        if ($page > $total_pages)
            $page = $total_pages;
        $start = $limit * $page - $limit;
        if ($start < 0)
            $start = 0;
        $details = $query->skip($start)->take($limit)->get();
        $i = 0;
        $responce = new \stdClass();

        foreach ($details as $detail) {
            $total = $detail->harga * $detail->quantity;
            $responce->rows[$i]['id'] = $detail->id;
            $responce->rows[$i]['cell'] = array(
                $detail->barang,
                $detail->harga,
                $detail->quantity,
                $total

            );
            $i++;
        }

        $total_pages = ceil($count / $limit);
        $responce->page =$page;
        $responce->total=$total_pages;
        $responce->records=$count;
        // echo json_encode($responce);
        return json_encode($responce);
    }

    public function store($request ,$id)
    {
        $barang  = strtoupper($request->barang);
    
        $harga = $request->harga;
        $harga = str_replace("idr ","",$harga);
        $harga = str_replace(",","",$harga);
        $quantity = $request->quantity;
        $quantity = str_replace("idr ","",$quantity);
        $quantity = str_replace(",","",$quantity);
        $transaction = Transaction::find($id);
        if ($transaction) {
            $transaction->details()->create([
                "barang" => $barang,
                "harga" => $harga,
                "quantity" => $quantity,
            ]);
            return true;
        }
    }

    public function destroy($id)
    {
        $transaction = Transaction::find($id);
        if ($transaction) {
            $transaction->details()->delete();
            return true;
        }
    }
}
