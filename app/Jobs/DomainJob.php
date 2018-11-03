<?php

namespace App\Jobs;
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


class DomainJob extends Job
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $request;
    protected $id;

    public function __construct(array $request, $id)
    {
        $this->request = $request;
        $this->id = $id;
    }

    public function handle(Domain $domain, Client $client)
    {
        $domain = Domain::find($this->id);
      // $domain->name = $this->request['name'];

        $requestOption = array(
            'timeout' => 2.0, // Response timeout
            'connect_timeout' => 2.0, // Connection timeout
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 6.1; WOW64) 
                AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36',
            ]
        );

        try {
            $promise = $client->getAsync($domain->name, $requestOption, array('allow_redirects' => true));
            $response = $promise->wait();

            $domain->status_code = $response->getStatusCode();
            $original_body = $response->getBody();

            if ($original_body) {
                $type = $response->getHeader('content-type');
                if ($type) {
                    $parsed = \GuzzleHttp\Psr7\parse_header($type);
                    $domain->body = mb_convert_encoding($original_body, 'UTF-8', $parsed[0]['charset'] ?: 'UTF-8');
                } else {
                    $domain->body = (string)$original_body;
                }

                $document = new Document((string)$domain->body);

                $domain->h1 = implode("\n", array_map(function ($val) {
                    return $val->text();
                }, $document->find('h1')));

                $domain->keywords = implode("\n", array_map(function ($val) {
                    return $val->getAttribute('content');
                }, $document->find('meta[name="keywords"]')));

                $domain->description = implode("\n", array_map(function ($val) {
                    return $val->getAttribute('content');
                }, $document->find('meta[name="description"]')));
            }
            $contentLength = $response->getHeader('Content-Length');
            $domain->content_length = $contentLength ? $contentLength[0] : strlen($domain->body);
        } catch (RequestException $e) {
            if ($response = $e->getResponse()) {
                $domain->status_code = $response->getStatusCode();
                $domain->body = (string)$response->getBody();
                $domain->content_length = strlen($domain->body);
//                $request->session()->flash('error', 'ddd');
//                return redirect()->route('index');
            }
        } catch (\Exception $e) {
            $errors = $e->getMessage();
            $domain->status_code = "Error";
 //            $request->session()->flash('error', $errors);
 //            return redirect()->route('index');
        }

        $domain->save();
    }
}
