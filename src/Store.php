<?php
    class Store {
        private $name;
        private $id;

    function construct($name, $id)
    {
        $this->name = $name;
        $this->id = $id;
    }

    function setName($new_name)
    {
        $$this->name = $new_name;
    }

    function getName()
    {
        return $this->name;
    }

    function getId()
    {
        return $this->id;
    }
}

?>
