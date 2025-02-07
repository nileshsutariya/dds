<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Client;
use App\Models\Setting;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DailyController extends Controller
{
    public function daily_index()
    {
        $activeClients = Client::all();
        $transaction = Transaction::all();
        return view('admin.daily_entry.daily_entry', compact('activeClients', 'transaction'));
    }

    public function fetchTransactions(Request $request)
    {
        $date = $request->input('date');

        $transactions = Transaction::whereDate('date', Carbon::parse($date)->toDateString())
            ->get();

        $data = [];
        foreach ($transactions as $transaction) {
            $client = Client::find($transaction->client_id);
            if ($client) {
                $data[] = [
                    'no' => count($data) + 1,
                    'id' => $transaction->id,
                    'daily_units' => $transaction->unit,
                    'full_name' => $client->name,
                    'phone_no' => $client->phone_no,
                    'address' => $client->address,
                    'area' => $client->area,
                    'price' => $transaction->price,
                ];
            }
        }

        return response()->json($data);
    }

    public function fetchActiveClients()
    {
        $activeClients = Client::where('status', 1)->get();

        $data = [];
        foreach ($activeClients as $client) {
            $data[] = [
                'no' => count($data) + 1,
                'daily_units' => $client->daily_unit,
                'full_name' => $client->name,
                'phone_no' => $client->phone_no,
                'address' => $client->address,
                'area' => $client->area,
                'id' => $client->id
            ];
        }

        return response()->json($data);
    }

    public function saveDailyUnit(Request $request)
    {
        $price = Setting::where('key', 'price')->value('value');
        // print_r($price);die;
        $transactions = $request->input('transactions');

        foreach ($transactions as $transaction) {

            $transactionRecord = Transaction::where('client_id', $transaction['client_id'])
                ->where('date', $transaction['date'])
                ->first();
            if ($transactionRecord) {
                $transactionRecord->unit = $transaction['unit'];
                $transactionRecord->price = $transaction['unit'] * $price;
                $transactionRecord->save();
            } else {
                $transactionRecord = new Transaction();
                $transactionRecord->client_id = $transaction['client_id'];
                $transactionRecord->unit = $transaction['unit'];
                $transactionRecord->date = $transaction['date'];
                $transactionRecord->price = $transaction['unit'] * $price;
                $transactionRecord->save();
            }
        }

        return response()->json($transactions);
    }

    public function updatedDailyunit(Request $request)
    {
        $transactions = $request->input('transactions');
        $price = Setting::where('key', 'price')->value('value');

        foreach ($transactions as $transaction) {
            $transactionRecord = Transaction::find($transaction['id']);

            if ($transactionRecord) {
                $transactionRecord->unit = $transaction['unit'];
                $transactionRecord->price = $transaction['unit'] * $price;
                $transactionRecord->save();
            } else {
                return response()->json(['success' => false, 'message' => 'Transaction not found for ID ' . $transaction['id']]);
            }
        }

        return response()->json(['success' => true]);
    }


    public function deleteDailyunit(Request $request)
    {
        $transactionIds = $request->input('transaction_ids');

        if ($transactionIds) {
            $deleted = Transaction::whereIn('id',  $transactionIds)->delete();

            if ($deleted) {
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false, 'message' => 'Failed to delete transactions.']);
            }
        }

        return response()->json(['success' => false, 'message' => 'No transactions selected.']);
    }

    public function getTodayTotalSellingUnit()
    {
        $today = now()->format('Y-m-d');

        $totalSellingUnit = DB::table('transactions')
            ->whereDate('date', $today)
            ->sum('unit');

        return response()->json(['totalSellingUnit' => $totalSellingUnit]);
    }

    public function thisMonthTotalSellingUnit()
    {
        $totalSellingUnit = Transaction::whereYear('date', Carbon::now()->year)
            ->whereMonth('date', Carbon::now()->month)
            ->sum('unit');

        return response()->json([
            'totalSellingUnit' => $totalSellingUnit
        ]);
    }

    public function totalclients()
    {
        $clients = Client::count();

        return response()->json([
            'totalUnit' =>  $clients
        ]);
    }

    public function getLast15DaysMilkSales(Request $request)
    {
        $today = Carbon::today();

        $milkSalesData = Transaction::select(DB::raw('DATE(date) as date'), DB::raw('SUM(unit) as unit'))
            ->where('date', '>', $today->subDays(15))
            ->groupBy(DB::raw('DATE(date)'))
            ->orderBy('date', 'asc')
            ->get();

        return response()->json($milkSalesData);
    }

    public function getLastYearMilkSales(Request $request)
    {
        $startOfLastYear = Carbon::now()->subYear()->startOfYear();
        $endOfLastYear = Carbon::now()->subYear()->endOfYear();

        $milkSalesData = Transaction::select(
            DB::raw('MONTH(date) as month'),
            DB::raw('SUM(unit) as total_milk')
        )
            ->whereBetween('date', [$startOfLastYear, $endOfLastYear])
            ->groupBy(DB::raw('MONTH(date)'))
            ->orderBy(DB::raw('MONTH(date)'))
            ->get();

        return response()->json($milkSalesData);
    }

    public function getTotalUnits(Request $request)
    {
        $selectedMonth = $request->input('month', Carbon::now()->format('Y-m'));

        $startOfMonth = Carbon::parse($selectedMonth)->startOfMonth();
        $endOfMonth = Carbon::parse($selectedMonth)->endOfMonth();

        $totalUnits = Transaction::whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('unit');

            

        return response()->json(['totalUnit' => $totalUnits]);
    }
}
