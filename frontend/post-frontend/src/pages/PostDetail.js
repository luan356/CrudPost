import { useEffect, useState } from "react";
import { useParams, useNavigate, Link } from "react-router-dom";
import api, { setToken } from "../api/api";

export default function PostDetail() {
  const { id } = useParams(); // pega o :id da URL
  const navigate = useNavigate();

  const [post, setPost] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");

  useEffect(() => {
    const token = localStorage.getItem("token");
    setToken(token);

    api.get(`/posts/${id}`)
      .then(res => setPost(res.data))
      .catch(err => {
        setError(err.response?.data?.error || "Erro ao carregar post");
      })
      .finally(() => setLoading(false));
  }, [id]);

  const handleDelete = async () => {
    if (!window.confirm("Tem certeza que deseja excluir este post?")) return;

    try {
      await api.delete(`/posts/${id}`);
      navigate("/");
    } catch (err) {
      alert(err.response?.data?.error || "Erro ao excluir post");
    }
  };

  if (loading) return <p>Carregando...</p>;
  if (error) return <p style={{ color: "red" }}>{error}</p>;

  return (
    <div>
      <h1>{post.title}</h1>
      <p><strong>Autor:</strong> {post.author}</p>
      <p>{post.content}</p>

      <Link to={`/posts/edit/${id}`}>Editar</Link>
      <br />
      <button onClick={handleDelete}>Excluir</button>
    </div>
  );
}
