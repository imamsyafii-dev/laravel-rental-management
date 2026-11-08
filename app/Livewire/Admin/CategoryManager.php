<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryManager extends Component
{
    use WithPagination;

    public ?int $categoryId = null;

    public string $name = '';

    public string $slug = '';

    public string $description = '';

    public bool $is_active = true;

    public bool $isEditing = false;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                'alpha_dash',
                'unique:categories,slug,' . ($this->categoryId ?? 'NULL') . ',id',
            ],
            'description' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ];
    }

    public function save(): void
    {
        $validated = $this->validate();

        if ($this->categoryId) {
            Category::findOrFail($this->categoryId)->update($validated);
        } else {
            Category::create($validated);
        }

        session()->flash(
            'success',
            $this->isEditing
                ? 'Kategori berhasil diperbarui.'
                : 'Kategori berhasil ditambahkan.'
        );

        $this->resetForm();
    }

    public function edit(int $id): void
    {
        $category = Category::findOrFail($id);

        $this->categoryId = $category->id;
        $this->name = $category->name;
        $this->slug = $category->slug;
        $this->description = $category->description ?? '';
        $this->is_active = $category->is_active;
        $this->isEditing = true;
    }

    public function delete(int $id): void
    {
        Category::findOrFail($id)->delete();

        session()->flash('success', 'Kategori berhasil dihapus.');

        $this->resetPage();
    }

    public function resetForm(): void
    {
        $this->reset([
            'categoryId',
            'name',
            'slug',
            'description',
            'isEditing',
        ]);

        $this->is_active = true;
    }

    public function render()
    {
        return view('livewire.admin.category-manager', [
            'categories' => Category::latest()->paginate(10),
        ]);
    }
}