<?php

namespace Arshpharala\AdvancedCustomFields\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Arshpharala\AdvancedCustomFields\Models\Field;
use Illuminate\Http\Request;

class FieldController extends Controller
{
    public function sort(Request $request)
    {
        $order = $request->input('order', []);

        foreach ($order as $index => $id) {
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
