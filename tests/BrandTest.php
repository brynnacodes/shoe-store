<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once 'src/Brand.php';

    $server = 'mysql:host=localhost:8889;dbname=shoes_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class BrandTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
          Brand::deleteAll();
        }

        function test_getName()
        {
            //Arrange
            $name = 'Nike';
            $test_brand = new Brand($name);

            //Act
            $result = $test_brand->getName();

            //Assert
            $this->assertEquals($name, $result);
        }

        function test_getId()
        {
            //Arrange
            $name = 'Nike';
            $id = 3;
            $test_brand = new Brand($name, $id);

            //Act
            $result = $test_brand->getId();

            //Assert
            $this->assertEquals(3, $result);
        }

        function test_save()
        {
            //Arrange
            $name = 'Nike';
            $test_brand = new Brand($name);

            //Act
            $test_brand->save();
            $result = Brand::getAll();

            //Assert
            $this->assertEquals($test_brand, $result[0]);
        }

        function test_getAll()
        {
            //Arrange
            $name = 'Nike';
            $test_brand = new Brand($name);
            $test_brand->save();

            $name2 = 'Adidas';
            $test_brand2 = new Brand($name);
            $test_brand2->save();

            //Act
            $result = Brand::getAll();

            //Assert
            $this->assertEquals([$test_brand, $test_brand2], $result);
        }

        function test_find()
        {
            //Arrange
            $name = 'Nike';
            $test_brand = new Brand($name);
            $test_brand->save();

            $name2 = 'Adidas';
            $test_brand2 = new Brand($name);
            $test_brand2->save();

            //Act
            $result = Brand::find($test_brand->getId());

            //Assert
            $this->assertEquals($test_brand, $result);
        }

        function test_update()
        {
            //Arrange
            $name = 'Nike';
            $test_brand = new Brand($name);
            $test_brand->save();

            $new_name = 'Doc Martin';

            //Act
            $test_brand->update($new_name);
            $result = Brand::getAll();

            //Assert
            $this->assertEquals($new_name, $result[0]->getName());
        }

        function test_delete()
        {
            //Arrange
            $name = 'Nike';
            $test_brand = new Brand($name);
            $test_brand->save();

            $name2 = 'Adidas';
            $test_brand2 = new Brand($name);
            $test_brand2->save();

            //Act
            $test_brand->delete();
            $result = Brand::getAll();

            //Assert
            $this->assertEquals([$test_brand2], $result);
        }

        function test_getStores()
        {
            //Arrange
            $name = 'Shoes Shoes Shoes';
            $test_store = new Store($name);
            $test_store->save();

            $name2 = 'Shoes R Us';
            $test_store2 = new Store($name2);
            $test_store2->save();

            $name3 = 'Adidas';
            $test_brand = new Brand($name3);
            $test_brand->save();

            //Act
            $test_brand->addStore($test_store);
            $test_brand->addStore($test_store2);
            $result = $test_brand->getStores();
            //Assert
            $this->assertEquals([$test_store, $test_store2], $result);
        }
    }
?>
