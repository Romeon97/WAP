import { useState } from "react";
import axios from "axios";
import { useNavigate } from "react-router-dom";

function LoginPage() {
    const [email, setEmail] = useState("");
    const [password, setPassword] = useState("");
    const [error, setError] = useState("");
    const navigate = useNavigate();

    const handleLogin = async () => {
        try {
            const res = await axios.post("http://127.0.0.1:8000/api/login", {
                email,
                password,
            });

            if (res.status === 200) {
                setError("");
                navigate("/admin");
            }
        } catch {
            setError("Ongeldige inloggegevens.");
        }
    };

    return (
        <div style={{ margin: "2rem" }}>
            <h1>Login</h1>
            <div>
                <input
                    type="email"
                    placeholder="Email"
                    value={email}
                    onChange={(e) => setEmail(e.target.value)}
                    style={{ margin: "0.5rem", padding: "0.5rem" }}
                />
            </div>
            <div>
                <input
                    type="password"
                    placeholder="Wachtwoord"
                    value={password}
                    onChange={(e) => setPassword(e.target.value)}
                    style={{ margin: "0.5rem", padding: "0.5rem" }}
                />
            </div>
            <div>
                <button onClick={handleLogin} style={{ padding: "0.5rem 1rem" }}>
                    Inloggen
                </button>
            </div>
            {error && <p style={{ color: "red" }}>{error}</p>}
        </div>
    );
}

export default LoginPage;
