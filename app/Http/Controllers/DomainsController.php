<?php
namespace App\Http\Controllers;
use App\Domain;
use Illuminate\Validation;
use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Session;
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

    public function index()
    {
        $domains = DB::table('domains')->paginate(20);

        return view('domain.index', ['domains' => $domains]);
    }

    public function show($id)
    {
        $domain = Domain::findOrFail($id);
        return view('domain.show', ['domain' => $domain]);
    }


    public function store(Request $request)
    {
        $input = $request->all();

        if (!preg_match("~^https?://~i", $input[ 'name' ])) {
            $input[ 'name' ] = "http://" . $input[ 'name' ];
        }

        $request->merge(['name' => $input[ 'name' ]  ]);

        $rules = [
            'name' => 'required|active_url'
        ];

        try {
            $this->validate($request, $rules);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->getMessage();
            $request->session()->flash('error', $errors);
            return redirect()->route('index');
        }

        $domain = new Domain();
        $domain->name = $request->name;
        $domain->save();

        return redirect()->route('domains.show', ['id' => $domain->id]);
    }
}
