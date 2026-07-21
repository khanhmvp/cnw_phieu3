<?php  
class Category
{
    public $id;
    public string $name;
    public string $description;
    public function __construct($id, $name, $description)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
    }
    public function label(): string
    {
        return "[{$this->id}] {$this->name}";
    }
}