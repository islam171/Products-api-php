<?php

    use app\Services\Router;
    use app\Controllers\Auth;
    use app\Controllers\Products;
    use app\Controllers\Company;
    use app\Controllers\Category;
    use app\Controllers\Cart;

//    Auth
    Router::post('/api/v1/SignUp', Auth::class, 'register', true, false);
    Router::post('/api/v1/SignIn', Auth::class, 'login', true, false);
    Router::post('/api/v1/SignOut', Auth::class, 'logout', false, false);
    Router::get('/api/v1/profile', Auth::class, 'getProfile');
//    Products
    Router::get('/api/v1/products', Products::class, 'getAll', true);
    Router::get('/api/v1/product/id', Products::class, 'getId',  true);
    Router::get('/api/v1/product/catId', Products::class, 'getCatId',  true);
    Router::get('/api/v1/product/companyId', Products::class, 'getCompanyId',  true);
    Router::get('/api/v1/product/author', Products::class, 'getProductsByUser',  false);
    Router::delete('/api/v1/product/id', Products::class, 'deleteId',  true);
    Router::post('/api/v1/product', Products::class, 'addProduct',  true);
    Router::post('/api/v1/productup', Products::class, 'updateProduct', true );

//    Company
    Router::get('/api/v1/companies', Company::class, 'getAll');
    Router::get('/api/v1/company/id', Company::class, 'getId', true);
    Router::post('/api/v1/company', Company::class, 'create', true);
    Router::delete('/api/v1/company/id', Company::class, 'delete', true);
    Router::post('/api/v1/companyUp', Company::class, 'updateCompanyName', true);
    Router::get('/api/v1/companyByUser', Company::class, 'CompanyByUser');

//    Category
    Router::post('/api/v1/category', Category::class, 'create', true);
    Router::get('/api/v1/categories', Category::class, 'getAll');
    Router::get('/api/v1/category/id', Category::class, 'getById', true);
    Router::delete('/api/v1/category/id', Category::class, 'delete', true);
    Router::update('/api/v1/categoryUp', Category::class, 'update', true);

    //    Cart
    Router::get('/api/v1/carts', Cart::class, 'getAll', true);
    Router::get('/api/v1/cart/id', Cart::class, 'getId',  true);
    Router::get('/api/v1/cart/catId', Cart::class, 'getCatId',  true);
    Router::get('/api/v1/cart/user', Cart::class, 'getUserId',  true);
    Router::get('/api/v1/cart/user/products', Cart::class, 'getUserIdProducts',  true);
    Router::delete('/api/v1/cart/id', Cart::class, 'deleteId',  true);
    Router::delete('/api/v1/cart/productsId', Cart::class, 'removeId',  true);
    Router::delete('/api/v1/cart/clear', Cart::class, 'clear',  true);
    Router::post('/api/v1/cart', Cart::class, 'create',  true);
    Router::post('/api/v1/cartup', Cart::class, 'update', true );

    Router::enable();