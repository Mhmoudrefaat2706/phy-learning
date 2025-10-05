@extends('admin.pages.app')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold mb-0">{{ __('Roles') }}</h4>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createRoleModal">
                    <i class="bi bi-plus-lg me-1"></i> {{ __('Create New Role') }}
                </button>
            </div>

            <!-- Toast container -->
            <div class="position-fixed top-0 end-0 p-3" style="z-index: 1100;">
                <div id="liveToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body" id="toastMessage">
                            {{ __('Success message') }}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="{{ __('Close') }}"></button>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="rolesTable">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" style="width:60px">#</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Description') }}</th>
                                    <th>{{ __('Permissions') }}</th>
                                    <th class="text-center" style="width:180px">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($roles as $index => $role)
                                    <tr id="roleRow{{ $role->id }}">
                                        <td class="text-center fw-bold">{{ $index+1 }}</td>
                                        <td class="role-name">{{ $role->name }}</td>
                                        <td class="role-desc">{{ $role->description }}</td>
                                        <td class="role-perms">
                                            @foreach($role->permissions as $perm)
                                                <span class="badge bg-info text-dark">{{ $perm->name }}</span>
                                            @endforeach
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-secondary edit-role" data-id="{{ $role->id }}">
                                                <i class="bi bi-pencil-fill"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger delete-role" data-id="{{ $role->id }}">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-3 px-5">
                            {{ $roles->links("pagination::bootstrap-5") }}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- CREATE ROLE MODAL --}}
