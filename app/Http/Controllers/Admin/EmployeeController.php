<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rules;


use function Laravel\Prompts\password;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        Gate::authorize('is-admin');
        $employees = User::where('role' , 'employee')->paginate(15);
        return view('pages.employees.index' ,compact('employees'));


    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('is-admin');
        return view('pages.employees.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('is-admin');
        $validated = $request->validate([
            'name' => ['required' , 'string'],
            'email' => ['required' , 'email' ,'unique:users,email,except,id'],
            'phone' => ['required' , 'string'],
            'image' => ['nullable', 'image' , 'dimensions:min_width=100,min_height=100','max:1048576'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()]
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $file->store("images/employees/{$request->name}", 'public');
            $validated['image'] = $path;
        }

        User::create($validated);
        return redirect()->back()->with('success' , 'تم إضافة الموظف بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        Gate::authorize('is-admin');

        $employee = User::findOrFail($id);
        return view('pages.employees.show' , compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        Gate::authorize('is-admin');
        $employee = User::findOrFail($id);
        return view('pages.employees.edit' ,compact('employee'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        Gate::authorize('is-admin');
        $employee = User::findOrFail($id);
        $validated = $request->validate([
            'name' => ['sometimes' , 'string'],
            'email' => ['sometimes' , 'email' ,'unique:users,email,except,id']
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Gate::authorize('is-admin');
        $employee = User::findOrFail($id);
        $employee->delete();
        return redirect()->route('employee.index')->with('success' , 'تم حذف الموظف بنجاح');
    }
}
