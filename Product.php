<?php   
class Product
{
    public string $sku;
    public string $name;
    public int $categoryId;
    public float $price;
    public int $qty;
    public function __construct($sku, $name, $categoryId, $price, $qty)
    {
        $this->sku = $sku;
        $this->name = $name;
        $this->categoryId = $categoryId;
        $this->price = $price;
        $this->qty = $qty;
    }
    public function lineTotal(): float
    {
        return $this->price * $this->qty;
    }
    public function stockLevel(): string
    {
        if ($this->qty <= 2) {
            return "Canh bao het hang";
        } elseif ($this->qty <= 5) {
            return "Sap het";
        } else {
            return "An toan";
        }
    }
    public function toArray(): array
    {
        return [
            'sku' => $this->sku,
            'name' => $this->name,
            'categoryId' => $this->categoryId,
            'price' => $this->price,
            'qty' => $this->qty,
            'lineTotal' => $this->lineTotal(),
            'stockLevel' => $this->stockLevel()
        ];
    }
}