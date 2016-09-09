<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Accounts;
use App\Authors;
use App\Faculties;
use App\Holidays;
use App\Librarians;
use App\Loans;
use App\Materials;
use App\Reservations;
use App\Students;
use App\Works;

use DB;
use Storage;

date_default_timezone_set('Asia/Manila');

class MainController extends Controller
{
    private $reservationLimit = 3;
    private $loanLimit = 3;

    private function checkConfigurationFile() {
        if(!Storage::has('configuration.xml')) {
            Storage::put('configuration.xml', '<?xml version="1.0" encoding="UTF-8"?><settings><setting name="opac" value="2"/><setting name="reservation" value="Hide"/><setting name="penaltyDays" value="1"/><setting name="penaltyAmount" value="5"/></settings>');
        }
    }

    public function getIndex() {
        $data['material'] = Materials::orderBy('Date_Added', 'desc')->first();
        $data['borrower'] = Loans::join('accounts', 'loans.Account_Username', '=', 'accounts.Account_Username')
            ->leftJoin('faculties', function($join) {
                $join->on('accounts.Account_Owner', '=', 'faculties.Faculty_ID')->where('accounts.Account_Type', '=', 'Faculty');
            })
            ->leftJoin('librarians', function($join) {
                $join->on('accounts.Account_Owner', '=', 'librarians.Librarian_ID')->where('accounts.Account_Type', '=', 'Librarian');
            })
            ->leftJoin('students', function($join) {
                $join->on('accounts.Account_Owner', '=', 'students.Student_ID')->where('accounts.Account_Type', '=', 'Student');
            })
        ->groupBy('loans.Account_Username')->orderBy('Row_Count', 'desc')->select('*', DB::raw('count(*) as Row_Count'))->first();
        $data['most_borrowed_material'] = Loans::join('materials', 'loans.Material_ID', '=', 'materials.Material_ID')->groupBy('loans.Material_ID')->orderBy('Row_Count', 'desc')->select('*', DB::raw('count(*) as Row_Count'))->first();

        return view('main.index', $data);
    }

    public function getLogin() {
        return view('main.login');
    }

    public function getAbout() {
        return view('main.about');
    }

    public function getOpac() {
        $data['works_authors'] = Works::join('authors', 'works.Author_ID', '=', 'authors.Author_ID')->get();
        $data['works_materials'] = Works::join('materials', 'works.Material_ID', '=', 'materials.Material_ID')->groupBy('works.Material_ID')->get();
        $data['reservations'] = Reservations::where('Account_Username', session()->get('username'))->where('Reservation_Status', 'active')->get();
        $data['reserved_materials'] = Reservations::where('Reservation_Status', 'active')->get();
        $data['loaned_materials'] = Loans::where('Loan_Status', 'active')->get();

        if(session()->has('username')) {
            $data['reservation_limit'] = $this->reservationLimit;
            $data['on_reserved'] = Reservations::where('Reservation_Status', 'active')->where('Account_Username', session()->get('username'))->count();
        }

        $this->checkConfigurationFile();

        $configs = simplexml_load_file(storage_path('app') . '/configuration.xml');
        $opacVersion = false;

        foreach($configs as $config) {
            if($config['name'] == 'opac') {
                $opacVersion = $config['value'];

                break;
            }
        }

        if($opacVersion != false) {
            return view('main.opac_' . $opacVersion, $data);
        } else {
            return view('main.opac_1', $data);
        }
    }

    public function getAccountInfo() {
        if(!session()->has('username')) {
            session()->flash('global_status', 'Failed');
            session()->flash('global_message', 'Oops! Please login first.');

            return redirect()->route('main.getLogin');
        }

        $this->checkConfigurationFile();

        $configs = simplexml_load_file(storage_path('app') . '/configuration.xml');
        $username = session()->get('username');

        foreach($configs as $config) {
            if($config['name'] == 'penaltyAmount') {
                $perDayPenalty = $config['value'];
            } else if($config['name'] == 'penaltyDays') {
                $startPenaltyAfter = $config['value'];
            } else if($config['name'] == 'reservation') {
                $reservation = $config['value'];
            }
        }

        $data['per_day_penalty'] = $perDayPenalty;
        $data['start_penalty_after'] = $startPenaltyAfter;
        $data['holidays'] = Holidays::get();
        $data['loans'] = Loans::where('loans.Account_Username', $username)->join('materials', 'loans.Material_ID', '=', 'materials.Material_ID')->leftJoin('publishers', 'materials.Publisher_ID', '=', 'publishers.Publisher_ID')->orderBy('loans.Loan_Status', 'asc')->leftJoin('receives', 'loans.Loan_ID', '=', 'receives.Receive_Reference')->get();
        $data['reservations'] = Reservations::where('reservations.Account_Username', $username)->join('materials', 'reservations.Material_ID', '=', 'materials.Material_ID')->leftJoin('publishers', 'materials.Publisher_ID', '=', 'publishers.Publisher_ID')->orderBy('reservations.Reservation_Status', 'asc')->get();
        $data['works_authors'] = Works::join('authors', 'works.Author_ID', '=', 'authors.Author_ID')->get();
        $data['my_account_one'] = Accounts::where('Account_Username', $username)->first();
        $data['on_hand'] = Loans::where('Loan_Status', 'active')->where('Account_Username', $username)->count();
        $data['on_reserved'] = Reservations::where('Reservation_Status', 'active')->where('Account_Username', $username)->count();
        
        if($data['my_account_one']->Account_Type == 'Faculty') {
            $data['my_account_two'] = Faculties::where('Faculty_ID', $data['my_account_one']->Account_Owner)->first();
        } else if($data['my_account_one']->Account_Type == 'Librarian') {
            $data['my_account_two'] = Librarians::where('Librarian_ID', $data['my_account_one']->Account_Owner)->first();
        } else if($data['my_account_one']->Account_Type == 'Student') {
            $data['my_account_two'] = Students::where('Student_ID', $data['my_account_one']->Account_Owner)->first();
        }

        return view('main.account_information', $data);
    }

