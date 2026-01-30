# Advanced Custom Fields for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/arshpharala/advanced-custom-fields.svg?style=flat-square)](https://packagist.org/packages/arshpharala/advanced-custom-fields)
[![Total Downloads](https://img.shields.io/packagist/dt/arshpharala/advanced-custom-fields.svg?style=flat-square)](https://packagist.org/packages/arshpharala/advanced-custom-fields)
[![Laravel Version](https://img.shields.io/badge/laravel-10.x%20|%2011.x%20|%2012.x-red.svg?style=flat-square)](https://laravel.com)
[![PHP Version](https://img.shields.io/badge/php-8.1+-blue.svg?style=flat-square)](https://php.net)

Empower your Laravel application with a sophisticated, user-friendly, and developer-centric custom fields system. Inspired by WordPress ACF but rebuilt from the ground up for the modern Laravel ecosystem, this package provides a schema-less, highly performant way to manage content that doesn't belong in your core database tables.

---

## Table of Contents
- [Why ACF++ for Laravel?](#why-acf-for-laravel)
- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Quick Start](#quick-start)
- [Admin Panel](#admin-panel)
- [Configuration](#configuration)
- [Permissions & Security](#permissions--security)
- [Model Integration](#model-integration)
- [Rendering Fields](#rendering-fields)
- [Reading Values](#reading-values)
- [Querying & Filtering](#querying--filtering)
- [Locations & Rules](#locations--rules)
- [Field Types](#field-types)
- [Presentation & Theming](#presentation--theming)
- [Conditional Logic](#conditional-logic)
- [Export/Import & migrate:fresh Safety](#exportimport--migratefresh-safety)
- [Health & Recovery](#health--recovery)
- [Caching & Performance](#caching--performance)
- [Troubleshooting FAQ](#troubleshooting-faq)
- [Roadmap](#roadmap)
- [License](#license)

---

## Why ACF++ for Laravel?
Laravel often forces a choice: either bloat your models with dozens of nullable columns or use a rigid EAV system. **Advanced Custom Fields** solves this by providing:
1. **Schema-less values**: Values live in a single polymorphic table, keeping your main tables clean.
2. **Powerful UI**: A plug-and-play admin interface for non-technical users.
3. **Developer Experience**: Fluent API, Blade directives, and exportable definitions that version-control perfectly.

---

## Features

### 💎 Admin UX
- **Beautiful Dashboard**: Glassmorphism-inspired UI with Bootstrap 5 and Alpine.js.
- **Drag & Drop**: Reorder fields effortlessly using integrated SortableJS.
- **Search & Filter**: Find field groups instantly in large-scale setups.
- **Contextual Management**: Manage fields directly where they are used.

### 🛠️ Developer DX
- **Fluent Trait**: Simple `use HasAdvancedCustomFields` on any Eloquent model.
- **Blade Components**: `<x-acf::form />` handles all input rendering and validation.
- **Global Helpers**: Access data anywhere with `acf($model, 'key')`.
- **Eager Loading**: Prevents N+1 issues when fetching custom data.

### 🛡️ Safety & Stability
- **Export/Import Engine**: Definitions move from DB to JSON, making `migrate:fresh` painless for teams.
- **Soft Deletes**: Field groups and fields are never destroyed accidentally.
- **Mismatch Detection**: Real-time warnings if your database is out of sync with your JSON definitions.

### ⚡ Performance
- **Optimized SQL**: Polymorphic queries are indexed and eager-load friendly.
- **Tag-based Caching**: Instant retrieval of field definitions.

---

## Requirements
- PHP 8.1 or higher
- Laravel 10.x, 11.x, or 12.x
- Bootstrap 5 or Tailwind CSS (for Admin UI)

---

## Installation

### 1. Install via Composer
```bash
composer require arshpharala/advanced-custom-fields
```

### 2. Run the Install Command
This command will publish the config, migrations, and assets, and run the initial migrations.
```bash
php artisan acf:install
```

### 3. Manual Publishing (Optional)
If you need to manually publish specific tags:
```bash
# Publish Config
php artisan vendor:publish --tag="acf-config"

# Publish Assets
php artisan vendor:publish --tag="acf-assets"

# Publish Views (for deep customization)
php artisan vendor:publish --tag="acf-views"
```

---

## Quick Start (5-Minute Setup)

1. **Create a Group**: Go to `/admin/advanced-custom-fields` and create "Product Details".
2. **Add a Field**: Add a "Technical Specifications" textarea with key `specs`.
3. **Set Location**: Set the rule to `Post Type == Product`.
4. **Attach Trait**: Add the trait to your model.
   ```php
   use Arshpharala\AdvancedCustomFields\Traits\HasAdvancedCustomFields;

   class Product extends Model {
       use HasAdvancedCustomFields;
   }
   ```
5. **Render Form**: Add the component to your edit view.
   ```blade
   <x-acf::form :model="$product" />
   ```
6. **Read Value**: Use the helper in your frontend.
   ```blade
   <div class="specs">{{ acf($product, 'specs') }}</div>
   ```

---

## Admin Panel

The Admin Panel is accessible via `/admin/advanced-custom-fields` by default.

### Available Screens:
- **Field Groups**: List, search, and manage groups.
- **Group Editor**: Add fields, configure location rules, and sort via drag & drop.
- **Import/Export**: Handle JSON synchronization.
- **Health Check**: Detect orphan values and system issues.

> [!TIP]
> You can change the route prefix and apply custom middleware (e.g., `auth`, `admin.only`) in the `config/advanced-custom-fields.php` file.

---

## Configuration

```php
return [
    /*
    |--------------------------------------------------------------------------
    | Admin Route Configuration
    |--------------------------------------------------------------------------
    */
    'route_prefix' => 'admin/advanced-custom-fields',
    'middleware' => ['web', 'auth'],

    /*
    |--------------------------------------------------------------------------
    | Theme & UI Presets
    |--------------------------------------------------------------------------
    | 'bootstrap5' (default) or 'tailwind'
    */
    'theme' => 'bootstrap5',

    /*
    |--------------------------------------------------------------------------
    | Export Configuration
    |--------------------------------------------------------------------------
    */
    'export_path' => storage_path('app/acf/definitions.json'),
    'auto_export' => false, // Automatically export on save (dev only)
];
```

---

## Permissions & Security

### Defining Access
Use a Laravel Gate to control who can access the ACF Admin UI. 

```php
// app/Providers/AuthServiceProvider.php
Gate::define('view-acf-admin', function ($user) {
    return $user->is_admin;
});
```

Then specify the middleware in `config/advanced-custom-fields.php`:
```php
'middleware' => ['web', 'auth', 'can:view-acf-admin'],
```

### HTML Snippet Safety
If using fields that allow HTML, ensure you sanitize output unless explicitly trusted.
```blade
{!! clean(acf($post, 'html_content')) !!} 
```

---

## Model Integration

Add the `HasAdvancedCustomFields` trait to any Eloquent model.

### API Reference
- `acf(string $key, $default = null)`: Get a processed value.
- `setAcf(string $key, $value)`: Set a value (persists on save or via updateOrCreate).
- `syncAcf(array $values)`: Batch update values.
- `acfAll()`: Get all custom values for the model.
- `acfMeta(string $key)`: Get the raw `Value` model (includes locale/metadata).

---

## Rendering Fields (Admin)

### Blade Component
The most common way to render fields on a model edit page.
```blade
{{-- Renders all groups assigned to the "normal" position for this model --}}
<x-acf::form :model="$product" position="normal" />

{{-- Renders for a create screen (no model instance yet) --}}
<x-acf::form :model-type="App\Models\Product::class" context="admin.create" />
```

### Manual Rendering
```blade
@foreach($product->acfGroups() as $group)
    <h3>{{ $group->name }}</h3>
    @foreach($group->fields as $field)
        {!! acf_field($field, $product) !!}
    @endforeach
@endforeach
```

---

## Reading Values (Frontend Blade)

### 1. Loop Through All Assigned Fields
Ideal for "Details" or "Specifications" tabs where you want to show everything assigned to the model.

```blade
<div class="product-specs">
    <h4>Specifications</h4>
    <dl class="row">
        @foreach($product->acfAll() as $key => $value)
            @if($value)
                <dt class="col-sm-4">{{ ucfirst(str_replace('_', ' ', $key)) }}</dt>
                <dd class="col-sm-8">{{ is_array($value) ? implode(', ', $value) : $value }}</dd>
            @endif
        @endforeach
    </dl>
</div>
```

### 2. Access Specific Fields Individually
Use the `@acf` directive or the `acf()` helper for fine-grained control.

```blade
{{-- Using the Directive (Best for simple strings/colors) --}}
<div class="banner" style="background-color: @acf($product, 'theme_color', '#ffffff')">
    <h1>@acf($product, 'headline')</h1>
</div>

{{-- Using the Helper (Best for toggles and logic) --}}
@if(acf($product, 'show_sidebar'))
    <aside>...</aside>
@endif

@foreach(acf($product, 'features', []) as $feature)
    <li>{{ $feature }}</li>
@endforeach
```

### 4. Repeater Fields
Repeater fields allow you to create rows of content with sub-fields.

```blade
@if(have_rows('hero_slides', $product))
    <div class="carousel">
        @while(have_rows('hero_slides', $product))
            @php the_row('hero_slides', $product); @endphp
            <div class="slide">
                <img src="{{ get_sub_field('image') }}">
                <h3>{{ get_sub_field('caption') }}</h3>
                <p>{{ get_sub_field('description') }}</p>
            </div>
        @endwhile
    </div>
@endif
```

> [!NOTE]
> `the_row()` advances the pointer and returns the row data. `get_sub_field()` retrieves values from the current row.

### 3. Accessing Field Metadata (Labels/Instructions)
If you need the **Label** instead of the Raw Key:

```blade
@php $field = $product->acfMeta('warranty_info'); @php

@if($field)
    <div class="field-info">
        <label class="fw-bold">{{ $field->name }}</label>
        <span class="text-muted d-block small">{{ $field->instructions }}</span>
        <div class="content">{{ acf($product, 'warranty_info') }}</div>
    </div>
@endif
```

---

## Querying & Filtering

ACF for Laravel provides powerful query scopes to filter models based on custom field values.

### Basic Filtering
```php
// Find all products with a 'rating' > 4
$topProducts = Product::whereAcf('rating', '>', 4)->get();

// Find products with 'color' in a list
$blueItems = Product::whereAcfIn('color', ['blue', 'navy', 'sky'])->get();
```

### Sorting & Ordering
```php
// Order products by a custom 'priority' field
$ordered = Product::orderByAcf('priority', 'desc')->get();
```

### Advanced Eloquent Integration
```php
// Combine with standard Eloquent queries and relationships
$products = Product::where('status', 'active')
    ->with('category')
    ->whereAcf('sale_price', '<', 100)
    ->orderBy('created_at', 'desc')
    ->paginate(15);
```

---

## Locations & Rules

Field Groups are displayed based on **Location Rules**. 

### Rule Structure (JSON Metadata)
```json
{
    "param": "post_type",
    "operator": "==",
    "value": "App\\Models\\Product"
}
```

### Standard Contexts:
- `admin.edit`: Default edit screen.
- `admin.create`: Only show during creation.
- `frontend.form`: Public facing forms.

---

## Field Types

| Type | Data Type | UI Component |
| :--- | :--- | :--- |
| `text` | String | Standard Input |
| `textarea` | Text | Textarea |
| `number` | Integer/Float | Number Input |
| `toggle` | Boolean | Switch/Checkbox |
| `select` | String/Array | Dropdown/Multi-select |
| `date` | Date | HTML5 Date Picker |
| `email` | String | Email Input |
| `url` | String | URL Input |
| `color` | String | Color Picker |
| `repeater` | Array | Repeating rows of sub-fields |

---

## Presentation & Theming

Control the wrapper, label, and input styling via the **Presentation** JSON.

### Admin Template Integration

#### Tailwind (Default)
The package utilizes Tailwind-friendly classes for its internal UI. You can override these in config.

#### AdminLTE 3 / Bootstrap 3 & 4
Specify the `bootstrap5` or `bootstrap4` theme preset in config to adjust wrapper classes like `form-group` vs `mb-3`.

---

## Conditional Logic

Show or hide fields based on the values of other fields in the same group.

### Logic Example
```json
[
    {
        "field": "has_discount",
        "operator": "==",
        "value": "1"
    }
]
```
In the example above, the current field will only appear if the `has_discount` toggle is checked.

---

## Export/Import & migrate:fresh Safety

### The Problem
If you store your ACF definitions (Field Groups/Fields) only in the database, running `php artisan migrate:fresh` will wipe them out. 

### The Solution: JSON Synchronization
Use the provided commands to keep a version-controlled copy of your configuration.

1. **Export**: After creating fields in UI, run:
   ```bash
   php artisan acf:export
   ```
2. **Commit**: Check `storage/app/acf/definitions.json` into Git.
3. **Import**: On staging/production or after a fresh local migration, run:
   ```bash
   php artisan acf:import
   ```

### Recommended Team Workflow
- Add `php artisan acf:import` to your `composer.json` post-install/update scripts or your deployment pipeline.

---

## Health & Recovery

### Orphan Values
If you delete a Field definition, the stored Values in `acf_values` remain as "orphans". Use the **Health Check** screen to:
- Detect orphaned data.
- Bulk delete values for obsolete fields.

### Safe Deletion
By default, the package uses **Soft Deletes**. When you delete a group, it's moved to the trash. You can restore it from the database or via the Admin UI.

---

## Caching & Performance

### What is Cached?
- Field definitions (keyed by Model + Key).
- Location rule evaluations for a model instance.

### Management
```php
# Clear all ACF cache
php artisan cache:forget acf.definitions
```

---

## Troubleshooting FAQ

**Q: My fields are not showing on the model edit page.**
- Check the **Location Rules**. Ensure the `model_type` matches exactly (including namespace).
- Ensure the `position` in `<x-acf::form />` matches the group's position.

**Q: Why do I see a "Definitions Mismatch" banner?**
- Your database has changes that haven't been exported to JSON, or someone updated the JSON file and you haven't run `acf:import`.

---

## Roadmap
- [ ] Flexible Content blocks (Layout based).
- [ ] Advanced Relationship fields (Select from other Models).
- [ ] REST API & GraphQL integration.
- [ ] Inertia.js / Vue.js rendering components.

---

## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
