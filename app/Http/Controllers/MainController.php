<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Accounts;
use App\Authors;
use App\Faculties;
use App\Librarians;
use App\Students;
use App\Works;

class MainController extends Controller
{
    public function getIndex() {
        return view('main.index');
    }

    public function getLogin() {
        return view('main.login');
    }

    public function getOpac() {
        $data['works_authors'] = Works::join('authors', 'works.Author_ID', '=', 'authors.Author_ID')->get();
        $data['works_materials'] = Works::join('materials', 'works.Material_ID', '=', 'materials.Material_ID')->groupBy('works.Material_ID')->get();

        return view('main.opac', $data);
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

        return redirect()->route('main.getIndex');
    }

    public function getLogout() {
        session()->flush();

        return redirect()->route('main.getIndex');
    }
}
