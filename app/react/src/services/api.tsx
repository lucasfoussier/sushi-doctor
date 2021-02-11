import axios from 'axios';

const baseUrl = 'http://localhost:3000/api';

// request interceptor to add the auth token header to requests
axios.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('token');
    if (token) {
      config.headers['Authorization'] = 'Bearer '+token;
    }
    return config;
  },
  (error) => {
    Promise.reject(error);
  },
);

// response interceptor to refresh token on receiving token expired error
axios.interceptors.response.use(
  (response) => response,
  (error) => {
    const originalRequest = error.config;
    const refresh_token = localStorage.getItem('refresh_token');

    if (
      refresh_token
            && error.response.status === 401
            && !originalRequest._retry
    ) {
      originalRequest._retry = true;
      return axios
        .post(`${baseUrl}/token/refresh`, { refresh_token })
        .then((res) => {
          if (res.status === 200) {
              console.log(res.data);
            localStorage.setItem('token', res.data.token);
            console.log('Access token refreshed!');
            return axios(originalRequest);
          }
        });
    }
    return Promise.reject(error);
  },
);

// functions to make api calls

const api = {
  signup: (body) => axios.post(`${baseUrl}/test`, body),
  login: (body) => axios.post(`${baseUrl}/login_check`, body),
  refresh_token: (body) => axios.post(`${baseUrl}/token/refresh`, body),
  logout: (body) => axios.delete(`${baseUrl}/auth/logout`, body),
  getProtected: () => axios.get(`${baseUrl}`),
};

export default api;
