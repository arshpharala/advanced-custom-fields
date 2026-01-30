@extends('acf::admin.layout')

@section('title', $is_edit ? 'Edit Field Group' : 'New Field Group')

@section('breadcrumb')
  <li class="breadcrumb-item active">{{ $is_edit ? $group->name : 'New' }}</li>
@endsection

@section('content')
  <form action="{{ $is_edit ? route('acf.admin.groups.update', $group) : route('acf.admin.groups.store') }}" method="POST">
    @csrf
    @if ($is_edit)
      @method('PUT')
    @endif

    <div class="row">
      <div class="col-md-8">
        <div class="acf-card bg-white p-4 mb-4">
          <h5 class="fw-bold mb-4">Group Settings</h5>
          <div class="row g-3">
            <div class="col-12">
              <label class="form-label fw-semibold">Group Name</label>
              <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', $group->name) }}" placeholder="e.g. Hero Section Settings">
              @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Group Key</label>
              <input type="text" name="key" class="form-control @error('key') is-invalid @enderror"
                value="{{ old('key', $group->key) }}" placeholder="hero_settings">
              @error('key')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Position</label>
              <select name="position" class="form-select">
                <option value="normal" {{ $group->position == 'normal' ? 'selected' : '' }}>Normal (Main column)</option>
                <option value="sidebar" {{ $group->position == 'sidebar' ? 'selected' : '' }}>Sidebar</option>
                <option value="after_title" {{ $group->position == 'after_title' ? 'selected' : '' }}>After Title</option>
              </select>
            </div>
          </div>
        </div>

        <div class="acf-card bg-white p-4" x-data="fieldManager()">
          <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold m-0">Fields</h5>
            <button type="button" class="btn btn-sm btn-outline-primary" @click="addField()">
              <i class="bi bi-plus-lg me-1"></i> Add Field
            </button>
          </div>

          <div id="fields-container">
            @forelse($group->fields as $field)
              <div class="field-row p-3 d-flex align-items-center" data-id="{{ $field->id }}">
                <div class="me-3 text-muted"><i class="bi bi-grip-vertical fs-5"></i></div>
                <div class="flex-grow-1">
                  <div class="fw-bold">{{ $field->name }}</div>
                  <div class="small text-muted"><code>{{ $field->key }}</code> | {{ ucfirst($field->type) }}</div>
                </div>
                <div class="btn-group">
                  <button type="button" class="btn btn-sm btn-light border"><i class="bi bi-pencil"></i></button>
                  <button type="button" class="btn btn-sm btn-light border text-danger"
                    @click="deleteField({{ $field->id }})"><i class="bi bi-trash"></i></button>
                </div>
              </div>
            @empty
              <div class="text-center py-4 text-muted border border-dashed rounded" x-show="fields.length === 0">
                No fields created yet.
              </div>
            @endforelse
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="acf-card bg-white p-4 mb-4">
          <h5 class="fw-bold mb-3">Publish</h5>
          <div class="mb-3">
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" name="is_active" value="1"
                {{ $group->is_active ? 'selected' : '' }} id="statusSwitch">
              <label class="form-check-label" for="statusSwitch">Active</label>
            </div>
          </div>
          <hr>
          <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="{{ route('acf.admin.index') }}" class="btn btn-light text-muted">Cancel</a>
          </div>
        </div>

        <div class="acf-card bg-white p-4">
          <h5 class="fw-bold mb-3">Location Rules</h5>
          <p class="small text-muted mb-3">Select where this group should appear.</p>
          <div class="mb-3">
            <label class="form-label small fw-bold">Post Type</label>
            <select class="form-select form-select-sm">
              <option value="post">Post</option>
              <option value="page">Page</option>
            </select>
          </div>
          <button type="button" class="btn btn-sm btn-outline-secondary w-100">Add Rule</button>
        </div>
      </div>
    </div>
  </form>
@endsection

@push('scripts')
  <script>
    function fieldManager() {
      return {
        fields: [],
        init() {
          const el = document.getElementById('fields-container');
          Sortable.create(el, {
            animation: 150,
            ghostClass: 'bg-light',
            onEnd: (evt) => {
              const order = Array.from(el.children).map(row => row.dataset.id);
              this.updateOrder(order);
            }
          });
        },
        addField() {
          // Logic to open a modal or inline row for new field
          alert('Add field modal coming soon!');
        },
        deleteField(id) {
          if (confirm('Delete this field? Stored values will remain but will be orphaned.')) {
            // AJAX call
            fetch(`{{ url('admin/advanced-custom-fields/fields') }}/${id}`, {
              method: 'DELETE',
              headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
              }
            }).then(() => window.location.reload());
          }
        },
        updateOrder(order) {
          fetch('{{ route('acf.admin.fields.sort') }}', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
              order
            })
          });
        }
      }
    }
  </script>
@endpush
