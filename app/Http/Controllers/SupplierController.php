<?php

namespace App\Http\Controllers;

use App\User;
use Validator;
use App\Supplier;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreSupplier;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;  
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        $curr_usr = Auth::user();

        if($request->has('titlesearch')){
            $suppliers = Supplier::search($request->input('titlesearch')) 
                -> paginate(5);
        }else{
            $suppliers = Supplier::where('supplier_status' , '=', 1)
                -> orWhere('supplier_status' , '=', 0)
                -> orderBy('updated_at', 'desc')
                -> paginate(5);
        } 
        return view('suppliers', compact('suppliers', 'curr_usr'));
    }

    public function getSupplier($id)
    {
        $supplierdata = Supplier::find($id);
        //Session::flash('message', 'User has been successfully created!');
        return $supplierdata;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSupplier $request)
    {
        Supplier::create($request->all());
        //session()->flash('message', 'Successfully created a new supplier!');
        return redirect('/suppliers') -> with('store-success','Supplier was successfully created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $validator = Validator::make($request->all(), [
            'edit_supplier_name' => array(
                         'required',
                         'max:50',
                         'string',
                         'unique:suppliers,supplier_name,' .$id .',supplier_id'),
            'edit_supplier_addr' => array(
                         'required',
                         'max:100',
                         'string'),
            'edit_supplier_email' => 'required|email',
            'edit_supplier_cnum' => 'required|digits:11'
        ]);

        $attributeNames = array(
                    'edit_supplier_name' => "supplier's name",
                    'edit_supplier_addr' => 'address',
                    'edit_supplier_email' => 'email',  
                    'edit_supplier_cnum' => 'contact number',
                    'edit_supplier_cp' => 'contact person',
                    'edit_supplier_owner' => 'contact owner'
                );
        $validator->setAttributeNames($attributeNames);

        if ($validator->fails()) {
            return redirect('/supplies/'.$id)
                ->withErrors($validator, 'editSupplier')
                ->withInput($request->all())
                ->with('error_id', $id);
        }
        else{
            $supplier = Supplier::find($id);
            //dd($request-> all()); //for debugging purposes
            $supplier -> supplier_name = $request-> edit_supplier_name;
            $supplier -> supplier_addr = $request-> edit_supplier_addr;
            $supplier -> supplier_email = $request-> edit_supplier_email;
            $supplier -> supplier_cnum = $request-> edit_supplier_cnum;
            $supplier -> supplier_cp = $request-> edit_supplier_cp;
            $supplier -> supplier_owner = $request-> edit_supplier_owner;
            $supplier -> save();

            return redirect('/supplies/' .$id) -> with('update-profile-success','Supplier was successfully edited!');
        }  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $supplier = Supplier::find($id);

        //reset to active
        if ($supplier-> supplier_status == 0){
            $supplier -> supplier_status = 1;
            $supplier -> save();

            $supplies = Supplier::find($id)->inventories;

            foreach($supplies as $supply){
                $supply -> inventory_status = 1;
                $supply -> save();
            }
            return redirect() -> back() -> with('destroy-success','Supplier was successfully reset to active!');
        }
        //set to inactive
        else{
            $supplier -> supplier_status = 0;
            $supplier -> save();
            $supplies = Supplier::find($id)->inventories;

            foreach($supplies as $supply){
                $supply -> inventory_status = 0;
                $supply -> save();
            }

            return redirect() -> back() -> with('destroy-success','Supplier was successfully set to inactive!');
        }
    }
}
