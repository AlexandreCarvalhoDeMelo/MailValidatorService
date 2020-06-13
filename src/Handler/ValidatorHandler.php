<?php

declare(strict_types=1);

namespace MailValidator\Handler;

use MailValidator\Validator\Service\Validator as ValidatorMailService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Response;

/**
 * Class ValidatorHandler
 * @package MailValidator\Handler
 */
class ValidatorHandler implements RequestHandlerInterface
{
    const MESSAGE_INVALID_REQUEST_KEY = "Field 'email' not found.";

    /**
     * @var ValidatorMailService $service
     */
    private $service;

    /**
     * Using auto-wiring
     * ValidatorHandler constructor.
     * @param ValidatorMailService $validator
     */
    public function __construct(ValidatorMailService $validator)
    {
        $this->service = $validator;
    }

    /**
     * Main "controller" method
     * Two lines without alot of black magic
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $request = json_decode($request->getBody()->getContents()) ?? null;

        return isset($request->email) ?
            $this->respond(200, $this->service->processAndGetResult($request->email)) :
            $this->respond(400, self::MESSAGE_INVALID_REQUEST_KEY);
    }

    /**
     * @param $code
     * @param $body
     * @return Response
     */
    private function respond(int $code, $body): ResponseInterface
    {
        $body = is_string($body) ?
            json_encode(['Error' => $body], JSON_PRETTY_PRINT) :
            json_encode($body, JSON_PRETTY_PRINT);

        return (new ResponseFactory())
            ->createResponse($code)
            ->withBody(
                (new StreamFactory())
                ->createStream($body)
            );
    }
}
