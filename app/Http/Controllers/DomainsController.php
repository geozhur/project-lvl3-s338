<?php
namespace App\Http\Controllers;
use App\Domains;
use Illuminate\Validation;
use Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
class DomainsController extends Controller
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

    public function show($id)
    {
        $domain = Domains::find($id);
        if (!$domain) {
            return redirect()->route('index');
        }
        return view('domains', ['domain' => $domain]);
    }


    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/'
        ];

        try {
            $this->validate($request, $rules);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->getMessage();
            return redirect()->route('index');
        }

        $domain = new Domains();
        $domain->name = $request->name;
        $domain->save();

        return redirect()->route('domains.show', ['id' => $domain->id]);
    }
}
