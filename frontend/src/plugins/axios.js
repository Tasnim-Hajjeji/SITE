"use strict";
import axios from "axios";

// Full config:  https://github.com/axios/axios#request-config
const baseURL = process.env.baseURL || process.env.apiUrl || 'http://localhost:8000/api/';

let config = {
  baseURL: baseURL,
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json'
  }
  // timeout: 60 * 1000, // Timeout
  // withCredentials: true, // Check cross-site Access-Control
};

const _axios = axios.create(config);

_axios.interceptors.request.use(
  function(config) {
    // Do something before request is sent
    return config;
  },
  function(error) {
    // Do something with request error
    return Promise.reject(error);
  }
);

// Add a response interceptor
_axios.interceptors.response.use(
  function(response) {
    // Do something with response data
    return response;
  },
  function(error) {
    // Do something with response error
    return Promise.reject(error);
  }
);

export default _axios; // Export the configured instance