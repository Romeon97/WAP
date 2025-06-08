@extends('layouts.app')

@section('title', 'Gebruikersbeheer')

@section('content')
    <div class="container">
        <h1 class="page-title">Users</h1>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="form-footer mt-4 text-center">
            <a href="{{ route('register') }}" class="register-button">Create new user</a>
        </div>

        <div class="table-wrapper shadow bg-white p-4 rounded">
            <table class="user-table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>First name</th>
                    <th>Last name</th>
                    <th>Email</th>
                    <th>Employee code</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <form action="{{ route('admin.updateUsers') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <td>{{ $user->id }}</td>
                            <td><input type="text" name="users[{{ $user->id }}][first_name]" value="{{ $user->first_name }}"></td>
                            <td><input type="text" name="users[{{ $user->id }}][name]" value="{{ $user->name }}"></td>
                            <td><input type="email" name="users[{{ $user->id }}][email]" value="{{ $user->email }}"></td>
                            <td><input type="text" name="users[{{ $user->id }}][employee_code]" value="{{ $user->employee_code }}"></td>
                            <td>
                                <select name="users[{{ $user->id }}][user_role]">
                                    @foreach($roles as $role)
                                        @if(auth()->user()->user_role == 6 || !in_array($role->id, [4, 6]))
                                            <option value="{{ $role->id }}" {{ $user->user_role == $role->id ? 'selected' : '' }}>
                                                {{ $role->role }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </td>
                            <td class="action-buttons">
                                <button type="submit" class="save-btn">Save</button>
                        </form>

                        <form action="{{ route('admin.deleteUser', $user->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete-btn" onclick="return confirm('Confirm delete?')">Delete</button>
                        </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>


    </div>
@endsection
