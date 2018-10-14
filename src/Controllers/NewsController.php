<?php

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use Psr\Container\ContainerInterface;

class NewsController 
{
    protected $container;

    // constructor receives container instance
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function list(Request $request, Response $response)
    {
        $this->container->logger->info("Slim-Skeleton GET '/news' route");

        // get data news
        $pdo = $this->container->db;
        $sql = 'SELECT * FROM news';
        $sth = $pdo->query($sql);
        
        $records = $sth->fetchAll();

        if (empty($records)) {
            return $response->withStatus(404);
        }

        // reformat data for good response
        $baseUrl = ($request->getUri())->getBaseUrl();

        $news = array_map(function($item) use ($baseUrl) {
            $links = [
                'rel' => 'self',
                'href' => $baseUrl .'/news/'. $item['id']
            ];
            $item['links'] = $links;

            return $item;
        }, $records);
        
        $data = [
            'data' => $news
        ];

        $response = $response->withJson($data);

        // caching
        $resWithExpires = $this->container->cache->withExpires($response, time() + 3600);

        return $resWithExpires;
    }

    public function detail(Request $request, Response $response, array $args)
    {
        $this->container->logger->info("Slim-Skeleton GET '/news/" .$args['id']. "' route");

        // get data news by id
        $pdo = $this->container->db;
        $sql = 'SELECT * FROM news WHERE id=:id';
        $sth = $pdo->prepare($sql);
        $sth->bindParam(":id", $args['id']);
        $sth->execute();

        $news = $sth->fetch();

        if (empty($news)) {
            return $response->withStatus(404);
        }
        
        $response = $response->withJson($news);
        
        // caching
        $resWithExpires = $this->container->cache->withExpires($response, time() + 3600);

        return $resWithExpires;
    }

    public function add(Request $request, Response $response)
    {
        $this->container->logger->info("Slim-Skeleton POST '/news' route");

        $parsedBody = $request->getParsedBody();

        // validate
        $messages = [];
        if (empty($parsedBody) || (empty($parsedBody['title']) && empty($parsedBody['content']))) {
            $messages = [
                'messages' => [
                    'param title required',
                    'param content required',
                ]
            ];
            return $response->withJson($messages)->withStatus(400);
        }

        if (empty($parsedBody['title'])) {
            $messages = [
                'messages' => [
                    'param title required',
                ]
            ];
            return $response->withJson($messages)->withStatus(400);
        }

        if (empty($parsedBody['content'])) {
            $messages = [
                'messages' => [
                    'param content required',
                ]
            ];
            return $response->withJson($messages)->withStatus(400);
        }

        $news_data = [];
        $news_data['title'] = filter_var($parsedBody['title'], FILTER_SANITIZE_STRING);
        $news_data['content'] = filter_var($parsedBody['content'], FILTER_SANITIZE_STRING);
        $news_data['author'] = 'admin';

        // insert new data news
        $pdo = $this->container->db;
        $sql = 'INSERT INTO news (title, content, author, created_at) VALUES (:title, :content, :author, CURRENT_TIMESTAMP)';
        $sth = $pdo->prepare($sql);
        $sth->bindParam(":title", $news_data['title']);
        $sth->bindParam(":content", $news_data['content']);
        $sth->bindParam(":author", $news_data['author']);
        $sth->execute();
        
        $lastId = $pdo->lastInsertId();
        $detailUrl = ($request->getUri())->getBaseUrl() .'/news/'. $lastId;

        return $response->withAddedHeader('Location', $detailUrl)->withStatus(201);
    }

    public function edit(Request $request, Response $response, array $args)
    {
        $this->container->logger->info("Slim-Skeleton PUT '/news/" .$args['id']. "' route");

        $parsedBody = $request->getParsedBody();

        // validate
        $messages = [];
        if (empty($parsedBody) || (empty($parsedBody['title']) && empty($parsedBody['content']))) {
            $messages = [
                'messages' => [
                    'param title required',
                    'param content required',
                ]
            ];
            return $response->withJson($messages)->withStatus(400);
        }

        if (empty($parsedBody['title'])) {
            $messages = [
                'messages' => [
                    'param title required',
                ]
            ];
            return $response->withJson($messages)->withStatus(400);
        }

        if (empty($parsedBody['content'])) {
            $messages = [
                'messages' => [
                    'param content required',
                ]
            ];
            return $response->withJson($messages)->withStatus(400);
        }

        $news_data = [];
        $news_data['id'] = $args['id'];
        $news_data['title'] = filter_var($parsedBody['title'], FILTER_SANITIZE_STRING);
        $news_data['content'] = filter_var($parsedBody['content'], FILTER_SANITIZE_STRING);
        $news_data['author'] = 'admin';
        $news_data['updated_at'] = date('Y-m-d H:i:s');

        // query operation
        $pdo = $this->container->db;
        
        // check data exist or not
        $sql = 'SELECT * FROM news WHERE id=:id';
        $sth = $pdo->prepare($sql);
        $sth->bindParam(":id", $news_data['id']);
        $sth->execute();

        $news = $sth->fetch();

        if (empty($news)) {
            return $response->withStatus(404);
        }

        // update data news
        $sql = 'UPDATE news SET title=:title, content=:content, author=:author, updated_at=:updated_at WHERE id=:id';
        $sth = $pdo->prepare($sql);
        $sth->bindParam(":title", $news_data['title']);
        $sth->bindParam(":content", $news_data['content']);
        $sth->bindParam(":author", $news_data['author']);
        $sth->bindParam(":updated_at", $news_data['updated_at']);
        $sth->bindParam(":id", $news_data['id']);
        $sth->execute();

        return $response->withStatus(200);
    }

    public function delete(Request $request, Response $response, array $args)
    {
        $this->container->logger->info("Slim-Skeleton DELETE '/news/" .$args['id']. "' route");

        $news_data = [];
        $news_data['id'] = $args['id'];
        $news_data['deleted_at'] = date('Y-m-d H:i:s');

        // query operation
        $pdo = $this->container->db;

        // check data exist or not
        $sql = 'SELECT * FROM news WHERE id=:id';
        $sth = $pdo->prepare($sql);
        $sth->bindParam(":id", $news_data['id']);
        $sth->execute();

        $news = $sth->fetch();

        if (empty($news)) {
            return $response->withStatus(404);
        }

        // delete data news
        $pdo = $this->container->db;
        $sql = 'UPDATE news SET deleted_at=:deleted_at WHERE id=:id';
        $sth = $pdo->prepare($sql);
        $sth->bindParam(":deleted_at", $news_data['deleted_at']);
        $sth->bindParam(":id", $news_data['id']);
        $sth->execute();

        return $response->withStatus(200);
    }
}

