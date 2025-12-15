import { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import api, { setToken } from "../api/api";

export default function Posts() {
  const [posts, setPosts] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const token = localStorage.getItem("token");
    setToken(token);
    api.get("/posts")
      .then(res => setPosts(res.data))
      .finally(() => setLoading(false));
  }, []);

  if (loading) return <p>Loading...</p>;

  return (
    <div>
      <h1>Posts</h1>
      <Link to="/posts/create">Create Post</Link>
      {posts.map(post => (
        <div key={post.id}>
          <Link to={`/posts/${post.id}`}>{post.title}</Link> by {post.author}
        </div>
      ))}
    </div>
  );
}
