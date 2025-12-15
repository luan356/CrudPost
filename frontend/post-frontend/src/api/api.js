import axios from "axios";

const API_URL = "http://localhost:8000"; 
const api = axios.create({
  baseURL: API_URL,
});

export const setToken = (token) => {
  api.defaults.headers.common["Authorization"] = `Bearer ${token}`;
};

export default api;
