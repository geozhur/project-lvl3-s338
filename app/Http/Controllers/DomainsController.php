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
        $domains = DB::table('domains')->orderBy('id', 'desc')->paginate();

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

        $client = new Client();
        $domain = new Domain();

        $requestOption = array(
            'timeout' => 2.0, // Response timeout
            'connect_timeout' => 2.0, // Connection timeout
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 6.1; WOW64) 
                AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36',
            ]
        );

        try {
            $promise = $client->getAsync($request->name, $requestOption, array('allow_redirects' => true));
            $response = $promise->wait();
    
            $domain->name = $request->name;
            $domain->status_code = $response->getStatusCode();
            $body = $response->getBody();
            $domain->body = $body ? $body : "";
            $contentLength = $response->getHeader('Content-Length');
            $domain->content_length = $contentLength ? $contentLength[0] : strlen($domain->body);
            $domain->body = utf8_encode($domain->body);
        } catch (RequestException $e) {
            if ($response = $e->getResponse()) {
                $domain->name = $request->name;
                $domain->status_code = $response->getStatusCode();
                $domain->body = utf8_encode($response->getBody());
                $domain->content_length = strlen($domain->body);
            }
        } catch (\Exception $e) {
            $errors = $e->getMessage();
            $request->session()->flash('error', $errors);
            return redirect()->route('index');
        }

        $domain->save();
        return redirect()->route('domains.show', ['id' => $domain->id]);
    }
}
