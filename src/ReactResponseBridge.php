<?php namespace KEIII\ReactSilex;

use React\Http\Response as ReactResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

/**
 * React response bridge.
 */
class ReactResponseBridge
{
    /**
     * Send symfony response.
     * @param ReactResponse $response
     * @param SymfonyResponse $sf_response
     */
    public function send(ReactResponse $response, SymfonyResponse $sf_response)
    {
        $this->sendHeaders($response, $sf_response);
        $this->sendContent($response, $sf_response);
    }

    /**
     * Sends HTTP headers.
     * @param ReactResponse $response
     * @param SymfonyResponse $sf_response
     */
    private function sendHeaders(ReactResponse $response, SymfonyResponse $sf_response)
    {
        // date
        if (!$sf_response->headers->has('Date')) {
            $sf_response->setDate(\DateTime::createFromFormat('U', time()));
        }

        $headers = $sf_response->headers->allPreserveCase();

        // cookies
        foreach ($sf_response->headers->getCookies() as $cookie) {
            if (!isset($headers['Set-Cookie'])) {
                $headers['Set-Cookie'] = array();
            }

            $headers['Set-Cookie'][] = (string)$cookie;
        }

        $response->writeHead($sf_response->getStatusCode(), $headers);
    }

    /**
     * Sends content for the current web response.
     * @param ReactResponse $response
     * @param SymfonyResponse $sf_response
     */
    private function sendContent(ReactResponse $response, SymfonyResponse $sf_response)
    {
        $response->end($sf_response->getContent());
    }
}
