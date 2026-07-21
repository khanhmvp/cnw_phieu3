<?php
require_once __DIR__ . '/data.php';
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
        </style>>
</head>
<body>
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
</body>
</html>