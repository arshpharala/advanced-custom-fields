<?php

namespace Arshpharala\AdvancedCustomFields\Tests;

use Arshpharala\AdvancedCustomFields\Models\FieldGroup;
use Arshpharala\AdvancedCustomFields\Models\Field;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RepeaterFieldTest extends TestCase
{
    /** @test */
    public function it_can_save_repeater_sub_fields()
    {
        // 1. Create a group
        $group = FieldGroup::create([
            'name' => 'Hero Section',
            'key' => 'hero_section',
            'position' => 'normal'
        ]);

        // 2. Create a repeater field
        $repeater = Field::create([
            'group_id' => $group->id,
            'name' => 'Slides',
            'key' => 'hero_slides',
            'type' => 'repeater'
        ]);

        // 3. Create sub-fields via FieldController logic simulator
        $subField1 = Field::create([
            'group_id' => $group->id,
            'parent_id' => $repeater->id,
            'name' => 'Image URL',
            'key' => 'slide_image',
            'type' => 'url'
        ]);

        $subField2 = Field::create([
            'group_id' => $group->id,
            'parent_id' => $repeater->id,
            'name' => 'Caption',
            'key' => 'slide_caption',
            'type' => 'text'
        ]);

        $this->assertEquals(2, $repeater->subFields()->count());
        $this->assertEquals('slide_image', $repeater->subFields()->first()->key);
    }

    /** @test */
    public function it_can_format_repeater_value_recursively()
    {
        // Setup same fields as above
        $group = FieldGroup::create(['name' => 'G', 'key' => 'g', 'position' => 'n']);
        $repeater = Field::create(['group_id' => $group->id, 'name' => 'R', 'key' => 'repeater_key', 'type' => 'repeater']);
        Field::create(['group_id' => $group->id, 'parent_id' => $repeater->id, 'name' => 'S', 'key' => 'sub_key', 'type' => 'text']);

        $value = [
            ['sub_key' => 'Row 1'],
            ['sub_key' => 'Row 2'],
        ];

        $fieldType = app(\Arshpharala\AdvancedCustomFields\Support\FieldTypeRegistry::class)->get('repeater');
        $formatted = $fieldType->formatValue($value, $repeater);

        $this->assertIsArray($formatted);
        $this->assertCount(2, $formatted);
        $this->assertEquals('Row 1', $formatted[0]['sub_key']);
    }
}
