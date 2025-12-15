import { useState } from "react";
import { useNavigate } from "react-router-dom";
import api from "../api/api";
import NavButtons from "../components/NavButtons";

export default function Register() {
  const [name, setName] = useState("");
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState("");
  const navigate = useNavigate();

  const handleRegister = async () => {
    try {
      await api.post("/auth/register", { name, email, password });
      navigate("/login");
    } catch (err) {
      setError(err.response?.data?.error || "Register failed");
    }
  };

  return (
    <div className="container">
      <div className="card">
        <NavButtons />

        <h1>Register</h1>

        <input placeholder="Name" value={name} onChange={(e) => setName(e.target.value)} />
        <input placeholder="Email" value={email} onChange={(e) => setEmail(e.target.value)} />
        <input type="password" placeholder="Password" value={password} onChange={(e) => setPassword(e.target.value)} />

        <button onClick={handleRegister}>Create Account</button>

        {error && <p className="error">{error}</p>}
      </div>
    </div>
  );
}
