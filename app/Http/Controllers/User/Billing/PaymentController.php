<?php

namespace App\Http\Controllers\User\Billing;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerBranch;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(): Factory|View|Application
    {
        return view('user.billing.receive_payment');
    }

    public function customerUnpaidInvoices(CustomerBranch $customer): JsonResponse
    {
        $invoices = $customer->getUnpaidInvoices();
        return success_web_processor($invoices, 'Success');
    }
}
