@extends('layouts.contracts')

@section('title', 'Gebruikersbeheer')

@section('content')
    <div class="container">
        <h1 class="page-title">Users</h1>

        <div class="form-footer mt-4 text-center">
            <a href="/contract/register" class="register-button">Create new user</a>
        </div>

        <div class="table-wrapper shadow bg-white p-4 rounded">
            <table class="user-table">
                <thead>
                <tr>
                    <th>Useridentifier</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Admin</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody id="userList">
                <!-- Gebruikers worden hier via JavaScript geladen -->
                </tbody>
            </table>

            <div id="editFormContainer" style="display:none; margin-top: 40px;">
                <h2>Edit user</h2>
                <form id="editUserForm">
                    <input type="hidden" name="useridentifier">
                    <label>Name: <input type="text" name="name" required></label><br><br>
                    <label>Email: <input type="email" name="email" required></label><br><br>
                    <label>Role: <input type="text" name="role" required></label><br><br>
                    <label>Admin: <input type="checkbox" name="is_admin"></label><br><br>
                    <button type="submit" class="save-btn">Save</button>
                    <button type="button" onclick="hideEditForm()" class="delete-btn">Cancel</button>
                </form>
                <p id="editMessage" style="color:green;"></p>
            </div>
        </div>
    </div>

    <script>
        const user = JSON.parse(sessionStorage.getItem('user'));
        const token = sessionStorage.getItem('token');

        if (!user || !user.is_admin) {
            document.querySelector('.container').innerHTML = '<p style="color:red;">Je hebt geen toegang tot deze pagina.</p>';
        } else {
            loadUsers();
        }

        function loadUsers() {
            fetch(`/api/contracten/${user.contractIdentifier}/users`)
                .then(res => res.json())
                .then(data => {
                    const tbody = document.getElementById('userList');
                    tbody.innerHTML = '';
                    data.forEach(u => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${u.useridentifier}</td>
                            <td>${u.name}</td>
                            <td>${u.email}</td>
                            <td>${u.role}</td>
                            <td>${u.is_admin ? 'Yes' : 'No'}</td>
                            <td>
                                <button onclick="editUser('${u.useridentifier}')" class="save-btn">Edit</button>
                                <button onclick="deleteUser('${u.useridentifier}')" class="delete-btn">Delete</button>
                            </td>
                        `;
                        tbody.appendChild(row);
                    });
                });
        }

        function editUser(useridentifier) {
            fetch(`/api/contracten/${user.contractIdentifier}/user/${useridentifier}`)
                .then(res => res.json())
                .then(u => {
                    const form = document.getElementById('editUserForm');
                    form.useridentifier.value = u.useridentifier;
                    form.name.value = u.name;
                    form.email.value = u.email;
                    form.role.value = u.role;
                    form.is_admin.checked = !!u.is_admin;
                    document.getElementById('editFormContainer').style.display = 'block';
                });
        }

        function hideEditForm() {
            document.getElementById('editFormContainer').style.display = 'none';
        }

        document.getElementById('editUserForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const form = e.target;
            const data = {
                name: form.name.value,
                email: form.email.value,
                role: form.role.value,
                is_admin: form.is_admin.checked ? 1 : 0
            };
            fetch(`/api/contracten/${user.contractIdentifier}/user/${form.useridentifier.value}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-User-Identifier': user.useridentifier
                },
                body: JSON.stringify(data)
            }).then(res => res.json()).then(result => {
                document.getElementById('editMessage').textContent = result.message;
                loadUsers();
                setTimeout(() => hideEditForm(), 1500);
            });
        });

        function deleteUser(useridentifier) {
            if (!confirm(`Confirm delete?`)) return;
            fetch(`/api/contracten/${user.contractIdentifier}/user/${useridentifier}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-User-Identifier': user.useridentifier
                }
            }).then(res => res.json()).then(result => {
                alert(result.message);
                loadUsers();
            });
        }

        function logout() {
            sessionStorage.clear();
            window.location.href = '/contract/login';
        }
    </script>
@endsection
