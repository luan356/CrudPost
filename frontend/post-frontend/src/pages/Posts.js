import { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import api from "../api/api";

export default function Posts() {
  const [posts, setPosts] = useState([]);
  const [loading, setLoading] = useState(true);
  const navigate = useNavigate();

  useEffect(() => {
    api.get("/posts")
      .then(res => setPosts(res.data))
      .finally(() => setLoading(false));
  }, []);

  const logout = () => {
    localStorage.removeItem("token");
    navigate("/login");
  };

  return (
    <div className="container">
      <div className="card" style={{ maxWidth: "600px" }}>

        <div className="header">
          <button
            className="create-post"
            onClick={() => navigate("/posts/create")}
          >
            Criar Post
          </button>

          <h1>Posts</h1>

          <button className="logout" onClick={logout}>
            Logout
          </button>
        </div>

        {loading && <p>Carregando...</p>}

        {!loading && posts.length === 0 && (
          <p>Nenhum post encontrado.</p>
        )}

        <div className="post-list">
          {posts.map(post => (
            <div
              key={post.id}
              className="post-item"
              onClick={() => navigate(`/posts/${post.id}`)}
            >
              <div className="post-title">{post.title}</div>
              <div className="post-meta">
                {post.author} • {post.created_at}
              </div>
            </div>
          ))}
        </div>

      </div>
    </div>
  );
}
