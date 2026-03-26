<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../../Model/cart/CartModel.php';

$cartModel = new CartModel();
$idTK = $_SESSION['id'] ?? null;

// Mang sản phẩm đã chọn qua trang order_infor.php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_products'])) {
    $_SESSION['selected_products'] = $_POST['selected_products'];
    header("Location: order_infor.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    header('Content-Type: application/json');
    // Thêm sản phẩm vào giỏ hàng, cộng dồn số lượng nếu đã có trong giỏ hàng
    if (isset($_POST['add_to_cart'])) {
        if (!isset($_POST['idCTSP'])) {
            echo json_encode(["success" => false, "message" => "Không có sản phẩm để thêm vào giỏ hàng!"]);
            exit();
        }

        $idSP = intval($_POST['idSP'] ?? 0);
        $idCTSP = intval($_POST['idCTSP'] ?? 0);

        $soluong = isset($_POST['soluong']) ? max(1, intval($_POST['soluong'])) : 1;
        $mausac = $_POST['mausac'] ?? '';
        $dungluong = $_POST['dungluong'] ?? '';
        if (!$idCTSP) {
            echo json_encode(["success" => false, "message" => "Không tìm thấy sản phẩm phù hợp!"]);
            exit();
        }
        $stock_quantity = $cartModel->getStockQuantity($idCTSP);
        $current_cart_quantity = $cartModel->getCartItemQuantity($idTK, $idCTSP);

        if ($soluong + $current_cart_quantity > $stock_quantity) {
            echo json_encode(["success" => false, "message" => "Số lượng sản phẩm trong giỏ hàng + số lượng yêu cầu đã vượt mức tồn kho!"]);
            exit();
        }

        if ($idTK) {
            $cartModel->addToCart($idTK, $idSP, $idCTSP, $soluong, $mausac, $dungluong);
            $quantity = $cartModel->getCartQuantity($idTK);
            echo json_encode(["success" => true, "message" => "Thêm sản phẩm vào giỏ hàng thành công!", 'quantity' => $quantity]);
        }
        exit();
    }
    // Mua ngay sản phẩm, không cộng dồn số lượng khi bấm nhiều lần
    if (isset($_POST['buy_now'])) {
        $idTK = $_SESSION['id'] ?? null;
        if (!$idTK) {
            echo json_encode(["success" => false, "message" => "Chưa đăng nhập!"]);
            exit();
        }

        if (!isset($_POST['idSP']) || !isset($_POST['idCTSP'])) {
            echo json_encode(["success" => false, "message" => "Thiếu thông tin sản phẩm!"]);
            exit();
        }

        $idSP = intval($_POST['idSP']);
        $idCTSP = intval($_POST['idCTSP']);
        $soluong = 1;
        $mausac = $_POST['color'] ?? ' ';
        $dungluong = $_POST['capacity'] ?? ' ';

        // Kiểm tra tồn kho dựa trên idCTSP
        $stock_quantity = $cartModel->getStockQuantity($idCTSP);
        if ($stock_quantity <= 0) {
            echo json_encode(["success" => false, "message" => "Sản phẩm đã hết hàng!"]);
            exit();
        }

        // Kiểm tra sản phẩm này đã có trong giỏ chưa (theo biến thể idCTSP)
        $current_cart_quantity = $cartModel->getCartItemQuantity($idTK, $idCTSP);
        if ($current_cart_quantity > 0) {
            // Đã có, không thêm chỉ chuyển hướng
            echo json_encode(["success" => true, "redirect" => "../../View/cart/cart.php"]);
            exit();
        }

        // Chưa có thì thêm mới
        $cartModel->addToCart($idTK, $idSP, $idCTSP, $soluong, $mausac, $dungluong);

        echo json_encode(["success" => true, "redirect" => "../../View/cart/cart.php"]);
        exit();
    }
    
    // Cộng trừ số lượng sản phẩm trong giỏ hàng
    if (isset($_POST['update']) && isset($_POST['soluong']) && $idTK) {
        $idCTSP = intval($_POST['update']);          // đây là ID chi tiết sản phẩm
        $idSP = intval($_POST['idSP'] ?? 0);
        $soluong = max(1, intval($_POST['soluong']));
    
        $stock_quantity = $cartModel->getStockQuantity($idCTSP);
    
        if ($soluong > $stock_quantity) {
            die("Oái :< :Chỉ còn {$stock_quantity} sản phẩm trong kho!");
        }
    
        $cartModel->updateCart($idTK, $idSP, $idCTSP, $soluong);
        exit();
    }
    
}

// Xóa 1 sản phẩm
if (isset($_GET['remove']) && $idTK) {
    $cartModel->removeProduct($idTK, intval($_GET['remove']));
    header("Location: cart.php");
    exit();
}

// Xóa toàn bộ giỏ hàng
if (isset($_GET['clear']) && $idTK) {
    $cartModel->clearCart($idTK);
    header("Location: cart.php");
    exit();
}

// Lấy giỏ hàng từ session
$_SESSION['cart'] = $idTK ? $cartModel->getCartItems($idTK) : [];
$cartItems = $_SESSION['cart'];

// Lấy danh sách sản phẩm đã chọn
function getSelectedProducts($selected_products, $cartItems)
{
    $selected_cart = [];
    foreach ($selected_products as $idSP) {
        if (isset($cartItems[$idSP])) {
            $selected_cart[$idSP] = $cartItems[$idSP];
        }
    }
    return $selected_cart;
}
