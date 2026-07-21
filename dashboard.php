<?php
session_start();
if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
    // Nếu người dùng chưa đăng nhập, chuyển hướng đến login.php
    header("Location: login.php");
    exit();
}
require_once __DIR__ . '/data.php';
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'order') 
    $selectedSku = $_POST['sku'] ?? '';
    $orderQty = (int)($_POST['qty'] ?? 0);
    if ($selectedSku !== '' && $orderQty >0) {
        $targetProduct = null;
        foreach ($productObjects as $p) {
            if ($p->sku === $selectedSku) {
                $targetProduct = $p;
                break;
            }
        }
        if ($targetProduct){
            if(!isset($_SESSION['orders'])) {
                $_SESSION['orders'] = [];
            }
            $_SESSION['orders'][] = [
                'sku' => $targetProduct->sku,
                'name' => $targetProduct->name,
                'categoryId' => $targetProduct->categoryId,
                'price' => $targetProduct->price,
                'qty' => $orderQty,
                'total' => $targetProduct->price * $orderQty,
                'created_at' => date('H:i:s d/m/Y')
            ];
             header("Location: login.php");
             exit();
        }
    }

$totalInventoryValue = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quan li san pham</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .btn-logout {
            padding: 10px 20px;
            background-color: #f44336;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .text-right {
            text-align: right;
        }
        .total-row {
            font-weight: bold;
            background-color: #f9f9f9;
        }
        .card {
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .form-inline {
            display: flex;
            align-items: center;
        }
        .form-inline select, .form-inline input {
            margin-right: 10px;
            padding: 8px;
        }
        .btn-submit {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        </style>>
</head>
<body>
    <div class="header">
        <h2>Xin chao, <u><?= htmlspecialchars($_SESSION['username']) ?></u></h2>
        <a href="logout.php" class="btn-logout">Dang xuat</a>
    </div>
    <h2>Danh sach san pham</h2>
    <table>
        <thead>
            <tr>
                <th>SKU</th>
                <th>Ten san pham</th>
                <th>Danh muc</th>
                <th class="text-right">Don gia</th>
                <th class="text-right">So luong</th>
                <th class="text-right">Thanh tien</th>
                <th>Trang thai ton kho</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($productObjects as $p):?>
                <?php
                $totalInventoryValue += $p->lineTotal();
                $category = $categoryObjects[$p->categoryId] ?? null;
                ?>
            <tr>
                <td><?php htmlspecialchars($p->sku); ?></td>
                <td><?php htmlspecialchars($p->name); ?></td>
                <td><?php htmlspecialchars($category ? $category->name : 'N/A'); ?></td>
                <td class="text-right"><?= number_format($p->price, 0); ?></td>
                <td class="text-right"><?=  $p->qty; ?></td>
                <td class="text-right"><?= number_format($p->lineTotal(), 0); ?></td>
                <td><?= htmlspecialchars($p->stockLevel()); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot
            <tr class="total-row">
                <td colspan="5" class="text-right">Tong gia tri ton kho:</td>
                <td class="text-right"><?= number_format($totalInventoryValue, 0); ?></td>
                <td></td>
            </tr>

    </table>
    <div class="card">
        <h3>Dat hang</h3>
        <form method="POST" action="dashboard.php" class="form-inline">
            <input type="hidden" name="action" value="order">
            <label for="sku">Chon san pham:</label>
            <select name="sku" id="sku" required>
                <option value="">-- Chon san pham --</option>
                <?php foreach($productObjects as $p): ?>
                    <option value="<?= htmlspecialchars($p->sku); ?>"><?= htmlspecialchars($p->name); ?></option>
                <?php endforeach; ?>
            </select>
            <label for="qty">So luong:</label>
            <input type="number" name="qty"  min="1" value="1" required>
            <button type="submit" class="btn-submit">Dat thu</button>
        </form>
    </div>
    <h3>
        Danh sach Dat thu da luu
    </h3>
    <?php if(!empty($_SESSION['orders'])): ?>
        <table>
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Ten san pham</th>
                    <th>Danh muc</th>
                    <th class="text-right">Don gia</th>
                    <th class="text-right">So luong dat</th>
                    <th class="text-right">Thanh tien</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($_SESSION['orders'] as $index =>$order): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($order['create_at']) ?></td>
                        <td><?= htmlspecialchars($order['sku']) ?></td>
                        <td><?= htmlspecialchars($order['name']) ?></td>    
                        <td class="text-right"><?= number_format($order['price'], 0) ?></td>
                        <td class="text-right"><?= $order['qty'] ?></td>
                        <td class="text-right"><?= number_format($order['total'], 0) ?></td>
                    </tr>
                    <?php endforeach;?>
            </tbody>
            </table>
            <?php else: ?>
                <p>Chua co don hang nao duoc luu.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