<div class="modal fade" id="createRoleModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Create New Role') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="createRoleForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">{{ __('Name') }}</label>
                        <input type="text" class="form-control" id="createRoleName">
                        <div class="invalid-feedback" id="createRoleNameError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Description') }}</label>
                        <input type="text" class="form-control" id="createRoleDesc">
                        <div class="invalid-feedback" id="createRoleDescError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Permissions') }}</label>
                        <select multiple class="form-select" id="createRolePermissions">
                            @foreach($permissions as $perm)
                                <option value="{{ $perm->id }}">{{ $perm->name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback d-block" id="createRolePermsError"></div>
                        <small class="text-muted">{{ __('Hold Ctrl or Shift to select multiple') }}</small>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-success">{{ __('Create') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- EDIT ROLE MODAL --}}
<div class="modal fade" id="editRoleModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Edit Role') }} - <span id="modalRoleName"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editRoleForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="modalRoleId">
                    <div class="mb-3">
                        <label class="form-label">{{ __('Name') }}</label>
                        <input type="text" class="form-control" id="roleNameInput">
                        <div class="invalid-feedback" id="roleNameInputError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Description') }}</label>
                        <input type="text" class="form-control" id="roleDescInput">
                        <div class="invalid-feedback" id="roleDescInputError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Permissions') }}</label>
                        <select multiple class="form-select" id="rolePermissionsInput">
                            @foreach($permissions as $perm)
                                <option value="{{ $perm->id }}">{{ $perm->name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback d-block" id="rolePermsInputError"></div>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Save Changes') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- DELETE ROLE MODAL --}}
<div class="modal fade" id="deleteRoleModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header border-0">
                <h5 class="modal-title text-danger">{{ __('Confirm Delete') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>{{ __('Are you sure you want to delete') }} <strong id="deleteRoleName"></strong>?</p>
                <input type="hidden" id="deleteRoleId">
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteRoleBtn">{{ __('Delete') }}</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const createForm = document.getElementById('createRoleForm');
    const editForm = document.getElementById('editRoleForm');
    const createModal = new bootstrap.Modal(document.getElementById('createRoleModal'));
    const editModal = new bootstrap.Modal(document.getElementById('editRoleModal'));
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteRoleModal'));
    const toastEl = document.getElementById('liveToast');
    const toastMessage = document.getElementById('toastMessage');
    const toast = new bootstrap.Toast(toastEl);

    // --- Helper Functions ---
    function clearErrors(prefix) {
        ['Name','Desc','Perms'].forEach(f=>{
            const el = document.getElementById(prefix+f+'Error');
            if(el){
                el.textContent='';
                if(f!=='Perms') el.previousElementSibling.classList.remove('is-invalid');
            }
        });
    }

    function showErrors(errors, prefix){
        for(const key in errors){
            const el = document.getElementById(prefix + key.charAt(0).toUpperCase() + key.slice(1) + (key==='permissions'?'sError':'Error'));
            if(el){
                el.textContent = errors[key].join(', ');
                if(key!=='permissions') el.previousElementSibling.classList.add('is-invalid');
            }
        }
    }

    function showToast(message) {
        toastMessage.textContent = message;
        toast.show();
    }

    function addRoleRow(role) {
        const table = document.querySelector('#rolesTable tbody');
        const index = table.rows.length;
        const row = document.createElement('tr');
        row.id = `roleRow${role.id}`;
        row.innerHTML = `
            <td class="text-center fw-bold">${index+1}</td>
            <td class="role-name">${role.name}</td>
            <td class="role-desc">${role.description ?? ''}</td>
            <td class="role-perms">${role.permissions.map(p=>`<span class="badge bg-info text-dark">${p.name}</span>`).join(' ')}</td>
            <td class="text-center">
                <button class="btn btn-sm btn-outline-secondary edit-role" data-id="${role.id}"><i class="bi bi-pencil-fill"></i></button>
                <button class="btn btn-sm btn-outline-danger delete-role" data-id="${role.id}"><i class="bi bi-trash-fill"></i></button>
            </td>`;
        table.appendChild(row);
    }

    // --- CREATE ROLE ---
    createForm.addEventListener('submit', function(e){
        e.preventDefault();
        clearErrors('createRole');

        const name = document.getElementById('createRoleName').value.trim();
        const description = document.getElementById('createRoleDesc').value.trim();
        const permissions = Array.from(document.getElementById('createRolePermissions').selectedOptions).map(o=>o.value);

        fetch("{{ route('admin.roles.store') }}", {
            method:'POST',
            headers:{
                'Content-Type':'application/json',
                'X-CSRF-TOKEN':'{{ csrf_token() }}',
                'Accept':'application/json'
            },
            body: JSON.stringify({name, description, permissions})
        })
        .then(res => res.json())
        .then(data => {
            if(data.success){
                addRoleRow(data.role);
                createModal.hide();
                createForm.reset();
                showToast('Role created successfully!');
            } else if(data.errors){
                showErrors(data.errors,'createRole');
            }
        });
    });

    // --- EDIT & DELETE BUTTONS ---
    document.addEventListener('click', function(e){
        const btn = e.target.closest('button');

        // --- EDIT ---
        if(btn && btn.classList.contains('edit-role')){
            const id = btn.dataset.id;
            const row = document.getElementById(`roleRow${id}`);
            document.getElementById('modalRoleId').value = id;
            document.getElementById('modalRoleName').textContent = row.querySelector('.role-name').textContent;
            document.getElementById('roleNameInput').value = row.querySelector('.role-name').textContent;
            document.getElementById('roleDescInput').value = row.querySelector('.role-desc').textContent;

            const perms = Array.from(row.querySelectorAll('.role-perms .badge')).map(b=>b.textContent);
            const select = document.getElementById('rolePermissionsInput');
            Array.from(select.options).forEach(opt=> opt.selected = perms.includes(opt.textContent));

            clearErrors('role');
            editModal.show();
        }

        // --- DELETE ---
        if(btn && btn.classList.contains('delete-role')){
            const id = btn.dataset.id;
            const row = document.getElementById(`roleRow${id}`);
            document.getElementById('deleteRoleId').value = id;
            document.getElementById('deleteRoleName').textContent = row.querySelector('.role-name').textContent;
            deleteModal.show();
        }
    });

    // --- UPDATE ROLE ---
    editForm.addEventListener('submit', function(e){
        e.preventDefault();
        clearErrors('role');

        const id = document.getElementById('modalRoleId').value;
        const name = document.getElementById('roleNameInput').value.trim();
        const description = document.getElementById('roleDescInput').value.trim();
        const permissions = Array.from(document.getElementById('rolePermissionsInput').selectedOptions).map(o=>o.value);

        fetch(`/admin/roles/${id}`, {
            method:'PUT',
            headers:{
                'Content-Type':'application/json',
                'X-CSRF-TOKEN':'{{ csrf_token() }}',
                'Accept':'application/json'
            },
            body: JSON.stringify({name, description, permissions})
        })
        .then(res => res.json())
        .then(data => {
            if(data.success){
                const row = document.getElementById(`roleRow${id}`);
                row.querySelector('.role-name').textContent = data.role.name;
                row.querySelector('.role-desc').textContent = data.role.description ?? '';
                row.querySelector('.role-perms').innerHTML = data.role.permissions.map(p=>`<span class="badge bg-info text-dark">${p.name}</span>`).join(' ');
                editModal.hide();
                showToast('Role updated successfully!');
            } else if(data.errors){
                showErrors(data.errors,'role');
            }
        });
    });

    // --- DELETE ROLE ---
    document.getElementById('confirmDeleteRoleBtn').addEventListener('click', function(){
        const id = document.getElementById('deleteRoleId').value;
        fetch(`/admin/roles/${id}`, {
            method:'DELETE',
            headers:{
                'X-CSRF-TOKEN':'{{ csrf_token() }}',
                'Accept':'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if(data.success){
                document.getElementById(`roleRow${id}`).remove();
                deleteModal.hide();
                showToast('Role deleted successfully!');
            }
        });
    });

});

</script>
@endpush


