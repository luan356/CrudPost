import { useEffect, useState } from "react";
import { useNavigate, useParams } from "react-router-dom";
import api from "../api/api";

export default function PostForm() {
  const { id } = useParams();
  const navigate = useNavigate();

  const [title, setTitle] = useState("");
  const [content, setContent] = useState("");
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState("");

  useEffect(() => {
    if (id) {
      api.get(`/posts/${id}`)
        .then(res => {
          setTitle(res.data.title);
          setContent(res.data.content);
        })
        .catch(() => setError("Erro ao carregar post"));
    }
  }, [id]);

  const handleSubmit = async () => {
    setLoading(true);
    setError("");

    try {
      if (id) {
        await api.put(`/posts/${id}`, { title, content });
      } else {
        await api.post("/posts", { title, content });
      }
      navigate("/");
    } catch (err) {
      setError(err.response?.data?.error || "Erro ao salvar post");
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="container">
      <div className="card">
        <h1>{id ? "Editar Post" : "Criar Post"}</h1>

        <input
          placeholder="Título"
          value={title}
          onChange={(e) => setTitle(e.target.value)}
        />

        <textarea
          placeholder="Conteúdo"
          value={content}
          onChange={(e) => setContent(e.target.value)}
        />

        {error && <p className="error">{error}</p>}

        <div className="actions">
          <button
            className="secondary"
            onClick={() => navigate(-1)}
          >
            Voltar
          </button>

          <button
            onClick={handleSubmit}
            disabled={loading}
          >
            {loading ? "Salvando..." : "Salvar"}
          </button>
        </div>
      </div>
    </div>
  );
}
