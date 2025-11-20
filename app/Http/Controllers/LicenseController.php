<?php

namespace App\Http\Controllers;

use App\Models\License;
use App\Models\LicensesType;
use Illuminate\Http\Request;
use Illuminate\Encryption\Encrypter;

class LicenseController extends Controller
{
    public function index()
    {
        // dd(LicensesType::where('_name', operator: 'Enterprise')->first()->akaeay_);

        $licenses = LicensesType::all();
        return view('admin.licenses.index', compact('licenses'));
    }

    public function upgrade(Request $request)
    {
        $license = License::first();
        $key = $request->upgrade_key;
        $keyser = '3bn87gedb3d87bds';
        $encrypter = new Encrypter($keyser, 'AES-128-CBC');
        $key = $encrypter->decrypt($key);
        if ($key === LicensesType::where('_name', 'Pro')->first()->akaeay_) {
            $license->update(['akaeay_' => $key]);
            $message = __('licenses.message.pro_success');
        } elseif ($key ===  LicensesType::where('_name', 'Basic')->first()->akaeay_) {
            $license->update(['akaeay_' => $key]);
            $message = __('licenses.message.basic_success');
        } elseif ($key === LicensesType::where('_name', 'Enterprise')->first()->akaeay_) {
            $license->update(['akaeay_' => $key]);
            $message = __('licenses.message.enterprise_success');
        } else {
            $message = __('licenses.message.invalid_key');
        }


        return back()->with('status', $message);
    }
}
