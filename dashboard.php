<?php
session_start();

// 1. Chặn truy cập nếu chưa đăng nhập
if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/data.php';

// 2. Xử lý form đặt hàng thử (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'order') {
    $selectedSku = $_POST['sku'] ?? '';
    $orderQty = (int)($_POST['qty'] ?? 0);

    if ($selectedSku !== '' && $orderQty > 0) {
        $targetProduct = null;
        foreach ($productObjects as $p) {
            if ($p->sku === $selectedSku) {
                $targetProduct = $p;
                break;
            }
        }

        if ($targetProduct) {
            if (!isset($_SESSION['orders'])) {
                $_SESSION['orders'] = [];
            }

            // Lưu đơn hàng vào Session
            $_SESSION['orders'][] = [
                'sku' => $targetProduct->sku,
                'name' => $targetProduct->name,
                'categoryId' => $targetProduct->categoryId,
                'price' => $targetProduct->price,
                'qty' => $orderQty,
                'total' => $targetProduct->price * $orderQty,
                'created_at' => date('H:i:s d/m/Y')
            ];

            // Chuyển hướng lại dashboard để tránh gửi lại form khi Reload
            header("Location: dashboard.php");
            exit();
        }
    }
}

$totalInventoryValue = 0;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.5; }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #ccc; padding-bottom: 10px; margin-bottom: 20px; }
        .btn-logout { background: #dc3545; color: white; padding: 8px 15px; text-decoration: none; border-radius: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; margin-bottom: 25px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f4f4f4; }
        .text-right { text-align: right; }
        .total-row { font-weight: bold; background-color: #eef; }
        .card { background: #f9f9f9; padding: 15px; border: 1px solid #ddd; border-radius: 6px; margin-bottom: 25px; }
        .form-inline { display: flex; gap: 10px; align-items: center; }
        .form-inline select, .form-inline input { padding: 8px; }
        .btn-submit { background: #28a745; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>

    <div class="header">
        <h2>Xin chào, <u><?= htmlspecialchars($_SESSION['username']) ?></u>!</h2>
        <a href="logout.php" class="btn-logout">Đăng xuất</a>
    </div>

    <!-- 1. BẢNG SẢN PHẨM -->
    <h3>1. Danh sách sản phẩm kho</h3>
    <table>
        <thead>
            <tr>
                <th>SKU</th>
                <th>Tên sản phẩm</th>
                <th>Danh mục</th>
                <th class="text-right">Đơn giá (VNĐ)</th>
                <th class="text-right">Số lượng</th>
                <th class="text-right">Thành tiền (VNĐ)</th>
                <th>Trạng thái tồn kho</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productObjects as $p): ?>
                <?php
                    $totalInventoryValue += $p->lineTotal();
                    $category = $categoryObjects[$p->categoryId] ?? null;
                ?>
                <tr>
                    <td><?= htmlspecialchars($p->sku); ?></td>
                    <td><?= htmlspecialchars($p->name); ?></td>
                    <td><?= htmlspecialchars($category ? $category->label() : 'N/A'); ?></td>
                    <td class="text-right"><?= number_format($p->price, 0, ',', '.'); ?></td>
                    <td class="text-right"><?= $p->qty; ?></td>
                    <td class="text-right"><?= number_format($p->lineTotal(), 0, ',', '.'); ?></td>
                    <td><?= htmlspecialchars($p->stockLevel()); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="5" class="text-right">Tổng giá trị tồn kho:</td>
                <td class="text-right"><?= number_format($totalInventoryValue, 0, ',', '.'); ?></td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <!-- 2. FORM ĐẶT THỬ SẢN PHẨM -->
    <div class="card">
        <h3>2. Đặt thử sản phẩm</h3>
        <form method="POST" action="dashboard.php" class="form-inline">
            <input type="hidden" name="action" value="order">
            
            <label for="sku">Chọn sản phẩm:</label>
            <select name="sku" id="sku" required>
                <option value="">-- Chọn sản phẩm --</option>
                <?php foreach ($productObjects as $p): ?>
                    <option value="<?= htmlspecialchars($p->sku); ?>">
                        [<?= htmlspecialchars($p->sku); ?>] <?= htmlspecialchars($p->name); ?> - <?= number_format($p->price, 0, ',', '.') ?>đ
                    </option>
                <?php endforeach; ?>
            </select>
            
            <label for="qty">Số lượng:</label>
            <input type="number" name="qty" id="qty" min="1" value="1" required>
            
            <button type="submit" class="btn-submit">Đặt thử</button>
        </form>
    </div>

    <!-- 3. BẢNG ĐƠN HÀNG ĐÃ LƯU TRONG SESSION -->
    <h3>3. Danh sách Đặt thử đã lưu</h3>
    <?php if (!empty($_SESSION['orders'])): ?>
        <table>
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Thời gian</th>
                    <th>SKU</th>
                    <th>Tên sản phẩm</th>
                    <th class="text-right">Đơn giá</th>
                    <th class="text-right">Số lượng đặt</th>
                    <th class="text-right">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['orders'] as $index => $order): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($order['created_at']) ?></td>
                        <td><?= htmlspecialchars($order['sku']) ?></td>
                        <td><?= htmlspecialchars($order['name']) ?></td>    
                        <td class="text-right"><?= number_format($order['price'], 0, ',', '.') ?></td>
                        <td class="text-right"><?= $order['qty'] ?></td>
                        <td class="text-right"><?= number_format($order['total'], 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p><i>Chưa có đơn hàng nào được lưu trong Session. Hãy thử gửi form đặt thử phía trên!</i></p>
    <?php endif; ?>

</body>
</html>