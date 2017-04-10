<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Accounts;
use App\Accessions;
use App\Authors;
use App\Faculties;
use App\Students;
use App\Librarians;
use App\Loans;
use App\Materials;
use App\Publishers;
use App\Reservations;
use App\Receives;
use App\Works;
use App\Holidays;

use Storage;

date_default_timezone_set('Asia/Manila');

class SearchController extends Controller
{
    private $reservationLimit = 3;
    private $loanLimit = 3;

    private function checkConfigurationFile() {
        if(!Storage::has('configuration.xml')) {
            Storage::put('configuration.xml', '<?xml version="1.0" encoding="UTF-8"?><settings><setting name="opac" value="2"/><setting name="reservation" value="Hide"/><setting name="penaltyDays" value="1"/><setting name="penaltyAmount" value="5"/></settings>');
        }
    }

    public function postSearch($what, Request $request) {
        if(!session()->has('username')) {
            session()->flash('global_status', 'Failed');
            session()->flash('global_message', 'Oops! Please login first.');

            return redirect()->route('main.getLogin');
        } else {
            if(session()->get('account_type') != 'Librarian') {
                session()->flash('global_status', 'Failed');
                session()->flash('global_message', 'Oops! You are not authorized to access the panel.');

                return redirect()->route('main.getOpac');
            }
        }

        $data['what'] = $what;

        switch($what) {
            case 'accessions':
                $materialID = $request->input('materialID');

                $query = Accessions::where('Material_ID', $materialID)->where('Accession_Status', 'available')->get();

                if($query) {
                    return json_encode(array('status' => 'Success', 'message' => 'Book has been found.', 'data' => $query));
                } else {
                    return json_encode(array('status' => 'Failed', 'message' => 'Book not found.'));
                }

                break;
            case 'materials':
                $query = Materials::where('Material_Call_Number', $request->input('call_number'))->orWhere('Material_Title', $request->input('title'))->count();

                if($query > 0) {
                    return json_encode(array('status' => 'Success', 'message' => 'Book has been found.'));
                } else {
                    return json_encode(array('status' => 'Failed', 'message' => 'Book not found.'));
                }

                break;
            case 'username':
                $query = Accounts::where('Account_Username', $request->input('username'))->first();

                if($query) {
                    return json_encode(array('status' => 'Success', 'message' => 'Username has been found.'));
                } else {
                    return json_encode(array('status' => 'Failed', 'message' => 'Username not found.'));
                }

                break;
            case 'librarians':
                $query = Librarians::where('Librarian_First_Name', $request->input('first_name'))->where('Librarian_Last_Name', $request->input('last_name'))->count();

                if($query > 0) {
                    return json_encode(array('status' => 'Success', 'message' => 'Librarian has been found.'));
                } else {
                    return json_encode(array('status' => 'Failed', 'message' => 'Librarian not found.'));
                }

                break;
            case 'users':
                $query = Faculties::where('Faculty_First_Name', $request->input('first_name'))->where('Faculty_Last_Name', $request->input('last_name'))->count();

                if($query > 0) {
                    return json_encode(array('status' => 'Success', 'User has been found.'));
                } else {
                    $query = Students::where('Student_First_Name', $request->input('first_name'))->where('Student_Last_Name', $request->input('last_name'))->count();

                    if($query > 0) {
                        return json_encode(array('status' => 'Success', 'message' => 'User has been found.'));
                    } else {
                        return json_encode(array('status' => 'Failed', 'message' => 'User not found.'));
                    }
                }

                break;
            case 'authors':
                $data['authors'] = Authors::get();

                if($data['authors']) {
                    return json_encode(array('status' => 'Success', 'message' => 'Found some authors.', 'data' => $data));
                } else {
                    return json_encode(array('status' => 'Failed', 'message' => 'No results found.'));
                }

                break;
            case 'publishers':
                $data['publishers'] = Publishers::get();

                if($data['publishers']) {
                    return json_encode(array('status' => 'Success', 'message' => 'Found some publishers.', 'data' => $data));
                } else {
                    return json_encode(array('status' => 'Failed', 'message' => 'No results found.'));
                }

                break;
            case 'loan_borrowers':
                $data['users'] = Accounts::where('accounts.Account_Username', 'like', '%' . $request->input('searchKeyword') . '%')->whereIn('accounts.Account_Type', ['Student', 'Faculty'])
                    ->leftJoin('students', function($join) {
                        $join->on('accounts.Account_Owner', '=', 'students.Student_ID')->where('accounts.Account_Type', '=', 'Student');
                    })
                    ->leftJoin('faculties', function($join) {
                        $join->on('accounts.Account_Owner', '=', 'faculties.Faculty_ID')->where('accounts.Account_Type', '=', 'Faculty');
                    })
                ->get();

                if($data['users']) {
                    return json_encode(array('status' => 'Success', 'message' => 'Found some users.', 'data' => $data));
                } else {
                    return json_encode(array('status' => 'Failed', 'message' => 'No results found.'));
                }

                break;
            case 'loan_books':
                $data['works_authors'] = Works::join('authors', 'works.Author_ID', '=', 'authors.Author_ID')->get();
                $data['works_materials'] = Works::where('materials.Material_Title', 'like', '%' . $request->input('searchKeyword') . '%')->orWhere('materials.Material_Call_Number', 'like', '%' . $request->input('searchKeyword') . '%')->orWhere('authors.Author_First_Name', 'like', '%' . $request->input('searchKeyword') . '%')->orWhere('authors.Author_Middle_Name', 'like', '%' . $request->input('searchKeyword') . '%')->orWhere('authors.Author_Last_Name', 'like', '%' . $request->input('searchKeyword') . '%')->orWhereRaw("CONCAT(authors.Author_First_Name, ' ', authors.Author_Last_Name) LIKE '%" . $request->input('searchKeyword') . "%'")->orWhereRaw("CONCAT(authors.Author_First_Name, ' ', authors.Author_Middle_Name, ' ', authors.Author_Last_Name) LIKE '%" . $request->input('searchKeyword') . "%'")->join('materials', 'works.Material_ID', '=', 'materials.Material_ID')->leftJoin('authors', 'works.Author_ID', '=', 'authors.Author_ID')->groupBy('works.Material_ID')->get();
                $data['materials_publishers'] = Materials::join('publishers', 'materials.Publisher_ID', '=', 'publishers.Publisher_ID')->get();
                $data['reservations'] = Reservations::where('Account_Username', session()->get('username'))->where('Reservation_Status', 'active')->get();
                $data['reserved_materials'] = Reservations::where('Reservation_Status', 'active')->get();
                $data['loaned_materials'] = Loans::where('Loan_Status', 'active')->get();
                $data['accession_numbers'] = Accessions::where('Accession_Status', 'available')->get();

                if($data['works_materials']) {
                    return json_encode(array('status' => 'Success', 'message' => 'Found some users.', 'data' => $data));
                } else {
                    return json_encode(array('status' => 'Failed', 'message' => 'No results found.'));
                }

                break;
            case 'receive':
                $this->checkConfigurationFile();

                $data['configs'] = simplexml_load_file(storage_path('app') . '/configuration.xml');

                foreach($data['configs'] as $config) {
                    if($config['name'] == 'penaltyAmount') {
                        $perDayPenalty = $config['value'];
                    } else if($config['name'] == 'penaltyDays') {
                        $startPenaltyAfter = $config['value'];
                    }
                }

                $data['per_day_penalty'] = $perDayPenalty;
                $data['start_penalty_after'] = $startPenaltyAfter;
                $data['holidays'] = Holidays::get();
                $data['loans'] = Loans::where('loans.Account_Username', 'like', $request->input('searchKeyword') . '%')->join('materials', 'loans.Material_ID', '=', 'materials.Material_ID')->join('accounts', 'loans.Account_Username', '=', 'accounts.Account_Username')->get();
                $data['receives'] = Receives::get();
                $data['faculty_accounts'] = Faculties::get();
                $data['librarian_accounts'] = Librarians::get();
                $data['student_accounts'] = Students::get();

                if($data['loans']) {
                    return json_encode(array('status' => 'Success', 'message' => 'Found some loans.', 'data' => $data));
                } else {
                    return json_encode(array('status' => 'Failed', 'message' => 'No results found.'));
                }

                break;
            default:
                return view('errors.404');

                break;
        }
    }
}
