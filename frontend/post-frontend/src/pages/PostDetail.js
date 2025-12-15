import { useEffect, useState } from "react";
import { useNavigate, useParams } from "react-router-dom";
import api from "../api/api";

export default function PostDetail() {
  const { id } = useParams();
  const navigate = useNavigate();
  const [post, setPost] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");

  useEffect(() => {
    api.get(`/posts/${id}`)
      .then(res => setPost(res.data))
      .catch(() => setError("Erro ao carregar post"))
      .finally(() => setLoading(false));
  }, [id]);

  const handleDelete = async () => {
    if (!window.confirm("Deseja realmente excluir este post?")) return;

    try {
      await api.delete(`/posts/${id}`);
      navigate("/");
    } catch (err) {
      setError(err.response?.data?.error || "Erro ao excluir post");
    }
  };

  if (loading) {
    return (
      <div className="container">
        <div className="card">Carregando...</div>
      </div>
    );
  }

  if (!post) {
    return (
      <div className="container">
        <div className="card">Post não encontrado</div>
      </div>
    );
  }

  return (
    <div className="container">
      <div className="card" style={{ maxWidth: "600px" }}>
        <h1>{post.title}</h1>
        <p>{post.content}</p>

        {error && <p className="error">{error}</p>}

        <div className="actions">
          <button
            className="secondary"
            onClick={() => navigate(-1)}
          >
            Voltar
          </button>

          <button
            onClick={() => navigate(`/posts/edit/${id}`)}
          >
            Editar
          </button>

          <button
            className="danger"
            onClick={handleDelete}
          >
            Excluir
          </button>
        </div>
      </div>
    </div>
  );
}
