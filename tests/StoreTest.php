<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once 'src/Store.php';

    $server = 'mysql:host=localhost:8889;dbname=shoes_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class SourceTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
          Store::deleteAll();
        }

        function test_getName()
        {
            //Arrange
            $name = 'Shoes Shoes Shoes';
            $test_store = new Store($name);

            //Act
            $result = $test_store->getName();

            //Assert
            $this->assertEquals($name, $result);
        }

        function test_getId()
        {
            //Arrange
            $name = 'Shoes Shoes Shoes';
            $id = 3;
            $test_store = new Store($name, $id);

            //Act
            $result = $test_store->getId();

            //Assert
            $this->assertEquals(3, $result);
        }

        function test_save()
        {
            //Arrange
            $name = 'Shoes Shoes Shoes';
            $test_store = new Store($name);

            //Act
            $test_store->save();
            $result = Store::getAll();

            //Assert
            $this->assertEquals($test_store, $result[0]);
        }

        function test_getAll()
        {
            //Arrange
            $name = 'Shoes Shoes Shoes';
            $test_store = new Store($name);
            $test_store->save();

            $name2 = 'Shoes R Us';
            $test_store2 = new Store($name);
            $test_store2->save();

            //Act
            $result = Store::getAll();

            //Assert
            $this->assertEquals([$test_store, $test_store2], $result);
        }

        function test_find()
        {
            //Arrange
            $name = 'Shoes Shoes Shoes';
            $test_store = new Store($name);
            $test_store->save();

            $name2 = 'Shoes R Us';
            $test_store2 = new Store($name);
            $test_store2->save();

            //Act
            $result = Store::find($test_store->getId());

            //Assert
            $this->assertEquals($test_store, $result);
        }

        function test_update()
        {
            //Arrange
            $name = 'Shoes R Us';
            $test_store = new Store($name);
            $test_store->save();

            $new_name = 'Shoe Emporium';

            //Act
            $test_store->update($new_name);
            $result = Store::getAll();

            //Assert
            $this->assertEquals($new_name, $result[0]->getName());
        }
    }



?>
