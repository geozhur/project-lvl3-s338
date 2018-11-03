<?php
namespace App\Http\Controllers;
use App\Domain;
use Illuminate\Validation;
use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Session;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use DiDom\Document;
use DiDom\Query;
use App\Jobs\DomainJob;

class DomainsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function index()
    {
        $domains = DB::table('domains')->orderBy('id', 'desc')->paginate();

        return view('domain.index', ['domains' => $domains]);
    }

    public function show($id)
    {
        try {
            $domain = Domain::findOrFail($id);
        } catch (ModelNotFoundException $e) {
        }
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
        $domain->name = $request['name'];
        $domain->body = "loading...";
        $domain->content_length = "loading...";
        $domain->h1 = "loading...";
        $domain->keywords = "loading...";
        $domain->description = "loading...";
        $domain->status_code = "loading...";
        $domain->save();
        dispatch(new DomainJob($request->all(), $domain->id));

        return redirect()->route('domains.show', ['id' => $domain->id]);
    }
}