    public function getChangePassword() {
        if(!session()->has('username')) {
            session()->flash('global_status', 'Failed');
            session()->flash('global_message', 'Oops! Please login first.');

            return redirect()->route('main.getLogin');
        }

        return view('main.change_password');
    }

    public function getLogout() {
        session()->flush();

        return redirect()->route('main.getIndex');
    }

    public function getReserve($what) {
        if(!session()->has('username')) {
            session()->flash('global_status', 'Failed');
            session()->flash('global_message', 'Oops! Please login first.');

            return redirect()->route('main.getLogin');
        }

        $reserved_materials = Reservations::where('Reservation_Status', 'active')->where('Material_ID', $what)->count();
        $loaned_materials = Loans::where('Loan_Status', 'active')->where('Material_ID', $what)->count();

        $materialRow = Materials::where('Material_ID', $what)->first();
        $newMaterialCount = $materialRow->Material_Copies - $reserved_materials - $loaned_materials;

        if($newMaterialCount > 0) {
            $on_reserved = Reservations::where('Reservation_Status', 'active')->where('Account_Username', session()->get('username'))->count();

            if($on_reserved < $this->reservationLimit) {
                $query = Reservations::insert(array(
                    'Material_ID' => $what,
                    'Account_Username' => session()->get('username'),
                    'Reservation_Date_Stamp' => date('Y-m-d'),
                    'Reservation_Time_Stamp' => date('H:i:s')
                ));

                if($query) {
                    session()->flash('global_status', 'Success');
                    session()->flash('global_message', 'Book has been reserved.');
                } else {
                    session()->flash('global_status', 'Failed');
                    session()->flash('global_message', 'Failed to reserve book.');
                }
            } else {
                session()->flash('global_status', 'Failed');
                session()->flash('global_message', 'Oops! You can only reserve at most 3 books at a time.');
            }
        } else {
            session()->flash('global_status', 'Failed');
            session()->flash('global_message', 'Oops! No more copies available.');
        }

        return redirect()->route('main.getOpac');
    }

    public function postChangePassword(Request $request) {
        if(!session()->has('username')) {
            session()->flash('global_status', 'Failed');
            session()->flash('global_message', 'Oops! Please login first.');

            return redirect()->route('main.getLogin');
        }

        if($request->input('newPassword') == $request->input('confirmPassword')) {
            $query = Accounts::where('Account_Username', session()->get('username'))->where('Account_Password', md5($request->input('oldPassword')))->first();

            if($query) {
                $query = Accounts::where('Account_Username', session()->get('username'))->update(array(
                    'Account_Password' => md5($request->input('newPassword'))
                ));

                if($query) {
                    session()->flash('global_status', 'Success');
                    session()->flash('global_message', 'Password has been changed.');
                } else {
                    session()->flash('global_status', 'Failed');
                    session()->flash('global_message', 'Failed to change password.');
                }
            } else {
                session()->flash('global_status', 'Failed');
                session()->flash('global_message', 'Invalid old password.');
            }
        } else {
            session()->flash('global_status', 'Failed');
            session()->flash('global_message', 'Oops! Password doesn\'t match.');
        }

        return redirect()->route('main.getChangePassword');
    }

    public function postCancelReservation(Request $request) {
        if(!session()->has('username')) {
            session()->flash('global_status', 'Failed');
            session()->flash('global_message', 'Oops! Please login first.');

            return redirect()->route('main.getLogin');
        }

        switch($request->input('arg0')) {
            case '2705a83a5a0659cce34583972637eda5':
                // arg0: ajax
                $query = Reservations::where('Reservation_ID', $request->input('arg1'))->update(array(
                    'Reservation_Status' => 'inactive'
                ));

                if($query) {
                    return json_encode(array('status' => 'Success', 'message' => 'Reservation has been cancelled.'));
                } else {
                    return json_encode(array('status' => 'Failed', 'message' => 'Failed to cancel reservation.'));
                }

                break;
            case 'a8affc088cbca89fa20dbd98c91362e4':
                // arg0: click
                $query = Reservations::where('Reservation_ID', $request->input('arg1'))->update(array(
                    'Reservation_Status' => 'inactive'
                ));

                if($query) {
                    session()->flash('global_status', 'Success');
                    session()->flash('global_message', 'Reservation has been cancelled.');
                } else {
                    session()->flash('global_status', 'Failed');
                    session()->flash('global_message', 'Failed to cancel reservation.');
                }

                return redirect()->route('main.getAccountInfo');

                break;
            default:
                break;
        }
    }

