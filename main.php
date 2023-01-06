<?php
class Main
{

    // Connection instance
    private $connection;

    // table name
    private $table_name = "";

    public function __construct($connection, $table_name)
    {
        $this->connection = $connection;
        $this->table_name = $table_name;
    }

    public function create($list)
    {
        $query = "INSERT INTO " . $this->table_name . " SET ";
        for ($i = 0; $i < count($list); $i++) {
            $item = $list[$i];
            $query = $query . "" . $item['label'] . " = :" . $item['label'];
            if ($i < (count($list) - 1)) {
                $query = $query . ", ";
            }
        };
        $stmt = $this->connection->prepare($query);
        for ($i = 0; $i < count($list); $i++) {
            $item = $list[$i];
            $stmt->bindParam('' . $item['label'], $item['value']);
        };
        return $stmt->execute() ? true : false;
    }

    public function read()
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE isActive = 1 ORDER BY id ASC";
        if ($this->table_name === 'sub_category') {
            $query = "SELECT 
            sub_category.id AS 'id', 
            sub_category.detail AS 'detail', 
            category.detail AS 'category_detail' 
            FROM sub_category, category 
            WHERE sub_category.categoryId = category.id 
            AND sub_category.isActive = 1 
            ORDER BY id ASC";
        }
        if ($this->table_name === 'stock') {
            $query = "SELECT 
            stock.id AS 'id', 
            stock.detail AS 'detail', 
            stock.amount AS 'amount', 
            sub_category.detail AS 'sub_category_detail', 
            category.detail AS 'category_detail' 
            FROM stock, sub_category, category 
            WHERE stock.subCategoryId = sub_category.id 
            AND sub_category.categoryId = category.id ORDER BY id ASC";
        }
        if ($this->table_name === 'orders') {
            $query = "SELECT 
            orders.id AS 'id', 
            orders.detail AS 'detail', 
            orders.state AS 'state', 
            stock.detail AS 'stock_detail', 
            worker.detail AS 'worker_detail', 
            orders.amount AS 'amount', 
            orders.expense AS 'expense', 
            stock.amount AS 'cost', 
            sub_category.detail AS 'sub_category_detail', 
            category.detail AS 'category_detail' 
            FROM orders, stock, sub_category, worker, category 
            WHERE orders.stockId = stock.id 
            AND orders.workerId = worker.id 
            AND stock.subCategoryId = sub_category.id 
            AND sub_category.categoryId = category.id ORDER BY id ASC";
        }
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function update($id, $list)
    {
        $query = "UPDATE " . $this->table_name . " SET ";
        for ($i = 0; $i < count($list); $i++) {
            $item = $list[$i];
            $query = $query . "" . $item['label'] . " = :" . $item['label'];
            if ($i < (count($list) - 1)) {
                $query = $query . ", ";
            }
        };
        $query = $query . " WHERE id = :id";
        $stmt = $this->connection->prepare($query);
        for ($i = 0; $i < count($list); $i++) {
            $item = $list[$i];
            $stmt->bindParam('' . $item['label'], $item['value']);
        };
        $stmt->bindParam("id", $id);
        return $stmt->execute() ? true : false;
    }

    public function delete($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = '" . $id . "'";
        $stmt = $this->connection->prepare($query);
        return $stmt->execute() ? true : false;
    }
}
