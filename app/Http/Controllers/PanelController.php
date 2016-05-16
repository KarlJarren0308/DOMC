<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

class PanelController extends Controller
{
    public function getIndex() {
        if(session()->has('username')) {
            if(session()->get('account_type') == 'Librarian') {
                return view('panel.index');
            }
        }

        return redirect()->route('main.getIndex');
    }

    public function getManage($what) {
        return view('errors.503');
    }
}
