import { useEffect, useState } from "react";
import { useParams, useNavigate } from "react-router-dom";
import api, { setToken } from "../api/api";

export default function PostForm() {
  const { id } = useParams(); // se existir, é edição
  const navigate = useNavigate();

  const [title, setTitle] = useState("");
  const [content, setContent] = useState("");
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState("");

  useEffect(() => {
    const token = localStorage.getItem("token");
    setToken(token);

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
    <div>
      <h1>{id ? "Editar Post" : "Criar Post"}</h1>

      <input
        placeholder="Título"
        value={title}
        onChange={e => setTitle(e.target.value)}
      />

      <textarea
        placeholder="Conteúdo"
        value={content}
        onChange={e => setContent(e.target.value)}
      />

      <button onClick={handleSubmit} disabled={loading}>
        {loading ? "Salvando..." : "Salvar"}
      </button>

      {error && <p style={{ color: "red" }}>{error}</p>}
    </div>
  );
}
