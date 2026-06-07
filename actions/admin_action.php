<?php

session_start();

if (
    !isset($_SESSION['user_id'])
    ||
    $_SESSION['user_role'] !== 'admin'
){
    header('Location: ../View/index.php');
    exit;
}

$type =
    $_GET['type'] ?? '';

$action =
    $_GET['action'] ?? '';

$id =
    (int)($_GET['id'] ?? 0);




if($type === 'user'){

    require_once __DIR__ . '/../controllers/UserController.php';

    $controller =
        new UserController();

    if($action === 'delete' && $id > 0){

        $controller->delete($id);
    }

    if($action === 'add'){

        $controller->register(

            $_POST['name'],
            $_POST['email'],
            $_POST['password'],
            $_POST['phone'],
            $_POST['role']

        );
    }

    if($action === 'edit'){

        $controller->update(

            (int)$_POST['user_id'],
            $_POST['name'],
            $_POST['email'],
            $_POST['phone'],
            $_POST['role']

        );
    }
}




if($type === 'category'){

    require_once __DIR__ . '/../controllers/CategoryController.php';

    $controller =
        new CategoryController();

    if($action === 'delete' && $id > 0){

        $controller->delete($id);
    }

    if($action === 'add'){

        $controller->store(

            $_POST['name'],
            $_POST['image']

        );
    }

    if($action === 'edit'){

        $controller->update(

            (int)$_POST['category_id'],
            $_POST['name'],
            $_POST['image']

        );
    }
}




if($type === 'product'){

    require_once __DIR__ . '/../controllers/ProductController.php';

    $controller =
        new ProductController();

    if($action === 'delete' && $id > 0){

        $controller->delete($id);
    }

    if($action === 'add'){

        $controller->store(

            (int)$_POST['category_id'],
            $_POST['name'],
            $_POST['description'],
            (float)$_POST['price'],
            (int)$_POST['stock_quantity'],
            $_POST['image']

        );
    }

    if($action === 'edit'){

        $controller->update(

            (int)$_POST['product_id'],
            (int)$_POST['category_id'],
            $_POST['name'],
            $_POST['description'],
            (float)$_POST['price'],
            (int)$_POST['stock_quantity'],
            $_POST['image']

        );
    }
}




if($type === 'order'){

    require_once __DIR__ . '/../controllers/OrderController.php';

    $controller =
        new OrderController();

    if($action === 'delete' && $id > 0){

        $controller->delete($id);
    }

    if($action === 'add'){

        $controller->store(

            (int)$_POST['user_id'],
            (float)$_POST['total_amount'],
            $_POST['payment_method'],
            $_POST['delivery_method']

        );
    }

    if($action === 'edit'){

        $controller->update(

            (int)$_POST['order_id'],
            $_POST['status']

        );
    }
}




if($type === 'message'){

    require_once __DIR__ . '/../controllers/ContactController.php';

    $controller =
        new ContactController();

    if($action === 'delete' && $id > 0){

        $controller->delete($id);
    }
}

header('Location: ../View/dashboard.php');

exit;