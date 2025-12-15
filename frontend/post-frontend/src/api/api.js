import axios from "axios";

const api = axios.create({
  baseURL: "http://localhost:8000",
});

export const setToken = (token) => {
  if (token) {
    api.defaults.headers.common["Authorization"] = `Bearer ${token}`;
  } else {
    delete api.defaults.headers.common["Authorization"];
  }
};

const token = localStorage.getItem("token");
if (token) {
  setToken(token);
}

export default api;