    public function postLogin(Request $request) {
        $account = Accounts::where('Account_Username', $request->input('username'))->where('Account_Password', md5($request->input('password')))->first();

        if($account) {
            if($account->Account_Type == 'Faculty') {
                $faculty = Faculties::where('Faculty_ID', $account->Account_Owner)->first();

                if($faculty) {
                    session()->put('username', $account->Account_Username);
                    session()->put('first_name', $faculty->Faculty_First_Name);
                    session()->put('middle_name', $faculty->Faculty_Middle_Name);
                    session()->put('last_name', $faculty->Faculty_Last_Name);
                    session()->put('account_type', $account->Account_Type);

                    return redirect()->route('main.getOpac');
                }
            } else if($account->Account_Type == 'Librarian') {
                $librarian = Librarians::where('Librarian_ID', $account->Account_Owner)->first();

                if($librarian) {
                    session()->put('username', $account->Account_Username);
                    session()->put('first_name', $librarian->Librarian_First_Name);
                    session()->put('middle_name', $librarian->Librarian_Middle_Name);
                    session()->put('last_name', $librarian->Librarian_Last_Name);
                    session()->put('account_type', $account->Account_Type);
                    session()->put('account_owner', $account->Account_Owner);

                    return redirect()->route('panel.getIndex');
                }
            } else if($account->Account_Type == 'Student') {
                $student = Students::where('Student_ID', $account->Account_Owner)->first();

                if($student) {
                    session()->put('username', $account->Account_Username);
                    session()->put('first_name', $student->Student_First_Name);
                    session()->put('middle_name', $student->Student_Middle_Name);
                    session()->put('last_name', $student->Student_Last_Name);
                    session()->put('account_type', $account->Account_Type);

                    return redirect()->route('main.getOpac');
                }
            }
        }

        session()->flash('global_status', 'Failed');
        session()->flash('global_message', 'Invalid username and/or password.');

        return redirect()->route('main.getLogin');
    }

    public function postSearchOpac(Request $request) {
        $this->checkConfigurationFile();

        $data['works_authors'] = Works::join('authors', 'works.Author_ID', '=', 'authors.Author_ID')->get();
        $data['works_materials'] = Works::where('materials.Material_Title', 'like', '%' . $request->input('searchKeyword') . '%')->orWhere('materials.Material_Call_Number', 'like', '%' . $request->input('searchKeyword') . '%')->orWhere('authors.Author_First_Name', 'like', '%' . $request->input('searchKeyword') . '%')->orWhere('authors.Author_Middle_Name', 'like', '%' . $request->input('searchKeyword') . '%')->orWhere('authors.Author_Last_Name', 'like', '%' . $request->input('searchKeyword') . '%')->orWhereRaw("CONCAT(authors.Author_First_Name, ' ', authors.Author_Last_Name) LIKE '%" . $request->input('searchKeyword') . "%'")->orWhereRaw("CONCAT(authors.Author_First_Name, ' ', authors.Author_Middle_Name, ' ', authors.Author_Last_Name) LIKE '%" . $request->input('searchKeyword') . "%'")->join('materials', 'works.Material_ID', '=', 'materials.Material_ID')->leftJoin('authors', 'works.Author_ID', '=', 'authors.Author_ID')->groupBy('works.Material_ID')->get();
        $data['materials_publishers'] = Materials::join('publishers', 'materials.Publisher_ID', '=', 'publishers.Publisher_ID')->get();
        $data['reservations'] = Reservations::where('Account_Username', session()->get('username'))->where('Reservation_Status', 'active')->get();
        $data['reserved_materials'] = Reservations::where('Reservation_Status', 'active')->get();
        $data['loaned_materials'] = Loans::where('Loan_Status', 'active')->get();
        $data['toggle_reservation'] = 'Hide';

        $configs = simplexml_load_file(storage_path('app') . '/configuration.xml');

        foreach($configs as $config) {
            if($config['name'] == 'reservation') {
                $data['toggle_reservation'] = (string) $config['value'];

                break;
            }
        }

        if(session()->has('username')) {
            $data['reservation_limit'] = $this->reservationLimit;
            $data['on_reserved'] = Reservations::where('Reservation_Status', 'active')->where('Account_Username', session()->get('username'))->count();
        }

        return json_encode(array('status' => 'Success', 'message' => 'Request Successful.', 'data' => $data));
    }
}
