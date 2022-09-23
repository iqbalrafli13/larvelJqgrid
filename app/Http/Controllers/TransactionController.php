<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Http\Controllers\DetailtransactionController;


class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
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
        // $query = Transaction::with('gender')->orderBy($sidx, $sord);
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
        $transactions = $query->whereRaw($search)->skip($start)->take($limit)->get();
        $i = 0;
        $responce = new \stdClass();

        foreach ($transactions as $transaksi) {
            $responce->rows[$i]['id'] = $transaksi->id;
            $responce->rows[$i]['cell'] = array(
                $transaksi->nofaktur,
                date('d-m-Y', strtotime($transaksi->tanggal)),
                $transaksi->nama,
                $transaksi->gender->nama,
                $transaksi->phone,
                $transaksi->address,
                $transaksi->saldo,

            );
            $i++;
        }

        $total_pages = ceil($count / $limit);
        $responce->page =$page;
        $responce->total=$total_pages;
        $responce->records=$count;
        return json_encode($responce);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('modal.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $nofaktur   = strtoupper($request->nofaktur);
        $tanggal  = $request->tanggal;
        $nama  = strtoupper($request->nama);
        $gender_id  = strtoupper($request->gender_id);
        $phone  = $request->phone;
        $saldo  = $request->saldo;
        $address    = strtoupper($request->address);
        // $saldo = substr($saldo,4);
        $saldo = str_replace(",","",$saldo);
        $tanggal = date("Y-m-d", strtotime($tanggal));
        $phone = str_replace("_","",$phone);
        if(substr($phone,-1) == "-"){
            $phone = substr_replace($phone ,"",-1);
        }
        $data=[
            "nofaktur" => $nofaktur,
            "tanggal" => $tanggal,
            "nama" => $nama,
            "gender_id" => $gender_id,
            "phone" => $phone,
            "saldo" => $saldo,
            "address" => $address,
        ];
        $transaction = Transaction::create($data);
        $dataDetail = new \stdClass();
        if ($transaction) {
            for ($i=0; $i < count($request->barang) ; $i++) { 
                $dataDetail->barang = $request->barang[$i];
                $dataDetail->quantity = $request->qty[$i];
                $dataDetail->harga = $request->harga[$i];
                $detail = DetailtransactionController::store($dataDetail,$transaction->id);
            }
            $res=['status' => 'submitted',"postData"=>$data];
            return json_encode($res,200);
        }
        $res=['status' => 'notsubmitted',"postData"=>$request];
        return json_encode($res,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $nofaktur = $id;
        $postData = $_GET['postData'];
        $sort_index = $_GET['sort_index'];
        $sort_order = $_GET['sort_order'];
        $limit = $_GET['limit'];

        $operator = ($sort_order == 'asc') ? '<=' : '>=';

		if ($sort_index == 'tanggal') $postData = date('Y-m-d', strtotime($postData));
		if ($sort_index == 'phone') {
            $postData = str_replace("_","",$postData);
            if(substr($postData,-1) == "-"){
                $postData = substr_replace($postData ,"",-1);
            }
            $postData = "+".trim($postData);
        }
            
		
        $transaksi = Transaction::where('nofaktur',$nofaktur)->first();
		$count = Transaction::count();
        $row = Transaction::where($sort_index, $operator ,$postData)->orderBy($sort_index, $sort_order)->count();
		$page = ceil($row / $limit);
		if ($page > 1) $row -= $limit * ($page - 1);
        

		echo json_encode([
			'postData' => $postData,
			'operator' => $operator,
			'page' => $page,
			'row' => $row,
			'records' => $count,
			'data' => $transaksi,
		]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $transaction = Transaction::find($id);
        return view('modal.edit',compact('transaction'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);
        $nofaktur   = strtoupper($request->nofaktur);
        $tanggal  = $request->tanggal;
        $nama  = strtoupper($request->nama);
        $gender_id  = strtoupper($request->gender_id);
        $phone  = $request->phone;
        $saldo  = $request->saldo;
        $address    = strtoupper($request->address);
        // $saldo = substr($saldo,4);
        $saldo = str_replace(",","",$saldo);
        $tanggal = date("Y-m-d", strtotime($tanggal));
        $phone = str_replace("_","",$phone);
        if(substr($phone,-1) == "-"){
            $phone = substr_replace($phone ,"",-1);
        }
        $data=[
            "nofaktur" => $nofaktur,
            "tanggal" => $tanggal,
            "nama" => $nama,
            "gender_id" => $gender_id,
            "phone" => $phone,
            "saldo" => $saldo,
            "address" => $address,
        ];
        $dataDetail = new \stdClass();
        if (isset($transaction)) {
            DetailtransactionController::destroy($transaction->id);
            for ($i=0; $i < count($request->barang) ; $i++) { 
                $dataDetail->barang = $request->barang[$i];
                $dataDetail->quantity = $request->qty[$i];
                $dataDetail->harga = $request->harga[$i];
                $detail = DetailtransactionController::store($dataDetail,$transaction->id);
            }
            $transaction->update($data);
            $res=['status' => 'submitted',"postData"=>$data];
            return json_encode($res,200);
        }
        $res=['status' => 'notsubmitted',"postData"=>$request];
        return json_encode($res,201);
    }
    public function delete($id)
    {
        $transaction = Transaction::find($id);
        return view('modal.delete',compact('transaction'));
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);
        if ($transaction) {
            $transaction->details()->delete();
            $transaction->delete();
            $res=['deleted'];
            return json_encode($res,200);
        }
    }
}
