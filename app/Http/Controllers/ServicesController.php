<?php

namespace App\Http\Controllers;

use App\Models\BvnPhoneSearch;
use App\Models\NinValidation;
use App\Models\Service;
use App\Models\Wallet;
use App\Services\TransactionService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ServicesController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }


    public function userCheck()
    {

        if (auth()->user()->role != 'super admin')
            abort(403, 'Unauthorized');
    }

    public function index(Request $request)
    {
        $this->userCheck();
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $query = Service::query();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        }

        $services = $query->paginate($perPage)->withQueryString();

        return view('services.index', compact('services'));
    }

    public function edit($id)
    {
        $this->userCheck();

        $service = Service::findOrFail($id);
        return view('services.edit', compact('service'));
    }

    public function update(Request $request, $id)
    {
        $this->userCheck();
        $request->validate([
            'amount' => 'required|numeric',
            'description' => 'nullable',
            'status' => 'required|in:enabled,disabled',
        ]);

        $service = Service::findOrFail($id);
        $service->update($request->all());
        return redirect()->route('admin.services.index')->with('success', 'Service Updated Successfully!');
    }

    public function ninServicesList(Request $request)
    {

        // Services
        $pending = NinValidation::whereIn('status', ['pending', 'processing'])
            ->count();

        $resolved = NinValidation::where('status', 'resolved')
            ->count();

        $rejected = NinValidation::where('status', 'rejected')
            ->count();

        $total_request = NinValidation::count();

        $query = NinValidation::with(['user', 'transactions']); // Load related data

        if ($request->filled('search')) { // Check if search input is provided
            $searchTerm = $request->search;

            $query->where(function ($q) use ($searchTerm) {
                $q->where('refno', 'like', "%{$searchTerm}%") // Search in Reference No.
                    ->orWhere('nin_number', 'like', "%{$searchTerm}%") // Search in BMS ID
                    ->orWhere('status', 'like', "%{$searchTerm}%") // Search in Status
                    ->orWhereHas('user', function ($subQuery) use ($searchTerm) { // Search in User fields
                        $subQuery->where('name', 'like', "%{$searchTerm}%");
                    });
            });
        }

        // Check if date_from and date_to are provided and filter accordingly
        if ($dateFrom = request('date_from')) {
            $query->whereDate('created_at', '>=', $dateFrom); // Adjust 'created_at' to your date field
        }

        if ($dateTo = request('date_to')) {
            $query->whereDate('created_at', '<=', $dateTo); // Adjust 'created_at' to your date field
        }

        $nin_services = $query
            ->orderByRaw("
                CASE
                    WHEN status = 'pending' THEN 1
                    WHEN status = 'processing' THEN 2
                    ELSE 3
                END
            ") // Prioritize 'pending' first, then 'processing', and others last
            ->orderByDesc('id') // Sort by latest record within the same priority
            ->paginate(10);


        $request_type = 'nin-services';

        return view('admin.nin-services-list', compact(
            'pending',
            'resolved',
            'rejected',
            'total_request',
            'nin_services',
            'request_type'
        ));
    }

    public function showRequests($request_id, $type, $requests = null)
    {

        switch ($type) {
            case 'bvn-enrollment':

                break;
            case 'bvn-modification':

                break;
            case 'upgrade':

                break;

            case 'nin-services':
                $requests = NinValidation::with(['user', 'transactions'])->findOrFail($request_id);
                $request_type = 'nin-services';
                break;

            case 'vnin-to-nibss':

                break;

            default:
                $requests = NinValidation::with(['user', 'transactions'])->findOrFail($request_id);
                $request_type = 'nin-services';
        }

        if (strtolower($requests->status) == 'rejected') {
            abort(404, 'Kindly Submit a new request');
        }

        return view(
            'admin.view-request',
            compact(
                'requests',
                'request_type'
            )
        );
    }

    public function updateRequestStatus(Request $request, $id, $type)
    {
        $request->validate([
            'status' => 'required|string',
            'comment' => 'required|string',
        ]);

        $requestDetails = NinValidation::findOrFail($id);
        $route = 'admin.nin.services.list';
        $status = $request->status;


        $requestDetails->status = $status;
        $requestDetails->reason = $request->comment;


        if ($request->status === 'rejected') {

            $requestDetails->refunded_at = Carbon::now();

            $refundAmount = $request->refundAmount;

            $wallet = Wallet::where('user_id', $requestDetails->user_id)->first();

            $balance = $wallet->balance + $refundAmount;

            Wallet::where('user_id', $requestDetails->user_id)
                ->update(['balance' => $balance]);

            $serviceDesc = 'Wallet credited with a Request fee of ₦' . number_format($refundAmount, 2);

            $this->transactionService->createTransaction($requestDetails->user_id, $refundAmount, 'NIN Phone Search Refund', $serviceDesc,  'Wallet', 'Approved');
        }

        $requestDetails->save();

        return redirect()->route($route)->with('success', 'Request status updated successfully.');
    }

    public function bvnServicesList(Request $request)
    {

        // Services
        $pending = BvnPhoneSearch::whereIn('status', ['pending', 'processing'])
            ->count();

        $resolved = BvnPhoneSearch::where('status', 'resolved')
            ->count();

        $rejected = BvnPhoneSearch::where('status', 'rejected')
            ->count();

        $total_request = BvnPhoneSearch::count();

        $query = BvnPhoneSearch::with(['user', 'transactions']); // Load related data

        if ($request->filled('search')) { // Check if search input is provided
            $searchTerm = $request->search;

            $query->where(function ($q) use ($searchTerm) {
                $q->where('refno', 'like', "%{$searchTerm}%") // Search in Reference No.
                    ->orWhere('phone_number', 'like', "%{$searchTerm}%") // Search in BMS ID
                    ->orWhere('name', 'like', "%{$searchTerm}%") // Search in BMS ID
                    ->orWhere('status', 'like', "%{$searchTerm}%") // Search in Status
                    ->orWhereHas('user', function ($subQuery) use ($searchTerm) { // Search in User fields
                        $subQuery->where('name', 'like', "%{$searchTerm}%");
                    });
            });
        }

        // Check if date_from and date_to are provided and filter accordingly
        if ($dateFrom = request('date_from')) {
            $query->whereDate('created_at', '>=', $dateFrom); // Adjust 'created_at' to your date field
        }

        if ($dateTo = request('date_to')) {
            $query->whereDate('created_at', '<=', $dateTo); // Adjust 'created_at' to your date field
        }

        $bvn_services = $query
            ->orderByRaw("
                CASE
                    WHEN status = 'pending' THEN 1
                    WHEN status = 'processing' THEN 2
                    ELSE 3
                END
            ") // Prioritize 'pending' first, then 'processing', and others last
            ->orderByDesc('id') // Sort by latest record within the same priority
            ->paginate(10);


        $request_type = 'bvn-services';

        return view('admin.bvn-services-list', compact(
            'pending',
            'resolved',
            'rejected',
            'total_request',
            'bvn_services',
            'request_type'
        ));
    }

    public function showBvnRequests($request_id, $type, $requests = null)
    {

        switch ($type) {
            case 'bvn-enrollment':

                break;
            case 'bvn-modification':

                break;
            case 'upgrade':

                break;

            case 'nin-services':
                $requests = BvnPhoneSearch::with(['user', 'transactions'])->findOrFail($request_id);
                $request_type = 'nin-services';
                break;

            case 'vnin-to-nibss':

                break;

            default:
                $requests = BvnPhoneSearch::with(['user', 'transactions'])->findOrFail($request_id);
                $request_type = 'nin-services';
        }

        if (strtolower($requests->status) == 'rejected') {
            abort(404, 'Kindly Submit a new request');
        }

        return view(
            'admin.view-bvn-request',
            compact(
                'requests',
                'request_type'
            )
        );
    }

    public function updateBvnRequestStatus(Request $request, $id, $type)
    {
        $request->validate([
            'status' => 'required|string',
            'comment' => 'required|string',
        ]);

        $requestDetails = BvnPhoneSearch::findOrFail($id);
        $route = 'admin.bvn.services.list';
        $status = $request->status;


        $requestDetails->status = $status;
        $requestDetails->reason = $request->comment;


        if ($request->status === 'rejected') {

            $requestDetails->refunded_at = Carbon::now();

            $refundAmount = $request->refundAmount;

            $wallet = Wallet::where('user_id', $requestDetails->user_id)->first();

            $balance = $wallet->balance + $refundAmount;

            Wallet::where('user_id', $requestDetails->user_id)
                ->update(['balance' => $balance]);

            $serviceDesc = 'Wallet credited with a Request fee of ₦' . number_format($refundAmount, 2);

            $this->transactionService->createTransaction($requestDetails->user_id, $refundAmount, 'BVN Search Refund', $serviceDesc,  'Wallet', 'Approved');
        }

        $requestDetails->save();

        return redirect()->route($route)->with('success', 'Request status updated successfully.');
    }
}
