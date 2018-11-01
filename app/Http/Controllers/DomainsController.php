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

        $client = $this->client;
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
            
            $original_body = $response->getBody();
            if ($original_body) {
            // pgsql bug fix
                $type = $response->getHeader('content-type');
                if ($type) {
                    $parsed = \GuzzleHttp\Psr7\parse_header($type);
                    $domain->body = mb_convert_encoding($original_body, 'UTF-8', $parsed[0]['charset'] ?: 'UTF-8');
                } else {
                    $domain->body = (string)$original_body;
                }
            } else {
                $domain->body = "";
            }

            $domain->h1 = "";
            $domain->keywords = "";
            $domain->description = "";

            if ($original_body) {
                $document = new Document((string)$domain->body);
                $h1s = $document->find('h1');
                foreach ($h1s as $h1) {
                    $domain->h1 .= $h1->text() . "\n";
                }

                $keywords = $document->find('meta[name="keywords"]'); // <meta name="keywords" content="...">
                foreach ($keywords as $keyword) {
                    $domain->keywords .= $keyword->getAttribute('content') . "\n";
                }

                $descriptions = $document->find('meta[name="description"]'); // <meta name="description" content="...">
                foreach ($descriptions as $description) {
                    $domain->description .= $description->getAttribute('content') . "\n";
                }
            }
            $contentLength = $response->getHeader('Content-Length');
            $domain->content_length = $contentLength ? $contentLength[0] : strlen($domain->body);
        } catch (RequestException $e) {
            if ($response = $e->getResponse()) {
                $domain->name = $request->name;
                $domain->status_code = $response->getStatusCode();
                $domain->body = (string)$response->getBody();
                $domain->content_length = strlen($domain->body);
                $domain->h1 = "";
                $domain->keywords = "";
                $domain->description = "";
            } else {
                $domain->name = $request->name;
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
