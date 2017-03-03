<?php
    class Brand
    {
        private $name;
        private $id;

    function __construct($name, $id = null)
    {
        $this->name = $name;
        $this->id = $id;
    }

    function setName($new_name)
    {
        $this->name = $new_name;
    }

    function getName()
    {
        return $this->name;
    }

    function getId()
    {
        return $this->id;
    }

    function save()
    {
        $exec = $GLOBALS['DB']->prepare("INSERT INTO brands (name) VALUES (:name);");
        $exec->execute([':name' => $this->getName()]);
        $this->id = $GLOBALS['DB']->lastInsertId();
    }

    function update($new_name)
    {
        $exec = $GLOBALS['DB']->prepare("UPDATE brands SET name = :name WHERE id = :id;");
        $exec->execute([':name' => $new_name, ':id' => $this->getId()]);
        $this->setName($new_name);
    }

    function delete()
    {
        $GLOBALS['DB']->exec("DELETE FROM brands WHERE id = {$this->getId()};");
        $GLOBALS['DB']->exec("DELETE FROM brands_stores WHERE brand_id = {$this->getId()};");
    }

    static function getAll()
    {
        $returned_brands = $GLOBALS['DB']->query("SELECT * FROM brands;");
        $brands = [];
        foreach ($returned_brands as $brand) {
            $name = $brand['name'];
            $id = $brand['id'];
            $new_brand = new Brand($name, $id);
            array_push($brands, $new_brand);
        }
        return $brands;
    }

    static function deleteAll()
    {
        $GLOBALS['DB']->exec("DELETE FROM brands");
    }

    static function find($id)
    {
        $found_brand;
        $brands = Brand::getAll();
        foreach ($brands as $brand) {
            if ($brand->getId() ==$id) {
                $found_brand = $brand;
            }
        }
        return $found_brand;
    }
}

?>
