<?php
    date_default_timezone_set("America/Los_Angeles");
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Store.php";
    require_once __DIR__."/../src/Brand.php";

    $app = new Silex\Application();

    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();
    $app['debug'] = true;

    $server = 'mysql:host=localhost:8889;dbname=shoes';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    $app->register(new Silex\Provider\TwigServiceProvider(), ["twig.path" => __DIR__."/../views"]);

    $app->get('/', function() use($app) {
        $brands = Brand::getAll();
        $stores = Store::getAll();
        return $app["twig"]->render("root.html.twig", ['stores' => $stores, 'brands' => $brands]);
    });

    $app->post('/add_brand', function() use($app) {
        $new_brand = new Brand($_POST['name']);
        $new_brand->save();
        return $app->redirect("/");
    });

    $app->post('/add_store', function() use($app) {
        $new_store = new Store($_POST['name']);
        $new_store->save();
        return $app->redirect("/");
    });

    $app->get("/brands/{id}", function($id) use($app) {
        $brand = Brand::find($id);
        $stores = Store::getAll();
        $brand_stores = $brand->getStores();
        return $app['twig']->render("brand.html.twig", ['brand' => $brand, 'stores' => $stores, 'brand_stores' => $brand_stores]);
    });

    $app->post("/brands/{id}/edit", function($id) use($app) {
        $brand = Brand::find($id);
        $stores = Store::getAll();

        $store_id = $_POST['store'];
        $found_store = Store::find($store_id);
        $add_store = $brand->addStore($found_store);
        $brand_stores = $brand->getStores();
        return $app->redirect("/brands/".$id);
    });

    $app->get("/stores/{id}", function($id) use($app) {
        $store = Store::find($id);
        $brands = Brand::getAll();
        $store_brands = $store->getBrands();
        return $app['twig']->render("store.html.twig", ['store' => $store, 'brands' => $brands, 'store_brands' => $store_brands]);
    });

    $app->post("/stores/{id}/edit", function($id) use($app) {
        $store = Store::find($id);
        $brands = Brand::getAll();

        $brand_id = $_POST['brand'];
        $found_brand = Brand::find($brand_id);
        $add_brand = $store->addBrand($found_brand);
        $store_brands = $store->getBrands();
        return $app->redirect("/stores/".$id);
    });

    $app->post("/drop_brand/{id}", function($id) use($app) {
        $brand_id = $_POST['brand'];
        $found_brand = Brand::find($brand_id);
        $store = Store::find($id);

        $store->dropBrand();
        return $app->redirect("/stores/".$id);
    });

    $app->post("/drop_store/{id}", function($id) use($app) {
        $store_id = $_POST['store'];
        $found_store = Store::find($store_id);
        $brand = Brand::find($id);

        $brand->dropStore();
        return $app->redirect("/brands/".$id);
    });

    return $app;
?>
