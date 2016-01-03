<?php namespace KEIII\ReactSilex;

use React\Http\Request as ReactRequest;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

/**
 * React request bridge.
 */
class ReactRequestBridge
{
    /**
     * @param ReactRequest $request
     * @return SymfonyRequest
     */
    public function convertRequest(ReactRequest $request)
    {
        return SymfonyRequest::create(
            $request->getPath(),
            $request->getMethod(),
            $request->getQuery(),
            $this->extractCookies($request),
            $request->getFiles(),
            $this->extractServer($request),
            $request->getBody()
        );
    }

    /**
     * Extract the request cookies ($_COOKIE).
     * @param ReactRequest $request
     * @return array
     */
    private function extractCookies(ReactRequest $request)
    {
        $headers = $request->getHeaders();
        $str = isset($headers['Cookie']) ? $headers['Cookie'] : '';

        return $this->parseCookies($str);
    }

    /**
     * Parse raw cookies string.
     * @param string $str
     * @return array
     */
    private function parseCookies($str)
    {
        $result = [];

        foreach (explode(';', $str) as $item) {
            $item = explode('=', trim($item));

            if (count($item) === 2) {
                $result[$item[0]] = $item[1];
            }
        }

        return $result;
    }

    /**
     * Extract the server parameters ($_SERVER).
     * @param ReactRequest $request
     * @return array
     */
    private function extractServer(ReactRequest $request)
    {
        $server = [
            'SERVER_PROTOCOL' => 'HTTP/'.$request->getHttpVersion(),
            'REQUEST_METHOD' => $request->getMethod(),
            'REQUEST_URI' => $request->getPath(),
            'REQUEST_TIME' => time(),
        ];

        // headers
        foreach ($request->getHeaders() as $key => $value) {
            $name = mb_strtoupper($key, 'UTF-8');
            $name = str_replace('-', '_', $name);
            $server[$name] = $value;
        }

        return $server;
    }
}
