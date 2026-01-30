<?php

namespace Arshpharala\AdvancedCustomFields\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Arshpharala\AdvancedCustomFields\Models\FieldGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FieldGroupController extends Controller
{
    public function index(Request $request)
    {
        $query = FieldGroup::withCount('fields')->orderBy('order');
        
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('key', 'like', '%' . $request->search . '%');
        }

        $groups = $query->get();
        return view('acf::admin.groups.index', compact('groups'));
    }

    public function create()
    {
        return view('acf::admin.groups.edit', [
            'group' => new FieldGroup(),
            'is_edit' => false
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'key' => 'required|string|unique:acf_field_groups,key',
            'position' => 'required|string',
            'is_active' => 'boolean',
        ]);

        $group = FieldGroup::create($validated);

        return redirect()->route('acf.admin.groups.edit', $group)
            ->with('success', 'Field group created successfully.');
    }

    public function edit(FieldGroup $group)
    {
        $group->load('fields', 'locations');
        return view('acf::admin.groups.edit', [
            'group' => $group,
            'is_edit' => true
        ]);
    }

    public function update(Request $request, FieldGroup $group)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'key' => 'required|string|unique:acf_field_groups,key,' . $group->id,
            'position' => 'required|string',
            'is_active' => 'boolean',
        ]);

        $group->update($validated);

        // Update fields and locations here if needed in a batch update

        return redirect()->back()->with('success', 'Field group updated successfully.');
    }

    public function destroy(FieldGroup $group)
    {
        $group->delete();
        return redirect()->route('acf.admin.index')->with('success', 'Field group deleted.');
    }
}
