<?php

class Product
{
    public string $sku;
    public string $name;
    public int $categoryId;
    public float $price;
    public int $qty;

    public function __construct(string $sku, string $name, int $categoryId, float $price, int $qty)
    {
        $this->sku = $sku;
        $this->name = $name;
        $this->categoryId = $categoryId;
        $this->price = $price;
        $this->qty = $qty;
    }

    // Tính tổng tiền của dòng sản phẩm
    public function lineTotal(): float
    {
        return $this->price * $this->qty;
    }

    // Đánh giá mức tồn kho
    public function stockLevel(): string
    {
        if ($this->qty <= 2) {
            return 'Cảnh báo hết hàng';
        } elseif ($this->qty <= 5) {
            return 'Sắp hết';
        }
        return 'An toàn';
    }

    // Phục vụ mục đích debug
    public function toArray(): array
    {
        return [
            'sku' => $this->sku,
            'name' => $this->name,
            'category_id' => $this->categoryId,
            'price' => $this->price,
            'qty' => $this->qty,
            'line_total' => $this->lineTotal(),
            'stock_level' => $this->stockLevel()
        ];
    }
}