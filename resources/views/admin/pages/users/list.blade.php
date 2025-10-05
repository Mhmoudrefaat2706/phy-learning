@extends("admin.pages.app")

@section("title", __("Users Management"))

@section("content")
<div class="container-fluid">
    <div class="row">
        <div class="col-12">

            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold mb-0">{{ __('Users') }}</h4>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createUserModal">
                    <i class="bi bi-plus-lg me-1"></i> {{ __('Create New User') }}
                </button>
            </div>

            <!-- Toast -->
            <div class="position-fixed end-0 top-0 p-3" style="z-index:1100;">
                <div id="liveToast" class="toast align-items-center text-bg-success border-0" role="alert"
                    aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body" id="toastMessage">{{ __('Success') }}</div>
                        <button type="button" class="btn-close btn-close-white m-auto me-2"
                            data-bs-dismiss="toast" aria-label="{{ __('Close') }}"></button>
                    </div>
                </div>
            </div>

{{-- Users Table --}}
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table-hover mb-0 table align-middle" id="usersTable">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width:60px">#</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Email') }}</th>
                        <th>{{ __('Phone') }}</th>
                        <th>{{ __('school') }}</th>
                        <th>{{ __('school_level') }}</th>
                        <th>{{ __('score') }}</th>
                        <th>{{ __('level') }}</th>
                        <th class="text-center" style="width:180px">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $user)
                        <tr id="userRow{{ $user->id }}">
                            <td class="fw-bold text-center">{{ $users->firstItem() + $index }}</td>
                            <td class="user-name">{{ $user->name }}</td>
                            <td class="user-email">{{ $user->email }}</td>
                            <td class="user-phone">{{ $user->phone ?? "-" }}</td>
                            <td class="user-school">{{ $user->school ?? "-" }}</td>
                            <td class="user-school_level">{{ $user->school_level ?? "-" }}</td>
                            <td class="user-score">{{ $user->score ?? "-" }}</td>
                            <td class="user-level">{{ $user->level->name ?? "-" }}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-secondary edit-user"
                                    data-id="{{ $user->id }}">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger delete-user"
                                    data-id="{{ $user->id }}">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-muted text-center">{{ __('No users found.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    @if ($users->hasPages())
        <div class="card-footer d-flex justify-content-center">
            {{ $users->links("pagination::bootstrap-5") }}
        </div>
    @endif
</div>


        </div>
    </div>
</div>

{{-- ==== Create User Modal ==== --}}
<div class="modal fade" id="createUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Create New User') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="createUserForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">{{ __('Name') }}</label>
                        <input type="text" class="form-control" id="createUserName" required>
                        <div class="invalid-feedback" id="createUserNameError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Email') }}</label>
                        <input type="email" class="form-control" id="createUserEmail" required>
                        <div class="invalid-feedback" id="createUserEmailError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Phone') }}</label>
                        <input type="text" class="form-control" id="createUserPhone">
                        <div class="invalid-feedback" id="createUserPhoneError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('school') }}</label>
                        <input type="text" class="form-control" id="createUserschool">
                        <div class="invalid-feedback" id="createUserschoolError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('school_level') }}</label>
                        <input type="text" class="form-control" id="createUserschool_level">
                        <div class="invalid-feedback" id="createUserschool_levelError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Password') }}</label>
                        <input type="password" class="form-control" id="createUserPassword" required>
                        <div class="invalid-feedback" id="createUserPasswordError"></div>
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

{{-- ==== Edit User Modal ==== --}}
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Edit User') }} - <span id="modalUserName"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editUserForm">
                    @csrf
                    @method("PUT")
                    <input type="hidden" id="modalUserId">
                    <div class="mb-3">
                        <label class="form-label">{{ __('Name') }}</label>
                        <input type="text" class="form-control" id="userNameInput" required>
                        <div class="invalid-feedback" id="userNameInputError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Email') }}</label>
                        <input type="email" class="form-control" id="userEmailInput" required>
                        <div class="invalid-feedback" id="userEmailInputError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Phone') }}</label>
                        <input type="text" class="form-control" id="userPhoneInput">
                        <div class="invalid-feedback" id="userPhoneInputError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('school') }}</label>
                        <input type="text" class="form-control" id="userschoolInput">
                        <div class="invalid-feedback" id="userschoolInputError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('school_level') }}</label>
                        <input type="text" class="form-control" id="userschool_levelInput">
                        <div class="invalid-feedback" id="userschool_levelInputError"></div>
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

