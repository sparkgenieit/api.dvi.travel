
/// <reference types="vite/client" />

/**
 * Reads Vite environment variable correctly and safely.
 * Make sure .env (at project root) contains:
 * VITE_API_DVI_BASE_URL=https://dvi.versile.in
 */
const RAW_FROM_ENV = (import.meta.env.VITE_API_DVI_BASE_URL ?? "").trim();
export const RAW_API_BASE = RAW_FROM_ENV || "https://dvi.versile.in";
console.log('[API_BASE_URL]', RAW_API_BASE);

/** Normalize base URL (append /api/v1 if missing). */
function normalizeBase(base: string) {
  base = base.replace(/\/+$/, ""); // remove trailing slash
  if (!/\/api\/v1$/i.test(base)) base = `${base}/api/v1`;
  return base;
}

export const API_BASE_URL = normalizeBase(RAW_API_BASE);

type ApiOptions = {
  method?: string;
  auth?: boolean; // default true
  headers?: Record<string, string>;
  body?: Record<string, unknown> | string | FormData | Blob | ArrayBuffer | null | undefined; // if object, will JSON.stringify (except FormData/Blob/ArrayBuffer)
};

/** Token helpers */
export function getToken() {
  return localStorage.getItem("accessToken") || "";
}
export function setToken(token: string) {
  localStorage.setItem("accessToken", token);
}
export function clearToken() {
  localStorage.removeItem("accessToken");
}

/** Build full URL. If an absolute URL is passed, use it as-is. */
function buildUrl(path: string) {
  if (/^https?:\/\//i.test(path)) return path;
  const p = path.startsWith("/") ? path : `/${path}`;
  return `${API_BASE_URL}${p}`;
}

/** Universal API function */
export async function api(path: string, opts: ApiOptions = {} ) {
  const { method = "GET", auth = true, headers = {}, body } = opts;
console.debug("[api]", method, buildUrl(path));
  const isFormLike =
    (typeof FormData !== "undefined" && body instanceof FormData) ||
    (typeof Blob !== "undefined" && body instanceof Blob) ||
    (typeof ArrayBuffer !== "undefined" && body instanceof ArrayBuffer);

  const h: Record<string, string> = {
    ...(!isFormLike && body ? { "Content-Type": "application/json" } : {}),
    ...headers,
  };

  if (auth) {
    const t = getToken();
    if (t) h["Authorization"] = `Bearer ${t}`;
  }

  const url = buildUrl(path);
  let finalBody: BodyInit | null = null;
  if (body) {
    if (isFormLike) {
      finalBody = body as BodyInit;
    } else if (typeof body === "object") {
      finalBody = JSON.stringify(body);
    } else {
      finalBody = body as BodyInit;
    }
  }
  const res = await fetch(url, {
    method,
    headers: h,
    body: finalBody,
  });

  if (!res.ok) {
    const text = await res.text().catch(() => "");
    throw new Error(
      `API ${method} ${url} failed: ${res.status} ${res.statusText} ${text}`.trim()
    );
  }

  const ct = res.headers.get("content-type") || "";
  if (ct.includes("application/json")) return res.json();
  return res.text();
}
