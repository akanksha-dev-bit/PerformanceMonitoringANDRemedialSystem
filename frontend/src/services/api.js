import axios from 'axios';

const BACKEND_URL = import.meta.env.VITE_BACKEND_URL || 'http://localhost:8000';

const api = axios.create({
  baseURL: BACKEND_URL,
  withCredentials: true,
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
    'X-Requested-With': 'XMLHttpRequest',
  },
});

const getCookie = (name) => {
  const value = `; ${document.cookie}`;
  const parts = value.split(`; ${name}=`);
  if (parts.length === 2) return decodeURIComponent(parts.pop().split(';').shift());
  return null;
};

api.interceptors.request.use((config) => {
  const token = getCookie('XSRF-TOKEN');
  if (token) {
    config.headers['X-XSRF-TOKEN'] = token;
  }
  return config;
}, (error) => Promise.reject(error));

let isRedirecting = false;

const PUBLIC_ROUTES = ['/'];

api.interceptors.response.use(
  (response) => response,
  (error) => {
    const status = error.response?.status;
    const currentPath = window.location.pathname;

    const isPublicRoute = PUBLIC_ROUTES.includes(currentPath);
    const isUserEndpoint = error.config?.url?.includes('/api/user');

    if (status === 401 && !isRedirecting && !isPublicRoute && !isUserEndpoint) {
      isRedirecting = true;
      window.location.href = `${BACKEND_URL}/login`;
      return Promise.reject(error);
    }

    return Promise.reject(error);
  }
);

export default api;
