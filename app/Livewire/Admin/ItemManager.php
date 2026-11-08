<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Item;
use Livewire\Component;
use Livewire\WithPagination;

class ItemManager extends Component
{
    use WithPagination;

    public ?int $itemId = null;

    public ?int $category_id = null;

    public string $name = '';

    public string $slug = '';

    public string $description = '';

    public string $price_per_day = '';

    public int $stock = 1;

    public bool $is_active = true;

    public bool $isEditing = false;

    protected function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                'alpha_dash',
                'unique:items,slug,' . ($this->itemId ?? 'NULL') . ',id',
            ],
            'description' => ['nullable', 'string'],
            'price_per_day' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ];
    }

    public function save(): void
    {
        $validated = $this->validate();

        if ($this->itemId) {
            Item::findOrFail($this->itemId)->update($validated);
        } else {
            Item::create($validated);
        }

        session()->flash(
            'success',
            $this->isEditing
                ? 'Barang berhasil diperbarui.'
                : 'Barang berhasil ditambahkan.'
        );

        $this->resetForm();
    }

    public function edit(int $id): void
    {
        $item = Item::findOrFail($id);

        $this->itemId = $item->id;
        $this->category_id = $item->category_id;
        $this->name = $item->name;
        $this->slug = $item->slug;
        $this->description = $item->description ?? '';
        $this->price_per_day = (string) $item->price_per_day;
        $this->stock = $item->stock;
        $this->is_active = $item->is_active;
        $this->isEditing = true;
    }

    public function delete(int $id): void
    {
        Item::findOrFail($id)->delete();

        session()->flash('success', 'Barang berhasil dihapus.');

        $this->resetPage();
    }

    public function resetForm(): void
    {
        $this->reset([
            'itemId',
            'category_id',
            'name',
            'slug',
            'description',
            'price_per_day',
            'isEditing',
        ]);

        $this->stock = 1;
        $this->is_active = true;
    }

    public function render()
    {
        return view('livewire.admin.item-manager', [
            'items' => Item::with('category')->latest()->paginate(10),
            'categories' => Category::where('is_active', true)
                ->orderBy('name')
                ->get(),
        ]);
    }
}