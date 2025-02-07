<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Client;
use App\Models\Setting;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CalendarController extends Controller
{
    //show full calendar 
    public function calendar()
    {
        $clients = Client::all();
        return view('admin.calendar.calendar', compact('clients'));
    }

    //in calendar for particular date transaction unit and amount show
    public function gettransactionUnit(Request $request, $clientId)
    {
        $start = $request->input('start');
        $end = $request->input('end');

        $startDate = Carbon::parse($start)->startOfMonth();
        $endDate = Carbon::parse($end)->endOfMonth();

        $transactionUnit = Transaction::where('client_id', $clientId)
            ->whereBetween('date', [$startDate, $endDate])
            ->get(['date', 'unit', 'price']);

        return response()->json($transactionUnit);
    }

    //show monthly report
    public function monthlyReport()
    {
        $clients = Client::all();
        return view('admin.monthaly.monthly', compact('clients'));
    }

    //on calendar update transaction unit using model
    public function transactionUpdate(Request $request)
    {
        $price = Setting::where('key', 'price')->value('value');

        $transaction = Transaction::where('client_id', $request->client_id)
            ->where('date', $request->date)
            ->first();

        if ($transaction) {
            $amount = $request->unit * $price;

            $transaction->update([
                'unit' => $request->unit,
                'price' => $amount,
            ]);

            return response()->json([
                'message' => 'Transaction updated successfully!',
                'transaction' => $transaction,
            ], 200);
        }

        return response()->json(['message' => 'Transaction not found.'], 404);
    }

    //on calendar save transaction unit using model
    public function transactionStore(Request $request)
    {
        $price = Setting::where('key', 'price')->value('value');

        $amount = $request->unit * $price;
        Transaction::create([
            'client_id' => $request->client_id,
            'date' => $request->date,
            'unit' => $request->unit,
            'price' => $amount
        ]);

        return response()->json(['message' => 'Transaction created successfully!'], 201);
    }

    //monthly report fetch all client_name and amount and unit and total
    public function fetchClients(Request $request)
    {
        $selectedMonth = $request->get('month');

        if (!$selectedMonth) {
            return response()->json(['error' => 'Month parameter is required'], 400);
        }

        $year = date('Y', strtotime($selectedMonth));
        $month = date('m', strtotime($selectedMonth));

        $clients = Client::all();
        $transactions = Transaction::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get(['date', 'price', 'unit', 'client_id']);

        $clientData = [];

        foreach ($clients as $client) {
            $clientTransactions = $transactions->where('client_id', $client->id);
            $transactionData = [];
            $amountData = [];

            $date = new \DateTime($year . '-' . $month . '-01');
            $daysInMonth = (int) $date->format('t');

            $totalUnits = 0;
            $totalAmount = 0;

            for ($i = 1; $i <= $daysInMonth; $i++) {
                $currentDate = $date->format('Y-m-d');
                $transaction = $clientTransactions->firstWhere('date', $currentDate);

                $unitsForDay = $transaction ? $transaction->unit : 0;
                $transactionData[$currentDate] = $unitsForDay;

                $amountForDay = $transaction ? $transaction->price : 0;
                $amountData[$currentDate] = $amountForDay;

                $totalUnits += $unitsForDay;
                $totalAmount += $amountForDay;

                $date->modify('+1 day');
            }

            $clientData[] = [
                'name' => $client->name,
                'transactions' => $transactionData,
                'amounts' => $amountData,
                'total_units' => $totalUnits,
                'total_amount' => $totalAmount,
            ];
        }

        return response()->json($clientData);
    }
}
