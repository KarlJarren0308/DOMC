<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Accounts;
use App\Authors;
use App\Faculties;
use App\Librarians;
use App\Loans;
use App\Materials;
use App\Publishers;
use App\Receives;
use App\Reservations;
use App\Students;
use App\Works;

date_default_timezone_set('Asia/Manila');

class PanelController extends Controller
{
    public function getIndex() {
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

        return view('panel.index');
    }

    public function getLoan() {
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

        $data['accounts'] = Accounts::orderBy('Account_Type', 'asc')->get();
        $data['faculty_accounts'] = new Faculties;
        $data['librarian_accounts'] = new Librarians;
        $data['student_accounts'] = new Students;
        $data['works_authors'] = Works::join('authors', 'works.Author_ID', '=', 'authors.Author_ID')->get();
        $data['works_materials'] = Works::join('materials', 'works.Material_ID', '=', 'materials.Material_ID')->groupBy('works.Material_ID')->get();

        return view('panel.loan', $data);
    }

    public function getReserved() {
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

        $data['works_reservations'] = Reservations::where('reservations.Reservation_Status', 'active')->join('materials', 'reservations.Material_ID', '=', 'materials.Material_ID')->join('accounts', 'reservations.Account_Username', '=', 'accounts.Account_Username')->get();
        $data['faculty_accounts'] = Faculties::get();
        $data['librarian_accounts'] = Librarians::get();
        $data['student_accounts'] = Students::get();
        $data['works_authors'] = Works::join('authors', 'works.Author_ID', '=', 'authors.Author_ID')->get();
        $data['works_materials'] = Works::join('materials', 'works.Material_ID', '=', 'materials.Material_ID')->groupBy('works.Material_ID')->get();

        return view('panel.reserved', $data);
    }

    public function getReceive() {
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

        $data['loans'] = Loans::join('materials', 'loans.Material_ID', '=', 'materials.Material_ID')->join('accounts', 'loans.Account_Username', '=', 'accounts.Account_Username')->get();
        $data['faculty_accounts'] = Faculties::get();
        $data['librarian_accounts'] = Librarians::get();
        $data['student_accounts'] = Students::get();
        $data['works_authors'] = Works::join('authors', 'works.Author_ID', '=', 'authors.Author_ID')->get();
        $data['works_materials'] = Works::join('materials', 'works.Material_ID', '=', 'materials.Material_ID')->groupBy('works.Material_ID')->get();

        return view('panel.receive', $data);
    }

