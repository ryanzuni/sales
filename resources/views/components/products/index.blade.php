<?php

use Livewire\Component;
use App\Models\Product;

new class extends Component {

    public $name;
    public $price;
    public $isOpen = false;
    public $products;

    public function mount()
    {
        $this->products = Product::all();
    }

    public function store()
    {
        Product::create([
            'name' => $this->name,
            'price' => $this->price
        ]);

        $this->name = '';
        $this->price = '';
        $this->products = Product::all();
        $this->isOpen = false;
    }
};
?>
