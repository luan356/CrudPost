import { useNavigate } from "react-router-dom";

export default function NavButtons() {
  const navigate = useNavigate();

  return (
    <div className="nav-buttons">
      <button className="secondary" onClick={() => navigate("/")}>
        Home
      </button>
      <button className="secondary" onClick={() => navigate(-1)}>
        Voltar
      </button>
    </div>
  );
}
