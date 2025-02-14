<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Unit;
use App\Models\User;
use App\Models\Login;
use App\Models\Client;
use App\Models\Payment;
use App\Models\Setting;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function create()
    {
        $areas = Area::all();
        return view('admin.user.admin_user', compact('areas'));
    }

    public function create_client()
    {
        $areas = Area::all();
        return view('admin.client.admin_client', compact('areas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'user_name' =>
            'required|string|min:8|unique:users,user_name|regex:/^[a-zA-Z0-9_]{4,}$/',
            'phone_no' => 'required',
            'password' => 'required',
            'address' => 'required',
            'area' => 'required',
        ]);

        $admin = new User();

        $admin->name = $request->name;
        $admin->phone_no = $request->phone_no;
        $admin->user_name = $request->user_name;
        $admin->address = $request->address;
        if (is_array($request->area)) {
            $admin->area = implode(',', $request->area);
        }
        $admin->password = Hash::make($request->password);
        $admin->status = $request['status'] ? 1 : 0;

        $admin->save();

        return redirect()->route('admin.index');
    }

    public function store_client(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone_no' => 'required',
            'address' => 'required',
            'area' => 'required',
            'password' => 'required',
            'unit' => 'required'
        ]);

        $admin = new Client();

        $admin->name = $request->name;
        $admin->phone_no = $request->phone_no;
        $admin->address = $request->address;
        $admin->password = Hash::make($request->password);
        $admin->status = $request['status'] ? 1 : 0;
        $admin->area = $request->area;
        $admin->daily_unit = $request->unit;

        $admin->save();

        // $transaction = new Transaction();

        // $transaction->client_id = $admin->id;
        // $transaction->date = Carbon::now();
        // $transaction->unit = $admin->daily_unit;

        // $transaction->save();

        return redirect()->route('admin_client.index');
    }


    public function index(Request $request)
    {
        $admins = User::paginate(100);

        foreach ($admins as $adm) {
            $adm->areas = DB::table('areas')
                ->whereIn('id', explode(',', $adm->area))
                ->pluck('area_name');
        }

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.user.admin_usertable', compact('admins'))->render(),
                'pagination' => (string) $admins->links()
            ]);
        }
        return view('admin.user.admin_usertable', compact('admins'));
    }

    public function index_client(Request $request)
    {
        $admin_client = Client::paginate(100);

        foreach ($admin_client as $adm) {
            $adm->areas = DB::table('areas')
                ->whereIn('id', explode(',', $adm->area))
                ->pluck('area_name');
        }

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.client.admin_client_table', compact('admin_client'))->render(),
                'pagination' => (string) $admin_client->links()
            ]);
        }

        return view('admin.client.admin_client_table', compact('admin_client'));
    }

    public function edit(string $id)
    {
        $admin = User::findOrFail($id);
        $areas = Area::all();
        return view('admin.user.admin_user', compact('admin', 'areas'));
    }

    public function edit_client(string $id)
    {
        $admin = Client::findorfail($id);
        $areas = Area::all();
        return view('admin.client.admin_client', compact('admin', 'areas'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required',
            'user_name' => 'required|string|min:8|regex:/^[a-zA-Z0-9_]{4,}$/|unique:users,user_name,' . $id,
            'phone_no' => 'required',
            'password' => 'nullable',
            'address' => 'required',
            'area' => 'required',
        ]);

        $admin = User::findOrFail($id);

        $admin->name = $request->name;
        $admin->phone_no = $request->phone_no;
        $admin->user_name = $request->user_name;
        $admin->address = $request->address;
        if (is_array($request->area)) {
            $admin->area = implode(',', $request->area);
        }
        $admin->password = Hash::make($request->password);
        $admin->status = $request['status'] ? 1 : 0;

        $admin->save();

        return redirect()->route('admin.index');
    }

    public function update_client(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required',
            'phone_no' => 'required',
            'address' => 'required',
            'area' => 'required',
            // 'password' => 'required'
        ]);

        $admin = Client::findorfail($id);

        $admin->name = $request->name;
        $admin->phone_no = $request->phone_no;
        $admin->address = $request->address;
        $admin->password = Hash::make($request->password);
        $admin->status = $request['status'] ? 1 : 0;
        $admin->area = $request->area;
        $admin->daily_unit = $request->unit;

        $admin->save();

        // $transaction = Transaction::where('client_id', $admin->id)->first();

        // if ($transaction) {
        //     $transaction->unit = $admin->daily_unit; 
        //     $transaction->date = Carbon::now(); 
        //     $transaction->save();
        // } else {
        //     $transaction = new Transaction();
        //     $transaction->client_id = $admin->id;
        //     $transaction->date = Carbon::now(); 
        //     $transaction->unit = $admin->daily_unit; 
        //     $transaction->save();
        // }

        return redirect()->route('admin_client.index');
    }

    public function dash()
    {
        return view('admin.dashboard_admin');
    }

    public function profile()
    {
        $profile = Auth::guard('admin')->user();
        return view('admin.profile', compact('profile'));
    }

    public function admin_update_profile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'phone_no' => 'required|unique:users,phone_no,' . Auth::guard('admin')->id(),
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $profile = Auth::guard('admin')->user();

        $profile->name = $request->name;

        if ($request->filled('phone_no') && $profile->phone_no !== $request->phone_no) {
            $profile->phone_no = $request->phone_no;
        }

        if ($request->filled('password')) {
            $profile->password = Hash::make($request->password);
        }
        $profile->save();
        Auth::guard('admin')->login($profile);

        return redirect()->route('admin.dash');
    }

    public function create_unit()
    {
        return view('admin.unit.unit');
    }

    public function store_unit(Request $request)
    {
        $request->validate([
            'unit_name' => 'required',
            'unit_symbol' => 'required',
            'unit_type' => 'required',
            // 'status' => 'required'
        ]);

        $unit = new Unit();

        $unit->unit_name = $request->unit_name;
        $unit->unit_symbol = $request->unit_symbol;
        $unit->unit_type = $request->unit_type;
        $unit->status = $request['status'] ? 1 : 0;

        $unit->save();

        return redirect()->route('unit.index');
    }

    public function index_unit(Request $request)
    {
        $units = Unit::paginate(3);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.unit_table', compact('units'))->render(),
                // 'pagination' => (string) $users->links()  ,
            ]);
        }
        return view('admin.unit.unit_table', compact('units'));
    }

    public function edit_unit(string $id)
    {
        $unit = Unit::findorfail($id);
        return view('admin.unit.unit', compact('unit'));
    }

    public function update_unit(Request $request, string $id)
    {
        $request->validate([
            'unit_name' => 'required',
            'unit_symbol' => 'required',
            'unit_type' => 'required',
        ]);

        $unit = Unit::findorfail($id);

        $unit->unit_name = $request->unit_name;
        $unit->unit_symbol = $request->unit_symbol;
        $unit->unit_type = $request->unit_type;
        $unit->status = $request['status'] ? 1 : 0;

        $unit->save();

        return redirect()->route('unit.index');
    }

    public function settings()
    {
        $price = Setting::first();
        return view('admin.settings.setting', ['price' => $price ? $price->value : null]);
    }

    public function price_store(Request $request)
    {
        $request->validate([
            'price' => 'required'
        ]);

        $price = Setting::first();

        if ($price) {
            $price->value = $request->price;
            $price->save();
            return redirect()->back();
        } else {
            $price = new Setting();
            $price->value = $request->price;

            $price->save();
            return redirect()->back();
        }
    }

    public function fetch_price()
    {
        $price = Setting::where('key', 'price')->value('value');
        return response()->json(['price' => $price]);
    }

    public function receive_payment()
    {
        $clients = Client::with(['transactions' => function ($query) {
            $query->selectRaw('client_id, SUM(price) as total_amount')->groupBy('client_id');
        }])->get();
        // dd($clients->toArray());
        return view('admin.payment.payment', compact('clients'));
    }

    public function payment_store(Request $request)
    {
        $request->validate([
            'date' => 'required',
            'client_id' => 'nullable|exists:clients,id',
            'amount' => 'required|numeric',
            'note' => 'nullable'
        ]);

        $type = request('type') === 'd' ? 'd' : 'c';

        $payment = new Payment();

        $payment->date = $request->date;
        $payment->client_id = $request->client_id;
        $payment->amount = $request->amount;
        $payment->note = $request->note;
        $payment->type = $type;

        $payment->save();

        $totalAmount = Payment::where('client_id', $request->client_id)->sum('amount');
        $totalDue = Transaction::where('client_id', $request->client_id)->sum('price');
        $pendingAmount = $totalDue - $totalAmount;

        $advancePayment = $pendingAmount < 0 ? abs($pendingAmount) : 0;
        
        return response()->json([
            'total_amount' => $totalAmount,
            'pending_amount' => max($pendingAmount, 0),
            'advance_payment' => $advancePayment
        ]);

    }

    public function expense()
    {
        return view('admin.payment.expense');
    }

    public function payment_report()
    {
        $clients = Client::all();
        $payments = Payment::with('client')->get();
        // dd($payments);

        $totalCredit = Payment::where('type', 'c')->sum('amount');
        $totalDebit = Payment::where('type', 'd')->sum('amount');
        $balance = $totalCredit - $totalDebit;
        return view('admin.payment.payment_report', compact('clients', 'payments', 'balance'));
    }

    public function payment_filter(Request $request)
    {
        $query = Payment::with('client');

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        if ($request->client_id) {
            $query->where('client_id', $request->client_id);
        }

        $payments = $query->get();
        return response()->json(['payments' => $payments]);
    }
}
