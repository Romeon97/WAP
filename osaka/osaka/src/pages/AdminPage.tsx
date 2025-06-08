import { useEffect, useState } from "react";
import axios from "axios";

interface User {
    id: number;
    first_name: string;
    infix?: string;
    last_name: string;
    email: string;
    password?: string;
    is_admin: boolean;
}

function AdminPage() {
    const [users, setUsers] = useState<User[]>([]);
    const [form, setForm] = useState({
        first_name: "",
        infix: "",
        last_name: "",
        email: "",
        password: "",
        is_admin: false,
    });
    const [editId, setEditId] = useState<number | null>(null);

    useEffect(() => {
        axios.get<User[]>("http://127.0.0.1:8000/api/users").then((res) => {
            setUsers(res.data);
        });
    }, []);

    const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const { name, value, type, checked } = e.target;
        setForm((prev) => ({
            ...prev,
            [name]: type === "checkbox" ? checked : value,
        }));
    };

    const handleDelete = async (id: number) => {
        try {
            await axios.delete(`http://127.0.0.1:8000/api/users/${id}`);
            setUsers(users.filter((user) => user.id !== id));
        } catch (err: any) {
            alert("Error deleting user: " + (err.response?.data?.error || err.message));
        }
    };

    const handleEdit = (user: User) => {
        setForm({
            first_name: user.first_name,
            infix: user.infix || "",
            last_name: user.last_name,
            email: user.email,
            password: "",
            is_admin: !!user.is_admin,
        });
        setEditId(user.id);
    };

    const handleUpdate = async () => {
        if (editId === null) return;

        try {
            await axios.put(`http://127.0.0.1:8000/api/users/${editId}`, form);
            const updatedUsers = users.map((user) =>
                user.id === editId ? { ...user, ...form } : user
            );
            setUsers(updatedUsers);
            resetForm();
        } catch (err: any) {
            alert("Error updating user: " + (err.response?.data?.error || err.message));
        }
    };

    const handleSubmit = async () => {
        if (!form.first_name || !form.last_name || !form.email || !form.password) {
            alert("Please fill in all required fields");
            return;
        }

        try {
            const res = await axios.post("http://127.0.0.1:8000/api/users", form);
            setUsers([...users, res.data]);
            resetForm();
        } catch (err: any) {
            alert("Error adding user: " + (err.response?.data?.error || err.message));
        }
    };

    const resetForm = () => {
        setForm({
            first_name: "",
            infix: "",
            last_name: "",
            email: "",
            password: "",
            is_admin: false,
        });
        setEditId(null);
    };

    return (
        <div>
            <h1>Users</h1>

            <input name="first_name" value={form.first_name} onChange={handleChange} placeholder="First Name" />
            <input name="infix" value={form.infix} onChange={handleChange} placeholder="Infix (optional)" />
            <input name="last_name" value={form.last_name} onChange={handleChange} placeholder="Last Name" />
            <input name="email" value={form.email} onChange={handleChange} placeholder="Email" />
            <input name="password" type="password" value={form.password} onChange={handleChange} placeholder="Password" />
            <label>
                Admin:
                <input name="is_admin" type="checkbox" checked={form.is_admin} onChange={handleChange} />
            </label>

            {editId ? (
                <button onClick={handleUpdate}>Update User</button>
            ) : (
                <button onClick={handleSubmit}>Add User</button>
            )}

            <ul>
                {users.map((user) => (
                    <li key={user.id}>
                        {user.first_name} {user.infix || ""} {user.last_name} - {user.email}{" "}
                        {user.is_admin ? "(Admin)" : ""}
                        <button onClick={() => handleEdit(user)}>bewerk</button>
                        <button onClick={() => handleDelete(user.id)}>verwijder</button>
                    </li>
                ))}
            </ul>
        </div>
    );
}

export default AdminPage;
