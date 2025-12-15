import { BrowserRouter as Router, Routes, Route, Navigate } from "react-router-dom";
import { useState, useEffect } from "react";

import Login from "./pages/Login";
import Register from "./pages/Register";
import Posts from "./pages/Posts";
import PostDetail from "./pages/PostDetail";
import PostForm from "./pages/PostForm";

function App() {
  const [token, setToken] = useState(null);

  useEffect(() => {
    const storedToken = localStorage.getItem("token");
    if (storedToken) {
      setToken(storedToken);
    }
  }, []);

  return (
    <Router>
      <Routes>
        <Route path="/login" element={<Login setAuthToken={setToken} />} />
        <Route path="/register" element={<Register />} />

        <Route
          path="/"
          element={token ? <Posts /> : <Navigate to="/login" />}
        />

        <Route path="/posts/:id" element={<PostDetail />} />
        <Route path="/posts/edit/:id" element={<PostForm />} />
        <Route path="/posts/create" element={<PostForm />} />
      </Routes>
    </Router>
  );
}

export default App;
