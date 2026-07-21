<?php

class Category
{
    public int $id;
    public string $name;
    public string $description;

    public function __construct(int $id, string $name, string $description = '')
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
    }

    // Trả về định dạng "[id] name"
    public function label(): string
    {
        return "[{$this->id}] {$this->name}";
    }
}