<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Accounts;
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
                return view('panel.settings', $data);

                break;
            default:
                return view('errors.404');

                break;
        }
    }

    public function getAdd($what, $status = null) {
        $data['what'] = $what;
        $data['status'] = $status;

        switch($what) {
            case 'materials':
                return view('panel.materials_add', $data);

                break;
            case 'authors':
                $data['authors'] = Authors::get();

                return view('panel.authors_add', $data);

                break;
            case 'publishers':
                $data['publishers'] = Publishers::get();

                return view('panel.publishers_add', $data);

                break;
            case 'students':
                $data['students'] = Students::get();

                return view('panel.students_add', $data);

                break;
            case 'faculties':
                $data['faculties'] = Faculties::get();

                return view('panel.faculties_add', $data);

                break;
            default:
                return view('errors.404');

                break;
        }
    }

    public function postAdd($what, Request $request) {
        $data['what'] = $what;
        $res = '';

        switch($what) {
            case 'materials':
                return view('panel.materials_add', $data);

                break;
            case 'authors':
                $query = Authors::insert(array(
                    'Author_First_Name' => $request->input('authorFirstName'),
                    'Author_Middle_Name' => $request->input('authorMiddleName'),
                    'Author_Last_Name' => $request->input('authorLastName')
                ));

                if($query) {
                    session()->flash('global_status', 'Success');
                    session()->flash('global_message', 'Author has been added.');
                } else {
                    session()->flash('global_status', 'Failed');
                    session()->flash('global_message', 'Failed to add author.');
                }

                return redirect()->route('panel.getManage', 'authors');

                break;
            case 'publishers':
                $query = Publishers::insert(array(
                    'Publisher_Name' => $request->input('publisherName')
                ));

                if($query) {
                    session()->flash('global_status', 'Success');
                    session()->flash('global_message', 'Publisher has been added.');
                } else {
                    session()->flash('global_status', 'Failed');
                    session()->flash('global_message', 'Failed to add publisher.');
                }

                return redirect()->route('panel.getManage', 'publishers');

                break;
            case 'students':
                $id = Students::insertGetId(array(
                    'Student_First_Name' => $request->input('studentFirstName'),
                    'Student_Middle_Name' => $request->input('studentMiddleName'),
                    'Student_Last_Name' => $request->input('studentLastName'),
                    'Student_Birth_Date' => $request->input('studentBirthDate')
                ));

                if($id) {
                    $query = Accounts::insert(array(
                        'Account_Username' => $request->input('studentID'),
                        'Account_Password' => md5($request->input('studentBirthDate')),
                        'Account_Type' => 'Student',
                        'Account_Owner' => $id
                    ));

                    if($query) {
                        session()->flash('global_status', 'Success');
                        session()->flash('global_message', 'Student has been added.');
                    } else {
                        session()->flash('global_status', 'Warning');
                        session()->flash('global_message', 'Student has been added but account was not created.');
                    }
                } else {
                    session()->flash('global_status', 'Failed');
                    session()->flash('global_message', 'Failed to add faculty.');
                }

                return redirect()->route('panel.getManage', 'students');

                break;
            case 'faculties':
                $id = Faculties::insertGetId(array(
                    'Faculty_First_Name' => $request->input('facultyFirstName'),
                    'Faculty_Middle_Name' => $request->input('facultyMiddleName'),
                    'Faculty_Last_Name' => $request->input('facultyLastName'),
                    'Faculty_Birth_Date' => $request->input('facultyBirthDate')
                ));

                if($id) {
                    $query = Accounts::insert(array(
                        'Account_Username' => $request->input('facultyID'),
                        'Account_Password' => $request->input('facultyBirthDate'),
                        'Account_Type' => 'Faculty',
                        'Account_Owner' => $id
                    ));

                    if($query) {
                        session()->flash('global_status', 'Success');
                        session()->flash('global_message', 'Faculty has been added.');
                    } else {
                        session()->flash('global_status', 'Warning');
                        session()->flash('global_message', 'Faculty has been added but account was not created.');
                    }
                } else {
                    session()->flash('global_status', 'Failed');
                    session()->flash('global_message', 'Failed to add faculty.');
                }

                return redirect()->route('panel.getManage', 'faculties');

                break;
            default:
                return view('errors.404');

                break;
        }
    }
}
