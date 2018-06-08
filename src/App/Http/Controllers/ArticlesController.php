<?php

namespace App\Http\Controllers;

use App\Repository\ArticleRepository;
use Framework\Container\Container;
use Framework\Http\JsonResponse;
use Framework\Http\Request;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ArticlesController
{
    /** @var ArticleRepository */
    protected $articles;

    public function __construct()
    {
        $this->articles = Container::getInstance()->get(ArticleRepository::class);
    }

    public function list(): ResponseInterface
    {
        return new JsonResponse([
            'articles' => $this->articles->all(),
        ]);
    }

    public function show(ServerRequestInterface $request): ResponseInterface
    {
        return new JsonResponse([
            'article' => $this->articles->findById($request->getAttribute('id')),
        ]);
    }

    public function store(ServerRequestInterface $request): ResponseInterface
    {
        return new JsonResponse([
            'article' => $this->articles->add($request->getParsedBody()),
        ]);
    }

    public function update(ServerRequestInterface $request): ResponseInterface
    {
        return new JsonResponse($request->getParsedBody());
        $this->articles->updateById($request->getAttribute('id'), $request->getParsedBody());

        return new JsonResponse([
            'article' => $this->articles->findById($request->getAttribute('id')),
        ]);
    }

    public function delete(ServerRequestInterface $request): ResponseInterface
    {
        return new JsonResponse([
            'result' => $this->articles->deleteById($request->getAttribute('id')),
        ]);
    }
}