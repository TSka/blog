<?php

namespace App\Repository;

use Framework\Container\ContainerInterface;

class ArticleRepository
{
    /**
     * @var \PDO
     */
    public $dbh;

    public function __construct(\PDO $dbh)
    {
        $this->dbh = $dbh;
    }

    public function all(): array
    {
        return $this->dbh->query('SELECT * FROM articles')->fetchAll();
    }

    public function add(array $data): array
    {
        $data['created'] = (new \DateTime('now'))->format('Y-m-d H:i:s');
        $this->dbh->prepare('INSERT INTO articles (title, text, created, image) VALUES (:title, :text, :created, :image)')->execute($data);

        return $this->findById($this->dbh->lastInsertId());
    }

    public function updateById($id, array $data): bool
    {
        unset($data['id']);
        $fields = [];
        foreach (array_keys($data) as $key) {
            $fields[] = sprintf('%s=:%s', $key, $key);
        }
var_dump($id, $data);die;
        $stmt = $this->dbh->prepare('UPDATE articles SET '.implode(',', $fields).' WHERE id = :id');
        $stmt->execute($data + ['id' => $id]);

        if (!$stmt->rowCount()) {
            throw new ArticleNotFoundException();
        }

        return true;
    }

    public function findById($id): array
    {
        $stmt = $this->dbh->prepare('SELECT * FROM articles WHERE id = :id');
        $stmt->execute(['id' => $id]);

        if (!$stmt) {
            throw new ArticleNotFoundException();
        }

        return $stmt->fetch();
    }

    public function deleteById($id): bool
    {
        $stmt = $this->dbh->prepare('DELETE FROM articles WHERE id = :id');
        $stmt->execute(['id' => $id]);

        return $stmt->rowCount();
    }
}