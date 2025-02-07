<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Area;
use App\Models\Client;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ClientController extends Controller
{
    public function create()
    {
        $areas = Area::all();
        return view('user.client.client', compact('areas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone_no' => 'required',
            'password' => 'required',
            'address' => 'required',
            'area' => 'required'
        ]);

        $client = new Client();

        $client->name = $request->name;
        $client->phone_no = $request->phone_no;
        $client->password = Hash::make($request->password);
        $client->address = $request->address;
        if (is_array($request->area)) {
            $client->area = implode(',', $request->area);
        }

        $client->save();

        return redirect()->route('client.index');
    }

    public function index(Request $request)
    {
        $clients = DB::table('clients')->paginate(3);

        foreach ($clients as $client) {
            $client->areas = DB::table('areas')
                ->whereIn('id', explode(',', $client->area))
                ->pluck('area_name');
        }

        if ($request->ajax()) {
            return response()->json([
                'html' => view('user.client.client_table', compact('clients'))->render(),
            ]);
        }
        return view('user.client.client_table', compact('clients'));
    }

    public function edit(string $id)
    {
        $client = Client::findorfail($id);
        $areas = Area::all();
        return view('user.client', compact('client', 'areas'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required',
            'phone_no' => 'required',
            'password' => 'required',
            'address' => 'required',
            'area' => 'required'
        ]);

        $client = Client::findorfail($id);

        $client->name = $request->name;
        $client->phone_no = $request->phone_no;
        $client->password = Hash::make($request->password);
        $client->address = $request->address;
        if (is_array($request->area)) {
            $client->area = implode(',', $request->area);
        }

        $client->save();

        return redirect()->route('client.index');
    }

    public function dash()
    {
        return view('dashboard_client');
    }

    //on client master update daily unit
    public function updateDailyUnit(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'daily_unit' => 'required|numeric',
        ]);

        $client = Client::find($request->client_id);
        $client->daily_unit = $request->daily_unit;
        $client->save();

        // $transaction = Transaction::where('client_id', $client->id)->first();

        // if ($transaction) {
        //     $transaction->unit = $client->daily_unit;
        //     $transaction->date = Carbon::now();
        //     $transaction->save();
        // }

        return response()->json(['success' => true]);
    }

    //in daily entry 
    public function getclientDate(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
        ]);

        $clients = Client::whereHas('transactions', function ($query) use ($request) {
            $query->whereDate('transaction_date', $request->date)
                ->where('status', 1);
        })
            ->with(['transactions' => function ($query) use ($request) {
                $query->whereDate('transaction_date', $request->date);
            }])
            ->get();

        return response()->json($clients);
    }
}
