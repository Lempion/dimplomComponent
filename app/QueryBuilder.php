<?php

namespace App;

use Aura\SqlQuery\QueryFactory;
use PDO;

class QueryBuilder
{
    private $db, $queryFactory;

    public function __construct(PDO $pdo, QueryFactory $queryFactory)
    {
        $this->db = $pdo;
        $this->queryFactory = $queryFactory;
    }

    public function getAll($table, $cols = ['*'], $orderBy = 'DESC')
    {
        $select = $this->queryFactory->newSelect();

        $select->cols($cols)
            ->from($table)
            ->orderBy(["id {$orderBy}"]);

        $sth = $this->db->prepare($select->getStatement());

        $sth->execute($select->getBindValues());

        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOne($table, $id, $cols = ['*'])
    {
        $select = $this->queryFactory->newSelect();

        $select->cols($cols)
            ->from($table)
            ->where('id =:id')
            ->bindValue('id', $id);

        $sth = $this->db->prepare($select->getStatement());

        $sth->execute($select->getBindValues());

        return $sth->fetch(PDO::FETCH_ASSOC);
    }

    public function update($table, $data, $id)
    {
        $update = $this->queryFactory->newUpdate();

        $update
            ->table($table)
            ->cols($data)
            ->where('id = :id')
            ->bindValue('id', $id);

        $sth = $this->db->prepare($update->getStatement());

        return $sth->execute($update->getBindValues());
    }


}