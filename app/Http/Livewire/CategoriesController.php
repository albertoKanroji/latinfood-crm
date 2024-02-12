<?php

namespace App\Http\Livewire;


use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response;


class CategoriesController extends Component
{
	use WithFileUploads;
	use WithPagination;
	public $name, $search, $image, $selected_id, $pageTitle, $componentName;
	private $pagination = 5;
	public $subform;
	public function mount()
	{
		$this->pageTitle = 'Listado';
		$this->componentName = 'Categorías';
	}
	public function paginationView()
	{
		return 'vendor.livewire.bootstrap';
	}
	public function render()
	{
		$currentPage = 'categorias';
		if (strlen($this->search) > 0)
			$data = Category::where('name', 'like', '%' . $this->search . '%')->paginate($this->pagination);
		else
			$data = Category::orderBy('id', 'desc')->paginate($this->pagination);
		return view('livewire.category.categories', ['categories' => $data, 'currentPage' => $currentPage])
			->extends('layouts.theme.app')
			->section('content');
	}
	public function Edit($id)
	{
		$record = Category::find($id, ['id', 'name', 'image']);
		$this->name = $record->name;
		$this->selected_id = $record->id;
		$this->image = null;
		$this->emit('show-modal', 'show modal!');
	}
	public function Store()
	{
		$rules = [
			'name' => 'required|unique:categories|min:3'
		];
		$messages = [
			'name.required' => 'Nombre de la categoría es requerido',
			'name.unique' => 'Ya existe el nombre de la categoría',
			'name.min' => 'El nombre de la categoría debe tener al menos 3 caracteres'
		];
		$this->validate($rules, $messages);
		$category = Category::create([
			'name' => $this->name
		]);


		$customFileName;
		if ($this->image) {
			$customFileName = uniqid() . '_.' . $this->image->extension();
			$this->image->storeAs('public/categories', $customFileName);
			$category->image = $customFileName;
			$category->save();
		}

		$this->resetUI();
		$this->emit('category-added', 'Categoría Registrada');
	}


	public function Update()
	{
		$rules = [
			'name' => "required|min:3|unique:categories,name,{$this->selected_id}"
		];

		$messages = [
			'name.required' => 'Nombre de categoría requerido',
			'name.min' => 'El nombre de la categoría debe tener al menos 3 caracteres',
			'name.unique' => 'El nombre de la categoría ya existe'
		];

		$this->validate($rules, $messages);


		$category = Category::find($this->selected_id);
		$category->update([
			'name' => $this->name
		]);

		if ($this->image) {
			$customFileName = uniqid() . '_.' . $this->image->extension();
			$this->image->storeAs('public/categories', $customFileName);
			$imageName = $category->image;

			$category->image = $customFileName;
			$category->save();

			if ($imageName != null) {
				if (file_exists('storage/categories' . $imageName)) {
					unlink('storage/categories' . $imageName);
				}
			}
		}

		$this->resetUI();
		$this->emit('category-updated', 'Categoría Actualizada');
	}


	public function resetUI()
	{
		$this->name = '';
		$this->image = null;
		$this->search = '';
		$this->selected_id = 0;
	}
	protected $listeners = ['deleteRow' => 'Destroy'];
	public function Destroy(Category $category)
	{
		$imageName = $category->image;
		$category->delete();
		if ($imageName != null) {
			unlink('storage/categories/' . $imageName);
		}
		$this->resetUI();
		$this->emit('category-deleted', 'Categoría Eliminada');
	}
	/*API */

	public function ShowAll()
	{
		try {
			$categories = Category::orderBy('name', 'asc')->get();

			$response = [];
			foreach ($categories as $category) {
				$response[] = [
					'id' => $category->id, // Convierte el ID a una cadena
					'name' => $category->name,
					'image' => asset('storage/categories/' . $category->image),
					'created_at' => $category->created_at,
					'updated_at' => $category->updated_at,
				];
			}
			return response()->json($response);
		} catch (\Exception $e) {
			return response()->json([
				'message' => 'An error occurred',
			], Response::HTTP_INTERNAL_SERVER_ERROR);
		}
	}
	public function CreateApi(Request $request)
	{
		try {
			$request->validate([
				'name' => 'required|unique:categories|min:3',
			]);

			$category = Category::create([
				'name' => $request->input('name'),
			]);

			return response()->json([
				'message' => 'Category created successfully',
				'data' => $category,
			], Response::HTTP_CREATED);
		} catch (\Exception $e) {
			return response()->json([
				'message' => 'An error occurred',
			], Response::HTTP_INTERNAL_SERVER_ERROR);
		}
	}
	public function UpdateApi(Request $request, $id)
	{
		try {
			$category = Category::findOrFail($id);
			$request->validate([
				'name' => "required|min:3|unique:categories,name,{$id}",
			]);
			$category->update([
				'name' => $request->input('name'),
			]);
			return response()->json([
				'message' => 'Category updated successfully',
				'data' => $category,
			]);
		} catch (ModelNotFoundException $e) {
			return response()->json([
				'message' => 'Category not found',
			], Response::HTTP_NOT_FOUND);
		} catch (\Exception $e) {
			return response()->json([
				'message' => 'An error occurred',
			], Response::HTTP_INTERNAL_SERVER_ERROR);
		}
	}
	public function FindApi($id)
	{
		try {
			$category = Category::findOrFail($id);
			return response()->json([
				'data' => $category,
			]);
		} catch (ModelNotFoundException $e) {
			return response()->json([
				'message' => 'Category not found',
			], Response::HTTP_NOT_FOUND);
		} catch (\Exception $e) {
			return response()->json([
				'message' => 'An error occurred',
			], Response::HTTP_INTERNAL_SERVER_ERROR);
		}
	}
}
