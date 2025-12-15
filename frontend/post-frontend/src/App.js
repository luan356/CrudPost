import { BrowserRouter as Router, Routes, Route, Navigate } from "react-router-dom";
import Login from "./pages/Login";
import Register from "./pages/Register";
import Posts from "./pages/Posts";
import PostDetail from "./pages/PostDetail";
import PostForm from "./pages/PostForm";

function App() {
  const token = localStorage.getItem("token");

  return (
    <Router>
      <Routes>
        <Route path="/login" element={<Login />} />
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
