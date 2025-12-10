// FILE: src/services/vendors.ts
import { api } from "@/lib/api";

/** Normalized Vendor type used by React pages */
export type Vendor = {
  id: string;          // backend primary key as string
  name: string;        // vendorName
  code: string;        // vendorCode
  mobile: string;      // vendorMobile
  email: string | null; // vendorEmail
  totalBranch: number; // totalBranch
  isActive: boolean;   // status === 1 / true
};

export type ListVendorsParams = {
  page?: number;
  limit?: number;
  search?: string;
};

export type ListVendorsResult = {
  items: Vendor[];
  total: number;
  page: number;
  limit: number;
};

/** Exact row shape from your /vendors API */
type VendorsApiRow = {
  id: number;
  vendorName: string;
  vendorCode: string;
  vendorMobile: string;
  vendorEmail: string | null;
  totalBranch: number;
  status: number | boolean;
};

/** Optional paginated shape (if backend wraps in { data, meta }) */
type VendorsApiResponse =
  | VendorsApiRow[]
  | {
      data: VendorsApiRow[];
      meta?: {
        total?: number;
        page?: number;
        limit?: number;
        totalPages?: number;
      };
    };

/** Map backend row → frontend Vendor */
function mapRow(row: VendorsApiRow): Vendor {
  return {
    id: String(row.id),
    name: row.vendorName ?? "",
    code: row.vendorCode ?? "",
    mobile: row.vendorMobile ?? "",
    email: row.vendorEmail ?? null,
    totalBranch: row.totalBranch ?? 0,
    isActive: row.status === 1 || row.status === true,
  };
}

/**
 * Fetch vendor list from /vendors and normalize into ListVendorsResult.
 * Works with:
 *   - plain array: [ { ... }, ... ]
 *   - paginated:   { data: [...], meta: {...} }
 */
export async function listVendors(
  params: ListVendorsParams = {}
): Promise<ListVendorsResult> {
  const qs = new URLSearchParams();

  if (params.page != null) qs.set("page", String(params.page));
  if (params.limit != null) qs.set("limit", String(params.limit));
  if (params.search?.trim()) qs.set("search", params.search.trim());

  const path = qs.toString() ? `/vendors?${qs.toString()}` : "/vendors";

  const res = (await api(path)) as VendorsApiResponse;

  const arr: VendorsApiRow[] = Array.isArray(res)
    ? res
    : Array.isArray(res.data)
    ? res.data
    : [];

  const items = arr.map(mapRow);

  const meta = !Array.isArray(res) ? res.meta ?? {} : {};
  const total =
    typeof meta.total === "number" && !Number.isNaN(meta.total)
      ? meta.total
      : items.length;

  const page = meta.page ?? params.page ?? 1;
  const limit =
    meta.limit ?? params.limit ?? (items.length > 0 ? items.length : 10);

  return { items, total, page, limit };
}
