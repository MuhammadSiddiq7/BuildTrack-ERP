<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeBank;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EmployeeBankController extends Controller
{
     use AuthorizesRequests;

    public function index()
    {
        $this->authorize('bank_view');
        $banks = EmployeeBank::latest()->get();
        $trashCount = EmployeeBank::onlyTrashed()->count();

        return view('employeeBank.index', compact('banks', 'trashCount'));
    }

    public function create()
    {
        $this->authorize('bank_create');
        $employees = Employee::all();
        return view('employeeBank.create',compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'bank_name' => 'required|unique:employee_banks,bank_name',
            'account_title' => 'nullable|unique:employee_banks,account_title',
            // 'branch' => 'nullable|string|max:255',
            'account_number' => 'required|string|max:255|unique:employee_banks,account_number',
            'iban' => 'required|string|max:255|unique:banks,iban',
            // 'edenred_exchange' => 'nullable|string|max:255',
            // 'status' => 'nullable|in:active,inactive',
        ]);

        DB::beginTransaction();
        try {
            $bank = EmployeeBank::create($request->all());

            logUserActivity('EmployeeBank', 'Created Bank ' . ($bank->name ?? 'Unnamed'), $bank->id, 'Bank');

            // $bankNotification = Notification::create([
            //     'type' => 'Bank',
            //     'subject' => 'New Bank Created',
            //     'message' => 'A new bank "' . ($bank->name ?? 'Unnamed') . '" has been added.',
            //     'is_global' => false,
            //     'url' => route('banks.index')
            // ]);

            // $users = User::permission('bank_view')->get();
            // foreach ($users as $user) {
            //     $user->notifications()->attach($bankNotification->id, ['is_read' => false]);
            // }

            DB::commit();
            return redirect()->route('employee.bank.index')->with('success', 'Bank added successfully.');
        } catch (Exception $ex) {
            DB::rollBack();
            Log::error('Bank Store Error: ' . $ex->getMessage());
            return redirect()->back()->withInput()->with('error', 'Error adding bank: ' . $ex->getMessage());
        }
    }


    public function edit($id)
    {
        $this->authorize('bank_edit');
        $bank = EmployeeBank::findOrFail($id);
        return view('employeeBank.edit', compact('bank'));
    }

    public function update(Request $request, $id)
    {
        $bank = EmployeeBank::findOrFail($id);

        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'name' => 'nullable|unique:banks,name,' . $bank->id,
            'account_title' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
            'iban' => 'nullable|string|max:255',
            // 'edenred_exchange' => 'nullable|string|max:255',
            // 'status' => 'nullable|in:active,inactive',
        ]);

        DB::beginTransaction();
        try {
            $bank->update($request->all());

            logUserActivity('Employee Bank', 'Updated Bank ' . ($bank->name ?? 'Unnamed'), $bank->id, 'Bank');

            // $bankNotification = Notification::create([
            //     'type' => 'Bank',
            //     'subject' => 'Bank Updated',
            //     'message' => 'The bank "' . ($bank->name ?? 'Unnamed') . '" has been updated.',
            //     'is_global' => false,
            //     'url' => route('banks.index')
            // ]);

            // $users = User::permission('bank_edit')->get();
            // foreach ($users as $user) {
            //     $user->notifications()->attach($bankNotification->id, ['is_read' => false]);
            // }

            DB::commit();
            return redirect()->route('employee.bank.index')->with('success', 'Bank updated successfully.');
        } catch (Exception $ex) {
            DB::rollBack();
            Log::error('Bank Update Error: ' . $ex->getMessage());
            return redirect()->back()->withInput()->with('error', 'Error updating bank: ' . $ex->getMessage());
        }
    }


    public function destroy($id)
    {
        $this->authorize('bank_trash');

        $bank = EmployeeBank::findOrFail($id);
        $bankName = $bank->name ?? 'Unnamed';

        DB::beginTransaction();
        try {
            $bank->delete();

            logUserActivity('EmployeeBank', 'Deleted Bank ' . $bankName, $bank->id, 'EmployeeBank');

            // $bankNotification = Notification::create([
            //     'type' => 'Bank',
            //     'subject' => 'Bank Deleted',
            //     'message' => 'The bank "' . $bankName . '" has been deleted.',
            //     'is_global' => false,
            //     'url' => route('banks.index')
            // ]);

            // $users = User::permission('bank_trash')->get();
            // foreach ($users as $user) {
            //     $user->notifications()->attach($bankNotification->id, ['is_read' => false]);
            // }

            DB::commit();
            return redirect()->route('employee.bank.index')->with('success', 'Bank deleted successfully.');
        } catch (Exception $ex) {
            DB::rollBack();
            Log::error('Bank Delete Error: ' . $ex->getMessage());
            return redirect()->back()->with('error', 'Error deleting bank: ' . $ex->getMessage());
        }
    }


    public function trash()
    {
        $this->authorize('bank_trash_view');
        $banks = EmployeeBank::onlyTrashed()->latest()->get();
        return view('employeeBank.trash', compact('banks'));
    }

    public function restore($id)
    {
        $bank = EmployeeBank::onlyTrashed()->findOrFail($id);
        $bank->restore();

        DB::beginTransaction();
        try {
            logUserActivity('EmployeeBank', 'Restored Bank ' . ($bank->name ?? 'Unnamed'), $bank->id, 'Bank');

            // $bankNotification = Notification::create([
            //     'type' => 'Bank',
            //     'subject' => 'Bank Restored',
            //     'message' => 'The bank "' . ($bank->name ?? 'Unnamed') . '" has been restored.',
            //     'is_global' => false,
            //     'url' => route('banks.index')
            // ]);

            // $users = User::permission('bank_restore')->get();
            // foreach ($users as $user) {
            //     $user->notifications()->attach($bankNotification->id, ['is_read' => false]);
            // }

            DB::commit();
            return redirect()->route('employee.bank.index')->with('success', 'Bank restored successfully.');
        } catch (Exception $ex) {
            DB::rollBack();
            Log::error('Bank Restore Error: ' . $ex->getMessage());
            return redirect()->back()->with('error', 'Error restoring bank: ' . $ex->getMessage());
        }
    }
}
