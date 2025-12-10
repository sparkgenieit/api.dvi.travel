// src/services/auth.ts
import { api, setToken, clearToken } from "@/lib/api";

export async function login(email: string, password: string) {
  const data = await api("/auth/login", {
    method: "POST",
    auth: false,
    body: { email, password },
  });
  if (data?.accessToken) {
    setToken(data.accessToken);
  }
  return data;
}

export function logout() {
  clearToken();
}
