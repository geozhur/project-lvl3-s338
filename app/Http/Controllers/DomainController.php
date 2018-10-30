<?php
namespace App\Http\Controllers;
use App\Domain;
use Illuminate\Validation;
use Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
class DomainController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function getDomain($id)
    {
        $domain = Domain::find($id);
        if(!$domain) {
            return redirect('/');
        }
        return view('domain', ['domain' => $domain]);
    }


    public function insertDomain( Request $request )
    {
        $rules = [
            'name' => 'required|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/'
        ];

        $validator = Validator::make($request->post(), $rules);


        try {
            $this->validate($request, $rules);
        }
        catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->getMessage();
            return redirect("/");
        }

        $domain = new Domain;
        $domain->name = $request->name;
        $domain->save();

        return redirect("/domain/{$domain->id}");
    }

    //
}
