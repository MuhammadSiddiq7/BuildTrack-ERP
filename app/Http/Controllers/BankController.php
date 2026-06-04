<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BankController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('bank_view');
        $banks = Bank::latest()->get();
        $trashCount = Bank::onlyTrashed()->count();

        return view('bank.index', compact('banks', 'trashCount'));
    }

    public function create()
    {
        $this->authorize('bank_create');
        return view('bank.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'nullable|unique:banks,name',
            'branch' => 'nullable|string|max:255',
            'account_number' => 'required|string|max:255|unique:banks,account_number',
            'iban' => 'required|string|max:255|unique:banks,iban',
            // 'edenred_exchange' => 'nullable|string|max:255',
            'status' => 'nullable|in:active,inactive',
        ]);

        DB::beginTransaction();
        try {
            $bank = Bank::create($request->all());

            logUserActivity('Bank', 'Created Bank ' . ($bank->name ?? 'Unnamed'), $bank->id, 'Bank');

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
            return redirect()->route('banks.index')->with('success', 'Bank added successfully.');
        } catch (Exception $ex) {
            DB::rollBack();
            Log::error('Bank Store Error: ' . $ex->getMessage());
            return redirect()->back()->withInput()->with('error', 'Error adding bank: ' . $ex->getMessage());
        }
    }


    public function edit($id)
    {
        $this->authorize('bank_edit');
        $bank = Bank::findOrFail($id);
        return view('bank.edit', compact('bank'));
    }

    public function update(Request $request, $id)
    {
        $bank = Bank::findOrFail($id);

        $request->validate([
            'name' => 'nullable|unique:banks,name,' . $bank->id,
            'branch' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
            'iban' => 'nullable|string|max:255',
            // 'edenred_exchange' => 'nullable|string|max:255',
            'status' => 'nullable|in:active,inactive',
        ]);

        DB::beginTransaction();
        try {
            $bank->update($request->all());

            logUserActivity('Bank', 'Updated Bank ' . ($bank->name ?? 'Unnamed'), $bank->id, 'Bank');

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
            return redirect()->route('banks.index')->with('success', 'Bank updated successfully.');
        } catch (Exception $ex) {
            DB::rollBack();
            Log::error('Bank Update Error: ' . $ex->getMessage());
            return redirect()->back()->withInput()->with('error', 'Error updating bank: ' . $ex->getMessage());
        }
    }


    public function destroy($id)
    {
        $this->authorize('bank_trash');

        $bank = Bank::findOrFail($id);
        $bankName = $bank->name ?? 'Unnamed';

        DB::beginTransaction();
        try {
            $bank->delete();

            logUserActivity('Bank', 'Deleted Bank ' . $bankName, $bank->id, 'Bank');

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
            return redirect()->route('banks.index')->with('success', 'Bank deleted successfully.');
        } catch (Exception $ex) {
            DB::rollBack();
            Log::error('Bank Delete Error: ' . $ex->getMessage());
            return redirect()->back()->with('error', 'Error deleting bank: ' . $ex->getMessage());
        }
    }


    public function trash()
    {
        $this->authorize('bank_trash_view');
        $banks = Bank::onlyTrashed()->latest()->get();
        return view('bank.trash', compact('banks'));
    }

    public function restore($id)
    {
        $bank = Bank::onlyTrashed()->findOrFail($id);
        $bank->restore();

        DB::beginTransaction();
        try {
            logUserActivity('Bank', 'Restored Bank ' . ($bank->name ?? 'Unnamed'), $bank->id, 'Bank');

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
            return redirect()->route('banks.index')->with('success', 'Bank restored successfully.');
        } catch (Exception $ex) {
            DB::rollBack();
            Log::error('Bank Restore Error: ' . $ex->getMessage());
            return redirect()->back()->with('error', 'Error restoring bank: ' . $ex->getMessage());
        }
    }
}
