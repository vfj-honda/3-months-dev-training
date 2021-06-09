<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeRequest;
use App\Models\Orders;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EmployeeController extends Controller
{

    /**
     * Show the form for creating a new employee.
     * Method: GET
     * @return \Illuminate\Http\Response
     */
    public function create(){
        return view('employee/create');
    }

    /**
     * Show the employee list.
     * Method: GET
     * @return \Illuminate\Http\Response
     */
    public function show(){
        $employee = User::all();
        return view('employee/list', $data = ['employee' => $employee]);
    }


    /**
     * Show the edit field.
     * Method: GET
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $employee = User::find($id);
        return view('employee/edit', $data = ['employee' => $employee]);
    }
    

    /**
    * Destroy Employee(User).
    * Method: DELETE
    * @return \Illuminate\Http\Response
    */
    public function destroy(Request $request, $id){
        try {
            return DB::transaction(function() use ($id) {
 
                # 1) set data
                $employee = User::find($id);

                # 2) delete
                $employee->order->delete();
                $employee->delete();

            }); 
 
 
        } catch(\Exception $e) {
 
        return back()->withErrors($e->getMessage());
 
        }
 
    }
 
    /**
    * Store new Employee(User).
    * Method: POST
    * @return \Illuminate\Http\Response
    */
    public function store(StoreEmployeeRequest $request){
        try {
             return DB::transaction(function() use ($request) {

                # 1) set data
                $employee = new User();
                $employee->name = $request->name;
                $employee->email = $request->email;
                $employee->chatwork_id = $request->chatwork_id;

                # 2) save user
                if (!$employee->save()){
					throw new \Exception('Save Employee failed');
				}

                # 3) create order 
                $order = new Orders();
                $order->user_id = $employee->id;
                $order->order_number = Orders::max('order_number') + 1;

                # 4) save order
                if (!$order->save()){
					throw new \Exception('Save Employee failed');
				}

                return redirect()->route('admin.employee.create');

            }); 


        } catch(\Exception $e) {

        return back()->withErrors($e->getMessage());

        }

    }

    /**
    * update new Employee(User).
    * Method: PUT
    * @return \Illuminate\Http\Response
    */
    public function update(StoreEmployeeRequest $request, $id){
       try {
            return DB::transaction(function() use ($request, $id) {
 
                 # 1) set data
                $employee = User::find($request->id);
                $employee->name = $request->name;
                $employee->email = $request->email;
                $employee->chatwork_id = $request->chatwork_id;
 
                 # 2) update data
                if (!$employee->save()){
                    throw new \Exception('Save Employee failed');
                }
 
                return redirect()->route('admin.employee.edit', $id);
 
            }); 
 
 
        } catch(\Exception $e) {
 
            return back()->withErrors($e->getMessage());
 
        }
 
    }


    public function authority()
    {
        $not_auth_employee = User::where('authority', '=', 0)
                                 ->orderBy('id', 'asc')
                                 ->get();
        $auth_employee = User::where('authority', '=', 1)
                             ->orderBy('id', 'asc')
                             ->get();
        return view('employee/authority', $data = ['not_auth_employee' => $not_auth_employee, 'auth_employee' => $auth_employee]);
    }

    /**
     * elevate an users authority
     * Method: PUT
     * 
     */
    public function elevate(Request $request)
    {
        try {
            return DB::transaction(function() use ($request) {
                
                $employee = User::find($request->elevate_user_id);
        
                $employee->authority = 1;
                
                if(!$employee->save()){
                   throw new \Exception('Elevate authority failed');
                }
    
                return redirect()->route('admin.employee.authority');

            });

        } catch (\Exception $e) {

            return back()->withErrors($e->getMessage());
            
        }

    }

    public function diselevate(Request $request)
    {
        try {
            return DB::transaction(function() use ($request) {
                
                $employee = User::find($request->diselevate_user_id);
        
                $employee->authority = 0;
                
                if(!$employee->save()){
                   throw new \Exception('Diselevate authority failed');
                }
    
                return redirect()->route('admin.employee.authority');

            });

        } catch (\Exception $e) {

            return back()->withErrors($e->getMessage());
            
        }
    }

}
