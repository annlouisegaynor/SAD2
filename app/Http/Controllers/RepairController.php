<?php

namespace App\Http\Controllers;

use App\Repair;
use Carbon\Carbon;
use App\Inventory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreSupplier;
use App\Http\Controllers\Controller;  
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class RepairController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(Request $request)
    {  
        $input = Input::all();

            //dd( $input['supply_name'][0]);
            // dd($request);

        for ($i=0; $i < count($input['dm_item_name']); ++$i) {
            
            $inrepair = Repair::where([
                            ['repair_inventory_id', '=', $input['dm_item_name'][$i]],
                            ['repair_status', '=', $input['dm_item_state'][$i]],
                            ['repair_user_id', '=', $request -> inventory_user_id],
                            ['repair_date', '=', $request -> received_at]
                        ]) 
                        -> get();

            if (!empty($inrepairs)){
                    $inrepair -> repair_qty += $input['dm_qty'][$i];
            }
            else {
                $repair = new Repair;
                $repair -> repair_inventory_id = $input['dm_item_name'][$i];
                $repair -> repair_user_id = $request -> inventory_user_id;
                $repair -> repair_date = $request -> received_at;
                $repair -> repair_qty = $input['dm_qty'][$i];
                $repair -> repair_status = $input['dm_item_state'][$i];
                $repair -> save(); 
            }

            
        } 
        //session()->flash('message', 'Successfully created a new supplier!');
        return redirect('/inventory');
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
        $repair = Repair::find($id);

        if ($request -> update_type == "fixed_qty"){
            //ADD FIXED QTY
            $repair -> repair_qty -= $request -> qty_fixed;
            $repair -> save();

            $inventory_item = Inventory::find($repair->repair_inventory_id);
            $inventory_item -> inventory_qty += $request -> qty_fixed;
            $inventory_item -> save();
        }
        else{
            //EDIT DAMAGED ITEM
            $repair -> repair_user_id = $request -> edit_handler;
            $repair -> repair_date = $request -> edit_received_at;
            $repair -> save();
        }
        return redirect('/inventory');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getRepair($id){
        $repairdata = DB::table('repairs')
                    -> join('inventories', 'inventories.inventory_id', '=', 'repairs.repair_inventory_id')
                    -> join('suppliers', 'suppliers.supplier_id','=','inventories.inventory_supplier_id')
                    -> join('profiles', 'profiles.profile_user_id', '=', 'repairs.repair_user_id')
                    -> select('inventories.inventory_name', 'repairs.*', 'profiles.fname', 'profiles.mname', 'profiles.lname', 'suppliers.supplier_name', 'suppliers.supplier_id')
                    -> where('repairs.repair_id', '=', $id)
                    -> get();
       
        return $repairdata;
    }
}