{{-- ==== Delete User Modal ==== --}}
<div class="modal fade" id="deleteUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header border-0">
                <h5 class="modal-title text-danger">{{ __('Confirm Delete') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>{{ __('Are you sure you want to delete') }} <strong id="deleteUserName"></strong>?</p>
                <input type="hidden" id="deleteUserId">
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteUserBtn">{{ __('Delete') }}</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push("scripts")
<script>
document.addEventListener('DOMContentLoaded', function() {
    const createForm = document.getElementById('createUserForm');
    const editForm   = document.getElementById('editUserForm');

    const createModalEl = document.getElementById('createUserModal');
    const editModalEl   = document.getElementById('editUserModal');
    const deleteModalEl = document.getElementById('deleteUserModal');

    const createModal = bootstrap.Modal.getOrCreateInstance(createModalEl);
    const editModal   = bootstrap.Modal.getOrCreateInstance(editModalEl);
    const deleteModal = bootstrap.Modal.getOrCreateInstance(deleteModalEl);

    const toastEl    = document.getElementById('liveToast');
    const toastMsg   = document.getElementById('toastMessage');
    const toast      = new bootstrap.Toast(toastEl);

    function showToast(message){
        toastMsg.textContent = message;
        toast.show();
    }

    // === Create ===
    createForm.addEventListener('submit', function(e){
        e.preventDefault();
        ['createUserName','createUserEmail','createUserPhone','createUserschool','createUserschool_level','createUserPassword'].forEach(id=>{
            document.getElementById(id).classList.remove('is-invalid');
            document.getElementById(id+'Error').textContent='';
        });

        const name     = document.getElementById('createUserName').value.trim();
        const email    = document.getElementById('createUserEmail').value.trim();
        const phone    = document.getElementById('createUserPhone').value.trim();
        const school   = document.getElementById('createUserschool').value.trim();
        const school_level = document.getElementById('createUserschool_level').value.trim();
        const password = document.getElementById('createUserPassword').value.trim();

        fetch("{{ route('admin.users.store') }}", {
            method:'POST',
            headers:{
                'Content-Type':'application/json',
                'Accept':'application/json',
                'X-CSRF-TOKEN':'{{ csrf_token() }}'
            },
            body:JSON.stringify({name,email,phone,school,school_level,password})
        })
        .then(res=>res.json())
        .then(data=>{
            if(data.success){
                const user  = data.user;
                const table = document.querySelector('#usersTable tbody');
                const index = table.rows.length;
                const row   = document.createElement('tr');
                row.id = `userRow${user.id}`;
                row.innerHTML = `
                    <td class="text-center fw-bold">${index+1}</td>
                    <td class="user-name">${user.name}</td>
                    <td class="user-email">${user.email}</td>
                    <td class="user-phone">${user.phone ?? '-'}</td>
                    <td class="user-school">${user.school ?? '-'}</td>
                    <td class="user-school_level">${user.school_level ?? '-'}</td>
                    <td class="user-score">${user.score ?? '-'}</td>
                    <td class="user-level">${user.level?.name ?? '-'}</td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-outline-secondary edit-user" data-id="${user.id}">
                            <i class="bi bi-pencil-fill"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger delete-user" data-id="${user.id}">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    </td>`;
                table.appendChild(row);
                createModal.hide();
                createForm.reset();
                setTimeout(()=> showToast('User created successfully'), 300);
            }else if(data.errors){
                Object.keys(data.errors).forEach(key=>{
                    const camel = key.charAt(0).toUpperCase() + key.slice(1);
                    const input = document.getElementById('createUser'+camel);
                    const error = document.getElementById('createUser'+camel+'Error');
                    if(input && error){
                        input.classList.add('is-invalid');
                        error.textContent = data.errors[key][0];
                    }
                });
            }
        });
    });

    // === Edit & Delete openers ===
    document.addEventListener('click', function(e){
        if(e.target.closest('.edit-user')){
            const btn  = e.target.closest('.edit-user');
            const id   = btn.dataset.id;
            const row  = document.getElementById(`userRow${id}`);
            const name = row.querySelector('.user-name').textContent;
            const email= row.querySelector('.user-email').textContent;
            const phone= row.querySelector('.user-phone').textContent;
            const school = row.querySelector('.user-school')?.textContent ?? '';
            const school_level = row.querySelector('.user-school_level')?.textContent ?? '';

            document.getElementById('modalUserId').value = id;
            document.getElementById('modalUserName').textContent = name;
            document.getElementById('userNameInput').value = name;
            document.getElementById('userEmailInput').value = email;
            document.getElementById('userPhoneInput').value = phone === '-' ? '' : phone;
            document.getElementById('userschoolInput').value = school === '-' ? '' : school;
            document.getElementById('userschool_levelInput').value = school_level === '-' ? '' : school_level;

            editModal.show();
        }

        if(e.target.closest('.delete-user')){
            const btn  = e.target.closest('.delete-user');
            const id   = btn.dataset.id;
            const row  = document.getElementById(`userRow${id}`);
            const name = row.querySelector('.user-name').textContent;

            document.getElementById('deleteUserId').value = id;
            document.getElementById('deleteUserName').textContent = name;
            deleteModal.show();
        }
    });

    // === Update ===
    editForm.addEventListener('submit', function(e){
        e.preventDefault();
        ['userNameInput','userEmailInput','userPhoneInput','userschoolInput','userschool_levelInput'].forEach(id=>{
            document.getElementById(id).classList.remove('is-invalid');
            document.getElementById(id+'Error').textContent='';
        });

        const id    = document.getElementById('modalUserId').value;
        const name  = document.getElementById('userNameInput').value.trim();
        const email = document.getElementById('userEmailInput').value.trim();
        const phone = document.getElementById('userPhoneInput').value.trim();
        const school = document.getElementById('userschoolInput').value.trim();
        const school_level = document.getElementById('userschool_levelInput').value.trim();

        fetch(`/admin/users/${id}`,{
            method:'PUT',
            headers:{
                'Content-Type':'application/json',
                'Accept':'application/json',
                'X-CSRF-TOKEN':'{{ csrf_token() }}'
            },
            body:JSON.stringify({name,email,phone,school,school_level})
        })
        .then(res=>res.json())
        .then(data=>{
            if(data.success){
                const row = document.getElementById(`userRow${id}`);
                row.querySelector('.user-name').textContent  = data.user.name;
                row.querySelector('.user-email').textContent = data.user.email;
                row.querySelector('.user-phone').textContent = data.user.phone ?? '-';
                row.querySelector('.user-school').textContent = data.user.school ?? '-';
                row.querySelector('.user-school_level').textContent = data.user.school_level ?? '-';
                editModal.hide();
                setTimeout(()=> showToast('User updated successfully'), 300);
            }else if(data.errors){
                const map = {
                    name:'userNameInput',
                    email:'userEmailInput',
                    phone:'userPhoneInput',
                    school:'userschoolInput',
                    school_level:'userschool_levelInput'
                };
                Object.keys(data.errors).forEach(key=>{
                    const input = document.getElementById(map[key]);
                    const error = document.getElementById(map[key]+'Error');
                    if(input && error){
                        input.classList.add('is-invalid');
                        error.textContent = data.errors[key][0];
                    }
                });
            }
        });
    });

    // Delete confirm
    document.getElementById('confirmDeleteUserBtn').addEventListener('click', function(){
        const id = document.getElementById('deleteUserId').value;
        fetch(`/admin/users/${id}`,{
            method:'DELETE',
            headers:{ 'X-CSRF-TOKEN':'{{ csrf_token() }}' }
        })
        .then(res=>res.json())
        .then(data=>{
            if(data.success){
                document.getElementById(`userRow${id}`).remove();
                deleteModal.hide();
                setTimeout(()=> showToast('User deleted successfully'), 300);
            }
        });
    });
});
</script>
@endpush
