<?php

namespace Arshpharala\AdvancedCustomFields\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Arshpharala\AdvancedCustomFields\Models\Field;

class FieldController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'group_id' => 'required|exists:acf_field_groups,id',
            'name' => 'required|string|max:255',
            'key' => 'required|string|max:255|unique:acf_fields,key',
            'type' => 'required|string',
            'instructions' => 'nullable|string',
            'is_required' => 'boolean',
            'default_value' => 'nullable',
            'options' => 'nullable|array',
            'presentation' => 'nullable|array',
            'sub_fields' => 'nullable|array',
            'parent_id' => 'nullable|exists:acf_fields,id',
        ]);

        $field = Field::create($data);

        if ($request->has('sub_fields')) {
            $this->saveSubFields($field, $request->sub_fields);
        }

        return response()->json($field->load('subFields'));
    }

    public function update(Request $request, Field $field)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'key' => 'required|string|max:255|unique:acf_fields,key,' . $field->id,
            'type' => 'required|string',
            'instructions' => 'nullable|string',
            'is_required' => 'boolean',
            'default_value' => 'nullable',
            'options' => 'nullable|array',
            'presentation' => 'nullable|array',
            'sub_fields' => 'nullable|array',
        ]);

        $field->update($data);

        if ($request->has('sub_fields')) {
            $field->subFields()->delete();
            $this->saveSubFields($field, $request->sub_fields);
        }

        return response()->json($field->load('subFields'));
    }

    private function saveSubFields(Field $parent, array $subFields)
    {
        foreach ($subFields as $index => $sub) {
            $sub['parent_id'] = $parent->id;
            $sub['group_id'] = $parent->group_id;
            $sub['order'] = $index;
            
            // Validate sub-field data (simplified)
            $child = Field::create($sub);
            
            if (isset($sub['sub_fields'])) {
                $this->saveSubFields($child, $sub['sub_fields']);
            }
        }
    }

    public function sort(Request $request)
    {
        foreach ($request->order as $index => $id) {
            Field::where('id', $id)->update(['order' => $index]);
        }
        return response()->json(['success' => true]);
    }

    public function destroy(Field $field)
    {
        $field->delete();
        return response()->json(['success' => true]);
    }

    // Usually fields are edited within the Group edit page, 
    // but we can add specific store/edit methods if needed.
}
