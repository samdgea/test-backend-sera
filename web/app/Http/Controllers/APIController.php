<?php
namespace App\Http\Controllers;


use App\Models\Customer;
use Illuminate\Http\Request;

class APIController
{
    public function getAllCustomers(Request $request) {
        $customers = Customer::all();

        return response()->json([
            'success' => true,
            'data' => $customers
        ]);
    }

    public function getCustomer($id, Request $request) {
        $customer = Customer::find($id);

        if (!empty($customer)) {
            return response()->json([
                'success' => true,
                'data' => $customer
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Unable to find customer with that ID'
        ]);
    }

    public function storeNewCustomer(Request $request) {
        $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'nullable|string|max:50',
            'email_address' => 'required|email',
            'phone_number' => 'required|string|min:9|max:20'
        ]);

        $cust =Customer::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email_address' => $request->email_address,
            'phone_number' => $request->phone_number,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'New Customer added',
            'data' => $cust
        ]);
    }

    public function deleteCustomer($id, Request $request) {
        $ret = Customer::destroy($id);

        if ($ret <> 0) {
            return response()->json([
                'success' => true,
                'message' => 'Success delete customer with ID ' . $id
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to delete customer with ID' . $id . '. Looks like that ID does not exists anymore'
        ]);
    }
}
