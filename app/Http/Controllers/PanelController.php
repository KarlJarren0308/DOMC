<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Authors;
use App\Faculties;
use App\Publishers;
use App\Students;
use App\Works;

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
        $data['what'] = $what;

        switch($what) {
            case 'materials':
                $data['works_authors'] = Works::join('authors', 'works.Author_ID', '=', 'authors.Author_ID')->get();
                $data['works_materials'] = Works::join('materials', 'works.Material_ID', '=', 'materials.Material_ID')->groupBy('works.Material_ID')->get();

                return view('panel.materials', $data);

                break;
            case 'authors':
                $data['authors'] = Authors::get();

                return view('panel.authors', $data);

                break;
            case 'publishers':
                $data['publishers'] = Publishers::get();

                return view('panel.publishers', $data);

                break;
            case 'students':
                $data['students'] = Students::get();

                return view('panel.students', $data);

                break;
            case 'faculties':
                $data['faculties'] = Faculties::get();

                return view('panel.faculties', $data);

                break;
            case 'settings':
                return view('panel.settings');

                break;
            default:
                return view('errors.404');

                break;
        }
    }
}