    public function getManage($what) {
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
                return view('errors.503');

                break;
            default:
                return view('errors.404');

                break;
        }
    }

    public function getAdd($what) {
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
            case 'materials':
                $data['publishers'] = Publishers::get();
                $data['authors'] = Authors::get();
                
                return view('panel.materials_add', $data);

                break;
            case 'authors':
                return view('panel.authors_add', $data);

                break;
            case 'publishers':
                return view('panel.publishers_add', $data);

                break;
            case 'students':
                return view('panel.students_add', $data);

                break;
            case 'faculties':
                return view('panel.faculties_add', $data);

                break;
            default:
                return view('errors.404');

                break;
        }
    }

    public function getEdit($what) {
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

        /*
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
        */

        return view('errors.503');
    }

    public function getDelete($what, $id) {
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
            case 'materials':
                $query = Materials::where('Material_ID', $id)->delete();

                if($query) {
                    $query = Works::where('Material_ID', $id)->delete();
                    
                    session()->flash('global_status', 'Success');
                    session()->flash('global_message', 'Material has been deleted.');
                } else {
                    session()->flash('global_status', 'Failed');
                    session()->flash('global_message', 'Failed to delete material.');
                }

                return redirect()->route('panel.getManage', 'materials');

                break;
            case 'authors':
                $query = Authors::where('Author_ID', $id)->delete();

                if($query) {
                    session()->flash('global_status', 'Success');
                    session()->flash('global_message', 'Author has been deleted.');
                } else {
                    session()->flash('global_status', 'Failed');
                    session()->flash('global_message', 'Failed to delete author.');
                }

                return redirect()->route('panel.getManage', 'authors');

                break;
            case 'publishers':
                $query = Publishers::where('Publisher_ID', $id)->delete();

                if($query) {
                    session()->flash('global_status', 'Success');
                    session()->flash('global_message', 'Publisher has been deleted.');
                } else {
                    session()->flash('global_status', 'Failed');
                    session()->flash('global_message', 'Failed to delete publisher.');
                }

                return redirect()->route('panel.getManage', 'publishers');

                break;
            case 'students':
                $query = Students::where('Student_ID', $id)->delete();

                if($query) {
                    $query = Accounts::where('Account_Type', 'Student')->where('Account_Owner', $id)->delete();

                    session()->flash('global_status', 'Success');
                    session()->flash('global_message', 'Student has been deleted.');
                } else {
                    session()->flash('global_status', 'Failed');
                    session()->flash('global_message', 'Failed to delete student.');
                }

                return redirect()->route('panel.getManage', 'students');

                break;
            case 'faculties':
                $query = Faculties::where('Faculty_ID', $id)->delete();

                if($query) {
                    $query = Accounts::where('Account_Type', 'Faculty')->where('Account_Owner', $id)->delete();
                    
                    session()->flash('global_status', 'Success');
                    session()->flash('global_message', 'Faculty has been deleted.');
                } else {
                    session()->flash('global_status', 'Failed');
                    session()->flash('global_message', 'Failed to delete faculty.');
                }

                return redirect()->route('panel.getManage', 'faculties');

                break;
            default:
                return view('errors.404');

                break;
        }
    }

    public function postLoan(Request $request) {
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

        switch($request->input('arg0')) {
            case 'f6614d9e3adf79e5eecc16a5405e8461':
                // arg0: loanAvailable
                $materialID = $request->input('arg1');
                $accountUsername = $request->input('arg2');

                $query = Loans::insert(array('Material_ID' => $materialID, 'Account_Username' => $accountUsername, 'Loan_Date_Stamp' => date('Y-m-d'), 'Loan_Time_Stamp' => date('H:i:s')));

                if($query) {
                    session()->flash('global_status', 'Success');
                    session()->flash('global_message', 'Loan Successful.');
                } else {
                    session()->flash('global_status', 'Warning');
                    session()->flash('global_message', 'Oops! Failed to loan material to the borrower.');
                }

                return redirect()->route('panel.getLoan');

                break;
            case 'bcfaa2f57da331c29c0bab9f99543451':
                // arg0: loanReserved
                $id = $request->input('arg1');

                $reservation = Reservations::where('Reservation_ID', $id)->first();

                if($reservation) {
                    $datetime = date('Y-m-d H:i:s', strtotime($reservation->Reservation_Date_Stamp . ' ' . $reservation->Reservation_Time_Stamp));

                    if(strtotime('+1 day', strtotime($datetime)) >= strtotime(date('Y-m-d H:i:s'))) {
                        $query = Reservations::where('Reservation_ID', $id)->update(array('Reservation_Status' => 'inactive'));

                        if($query) {
                            $query = Loans::insert(array('Material_ID' => $reservation->Material_ID, 'Account_Username' => $reservation->Account_Username, 'Loan_Date_Stamp' => date('Y-m-d'), 'Loan_Time_Stamp' => date('H:i:s'), 'Loan_Reference' => $id));

                            if($query) {
                                session()->flash('global_status', 'Success');
                                session()->flash('global_message', 'Loan Successful.');
                            } else {
                                session()->flash('global_status', 'Warning');
                                session()->flash('global_message', 'Oops! Failed to loan material to the borrower. Borrower\'s reservation has been cancelled by the system.');
                            }
                        } else {
                            session()->flash('global_status', 'Warning');
                            session()->flash('global_message', 'Oops! Failed to loan material to the borrower. Request has been interrupted.');
                        }
                    } else {
                        session()->flash('global_status', 'Failed');
                        session()->flash('global_message', 'Oops! This reservation has already expired.');
                    }
                } else {
                    session()->flash('global_status', 'Failed');
                    session()->flash('global_message', 'Oops! This reservation doesn\'t exist.');
                }

                return redirect()->route('panel.getReserved');

                break;
            default:
                break;
        }
    }

    public function postReceive(Request $request) {
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

        $id = $request->input('arg0');

        $loan = Loans::where('Loan_ID', $id)->first();

        if($loan) {
            if($loan->Loan_Status == 'active') {
                $query = Receives::insert(array('Material_ID' => $loan->Material_ID, 'Account_Username' => $loan->Account_Username, 'Receive_Date_Stamp' => date('Y-m-d'), 'Receive_Time_Stamp' => date('H:i:s'), 'Receive_Reference' => $id));

                if($query) {
                    $query = Loans::where('Loan_ID', $id)->update(array('Loan_Status' => 'inactive'));
                    
                    session()->flash('global_status', 'Success');
                    session()->flash('global_message', 'Receive Successful.');
                } else {
                    session()->flash('global_status', 'Warning');
                    session()->flash('global_message', 'Oops! Failed to receive material.');
                }
            } else {
                session()->flash('global_status', 'Warning');
                session()->flash('global_message', 'Oops! Borrower has already returned this material.');
            }
        } else {
            session()->flash('global_status', 'Failed');
            session()->flash('global_message', 'Oops! This loan doesn\'t exist.');
        }

        return redirect()->route('panel.getReceive');
    }

    public function postAdd($what, Request $request) {
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
        $res = '';

        switch($what) {
            case 'materials':
                $materialID = Materials::insertGetId(array(
                    'Material_Title' => $request->input('materialTitle'),
                    'Material_Collection_Type' => $request->input('materialCollectionType'),
                    'Material_ISBN' => $request->input('materialISBN'),
                    'Material_Call_Number' => $request->input('materialCallNumber'),
                    'Material_Location' => $request->input('materialLocation'),
                    'Material_Date_Published' => $request->input('materialDatePublished'),
                    'Material_Copies' => $request->input('materialCopies'),
                    'Publisher_ID' => $request->input('publisher')
                ));

                if($materialID) {
                    $ctr = 0;

                    foreach($request->input('authors') as $authorID) {
                        $query = Works::insert(array(
                            'Material_ID' => $materialID,
                            'Author_ID' => $authorID
                        ));

                        if($query) {
                            $ctr++;
                        }
                    }

                    if($ctr > 0) {
                        session()->flash('global_status', 'Success');
                        session()->flash('global_message', 'Material has been added.');
                    } else {
                        session()->flash('global_status', 'Failed');
                        session()->flash('global_message', 'Failed to associate author(s) to the material.');
                    }
                } else {
                    session()->flash('global_status', 'Failed');
                    session()->flash('global_message', 'Failed to add material.');
                }

                return redirect()->route('panel.getManage', 'materials');

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

    public function postTest(Request $request) {
        return $request->all();
    }
}

