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
            $query = $query . "" . $item['create_label'] . " = :" . $item['create_label'];
            if ($i < (count($list) - 1)) {
                $query = $query . ", ";
            }
        };
        $stmt = $this->connection->prepare($query);
        for ($i = 0; $i < count($list); $i++) {
            $item = $list[$i];
            $stmt->bindParam('' . $item['create_label'], $item['create_value']);
        };
        return $stmt->execute() ? true : false;
    }

    public function create_with_return($list)
    {
        $query = "INSERT INTO " . $this->table_name . " SET ";
        for ($i = 0; $i < count($list); $i++) {
            $item = $list[$i];
            $query = $query . "" . $item['create_label'] . " = :" . $item['create_label'];
            if ($i < (count($list) - 1)) {
                $query = $query . ", ";
            }
        };
        $stmt = $this->connection->prepare($query);
        for ($i = 0; $i < count($list); $i++) {
            $item = $list[$i];
            $stmt->bindParam('' . $item['create_label'], $item['create_value']);
        };
        return $stmt;
    }

    public function create_multi($list)
    {
        $query = "INSERT INTO " . $this->table_name . " (";
        for ($i = 0; $i < count($list); $i++) {
            $parent = $list[$i];
            for ($i = 0; $i < count($parent); $i++) {
                $child = $parent[$i];
                $query = $query . "" . $child['create_label'];
                if ($i < (count($parent) - 1)) {
                    $query = $query . ", ";
                }
            };
        };
        $query = $query . ") VALUES ";
        for ($i = 0; $i < count($list); $i++) {
            $parent = $list[$i];
            $query = $query . "(";
            for ($j = 0; $j < count($parent); $j++) {
                $child = $parent[$j];
                $query = $query . "'" . $child['create_value'] . "'";
                if ($j < (count($parent) - 1)) {
                    $query = $query . ", ";
                }
            };
            $query = $query . ")";
            if ($i < (count($list) - 1)) {
                $query = $query . ", ";
            }
        };
        $stmt = $this->connection->prepare($query);
        return $stmt;
    }

    public function read()
    {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id ASC";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function read_last()
    {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC LIMIT 1";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function update($id, $list)
    {
        $query = "UPDATE " . $this->table_name . " SET ";
        for ($i = 0; $i < count($list); $i++) {
            $item = $list[$i];
            $query = $query . "" . $item['create_label'] . " = :" . $item['create_label'];
            if ($i < (count($list) - 1)) {
                $query = $query . ", ";
            }
        };
        $query = $query . " WHERE id = :id";
        $stmt = $this->connection->prepare($query);
        for ($i = 0; $i < count($list); $i++) {
            $item = $list[$i];
            $stmt->bindParam('' . $item['create_label'], $item['create_value']);
        };
        $stmt->bindParam("id", $id);
        return $stmt->execute() ? true : false;
    }

    public function update_with_return($id, $list)
    {
        $query = "UPDATE " . $this->table_name . " SET ";
        for ($i = 0; $i < count($list); $i++) {
            $item = $list[$i];
            $query = $query . "" . $item['create_label'] . " = :" . $item['create_label'];
            if ($i < (count($list) - 1)) {
                $query = $query . ", ";
            }
        };
        $query = $query . " WHERE id = :id";
        $stmt = $this->connection->prepare($query);
        for ($i = 0; $i < count($list); $i++) {
            $item = $list[$i];
            $stmt->bindParam('' . $item['create_label'], $item['create_value']);
        };
        $stmt->bindParam("id", $id);
        return $stmt;
    }

    public function update_multi($list)
    {
        $query = "INSERT INTO stock (id,quantity) VALUES ";
        for ($i = 0; $i < count($list); $i++) {
            $item = $list[$i];
            $quantity = $item->maxQuantity - $item->quantity;
            $query = $query . "(" . $item->id . "," . $quantity . ")";
            if ($i < (count($list) - 1)) {
                $query = $query . ", ";
            }
        };
        $query = $query . " ON DUPLICATE KEY UPDATE quantity=VALUES(quantity)";
        $stmt = $this->connection->prepare($query);
        return $stmt->execute() ? true : false;
    }

    public function delete($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = '" . $id . "'";
        $stmt = $this->connection->prepare($query);
        return $stmt->execute() ? true : false;
    }

    // public function delete_category($categoryId)
    // {
    //     $sub_cat_select_query = "SELECT id FROM sub_category WHERE categoryId = '" . $categoryId . "'";
    //     $sub_cat_select_stmt = $this->connection->prepare($sub_cat_select_query);
    //     $sub_cat_select_stmt->execute();
    //     $sub_cat_delete_query = "";
    //     $sub_sub_cat_delete_query = "";
    //     if ($sub_cat_select_stmt->rowCount() > 0) {
    //         $sub_cat_delete_query = "(";
    //         $sub_sub_cat_delete_query = "(";
    //         $sub_cat_index = 0;

    //         while ($row = $sub_cat_select_stmt->fetch(PDO::FETCH_ASSOC)) {
    //             extract($row);
    //             $sub_cat_index++;
    //             $sub_cat_delete_query = $sub_cat_delete_query . intval($id);
    //             if ($sub_cat_select_stmt->rowCount() > $sub_cat_index) {
    //                 $sub_cat_delete_query = $sub_cat_delete_query . ",";
    //             }
    //             $sub_sub_cat_select_query = "SELECT id FROM sub_sub_category WHERE subCategoryId = '" . intval($id) . "'";
    //             $sub_sub_cat_select_stmt = $this->connection->prepare($sub_sub_cat_select_query);
    //             $sub_sub_cat_select_stmt->execute();
    //             if ($sub_sub_cat_select_stmt->rowCount() > 0) {
    //                 $sub_sub_cat_index = 0;
    //                 while ($row = $sub_sub_cat_select_stmt->fetch(PDO::FETCH_ASSOC)) {
    //                     extract($row);
    //                     $sub_sub_cat_index++;
    //                     $sub_sub_cat_delete_query = $sub_sub_cat_delete_query . intval($id);
    //                     if ($sub_sub_cat_select_stmt->rowCount() > $sub_sub_cat_index) {
    //                         $sub_sub_cat_delete_query = $sub_sub_cat_delete_query . ",";
    //                     }
    //                 }
    //             }
    //         }
    //         $sub_cat_delete_query = "DELETE from sub_category WHERE id IN " . $sub_cat_delete_query . ")";
    //         $sub_cat_delete_stmt = $this->connection->prepare($sub_cat_delete_query);
    //         $sub_sub_cat_delete_query = "DELETE from sub_sub_category WHERE id IN " . $sub_sub_cat_delete_query . ")";
    //         $sub_sub_cat_delete_stmt = $this->connection->prepare($sub_sub_cat_delete_query);
    //         $sub_cat_delete_stmt->execute();
    //         $sub_sub_cat_delete_stmt->execute();
    //     }
    //     return $this->delete($categoryId);
    // }

    public function delete_category($categoryId)
    {
        $sub_cat_delete_query = "DELETE FROM sub_category WHERE categoryId = '" . $categoryId . "'";
        $sub_cat_delete_stmt = $this->connection->prepare($sub_cat_delete_query);
        $sub_cat_delete_stmt->execute();
        $sub_sub_cat_delete_query = "DELETE from sub_sub_category WHERE categoryId = '" . $categoryId . "'";
        $sub_sub_cat_delete_stmt = $this->connection->prepare($sub_sub_cat_delete_query);
        $sub_sub_cat_delete_stmt->execute();
        return $this->delete($categoryId);
    }

    public function delete_sub_category($subCategoryId)
    {
        $sub_sub_cat_delete_query = "DELETE from sub_sub_category WHERE subCategoryId = '" . $subCategoryId . "'";
        $sub_sub_cat_delete_stmt = $this->connection->prepare($sub_sub_cat_delete_query);
        $sub_sub_cat_delete_stmt->execute();
        return $this->delete($subCategoryId);
    }

    public function count()
    {
        $query = "SELECT COUNT(category.id) mycount FROM category WHERE category.isActive = 1";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function read_live($list)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE ";
        for ($i = 0; $i < count($list); $i++) {
            $item = $list[$i];
            $query = $query . "LOWER(" . $item['create_label'] . ") LIKE '%" . $item['create_value'] . "%' ";
            if ($i < (count($list) - 1)) {
                $query = $query . "OR ";
            }
        };
        $query = $query . " LIMIT 5";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function authorize($data)
    {
        $query = "SELECT * FROM users WHERE username = '" . $data->username . "' AND password = '" . $data->password . "'";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
