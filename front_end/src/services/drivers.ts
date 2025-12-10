// REPLACE-WHOLE-FILE: src/services/drivers.ts

import { api } from "@/lib/api";

/* =========================================================
   EXISTING LIST PAGE TYPES + FUNCTIONS (DO NOT CHANGE)
   ========================================================= */

export type Driver = {
  id: number;
  name: string;
  mobile: string;
  licenseNumber: string;
  licenseExpiryDate: string | null;
  licenseStatus: string;
  status: boolean;
};

export async function listDrivers(): Promise<Driver[]> {
  const res = await api("/drivers");
  // Backend already sends in correct shape
  return res as Driver[];
}

export async function updateDriverStatus(id: number, status: boolean) {
  await api(`/drivers/${id}/status`, {
    method: "PATCH",
    body: { status },
  });
}

export async function deleteDriver(id: number) {
  await api(`/drivers/${id}`, {
    method: "DELETE",
  });
}

/* =========================================================
   NEW: ADD DRIVER WIZARD / FORM SERVICE APIs
   (adds capabilities without breaking existing list APIs)
   ========================================================= */

export type Id = number | string;

export type Option = { id: Id; label: string };

export type DriverBasicInfo = {
  vendorId: Id | "";
  vehicleTypeId: Id | "";
  driverName: string;
  primaryMobile: string;
  alternativeMobile?: string;
  whatsappMobile?: string;
  email?: string;

  licenseNumber?: string;
  licenseIssueDate?: string; // ISO
  licenseExpireDate?: string; // ISO
  dateOfBirth?: string; // ISO

  bloodGroup?: string;
  gender?: string;

  aadharNumber?: string;
  panNumber?: string;
  voterId?: string;

  address?: string;

  // File upload
  profileFile?: File | null; // local only
  profileUrl?: string; // server url
};

export type DriverCostDetails = {
  driverSalary: number | "";
  foodCost: number | "";
  accommodationCost: number | "";
  bhattaCost: number | "";
  earlyMorningCharges: number | "";
  eveningCharges: number | "";
};

export type DriverDocument = {
  id: Id;
  documentType: string;
  fileName: string;
  fileUrl: string;
  createdAt?: string;
};

export type DriverReview = {
  id: Id;
  rating: number;
  description: string;
  createdAt?: string;
};

export type DriverEntity = {
  id: Id;
  basicInfo: DriverBasicInfo;
  costDetails?: DriverCostDetails;
  documents?: DriverDocument[];
  reviews?: DriverReview[];
};

/* ---------------- helpers ---------------- */

function normalizeOptions(input: any): Option[] {
  const arr = Array.isArray(input) ? input : input?.data || input?.items || [];
  if (!Array.isArray(arr)) return [];

  return arr
    .map((x) => {
      const id =
        x?.id ??
        x?.value ??
        x?._id ??
        x?.vendorId ??
        x?.vehicleTypeId ??
        x?.driverId;

      const label =
        x?.label ??
        x?.name ??
        x?.title ??
        x?.vendorName ??
        x?.vehicleTypeName ??
        String(id ?? "");

      if (id === undefined || id === null) return null;
      return { id, label } as Option;
    })
    .filter(Boolean) as Option[];
}

function normalizeLookups(data: any) {
  return {
    vendors: normalizeOptions(data?.vendors),
    vehicleTypes: normalizeOptions(data?.vehicleTypes),
    bloodGroups: normalizeOptions(data?.bloodGroups),
    genders: normalizeOptions(data?.genders),
    documentTypes: normalizeOptions(data?.documentTypes),
  };
}

/* ---------------- lookups ---------------- */

export async function fetchDriverLookups(): Promise<{
  vendors: Option[];
  vehicleTypes: Option[];
  bloodGroups: Option[];
  genders: Option[];
  documentTypes: Option[];
}> {
  // Preferred: one endpoint
  // NOTE: api.ts already normalizes base to ".../api/v1",
  // so do NOT include "/api/v1" here.
  try {
    const data = await api("/drivers/lookups", { method: "GET" });
    return normalizeLookups(data);
  } catch {
    // Fallbacks (if you don't have /drivers/lookups yet)
    const [vendors, vehicleTypes] = await Promise.all([
      api("/vendors/options", { method: "GET" }).catch(() => []),
      api("/vehicle-types/options", { method: "GET" }).catch(() => []),
    ]);

    return {
      vendors: normalizeOptions(vendors),
      vehicleTypes: normalizeOptions(vehicleTypes),
      bloodGroups: [
        { id: "A+", label: "A+" },
        { id: "A-", label: "A-" },
        { id: "B+", label: "B+" },
        { id: "B-", label: "B-" },
        { id: "AB+", label: "AB+" },
        { id: "AB-", label: "AB-" },
        { id: "O+", label: "O+" },
        { id: "O-", label: "O-" },
      ],
      genders: [
        { id: "Male", label: "Male" },
        { id: "Female", label: "Female" },
        { id: "Other", label: "Other" },
      ],
      documentTypes: [
        { id: "Aadhar", label: "Aadhar" },
        { id: "PAN", label: "PAN" },
        { id: "License", label: "Driving License" },
        { id: "VoterID", label: "Voter ID" },
        { id: "Profile", label: "Profile Photo" },
        { id: "Other", label: "Other" },
      ],
    };
  }
}

/* ---------------- driver details ---------------- */

export async function getDriver(driverId: Id): Promise<DriverEntity> {
  const data: any = await api(`/drivers/${driverId}`, { method: "GET" });
  const d = data?.data ?? data;

  return {
    id: d?.id ?? d?.driverId ?? driverId,
    basicInfo: d?.basicInfo ?? d,
    costDetails: d?.costDetails ?? d?.cost,
    documents: d?.documents ?? [],
    reviews: d?.reviews ?? [],
  };
}

/* ---------------- create / update basic info ---------------- */

export async function createOrUpdateDriverBasic(
  driverId: Id | null,
  payload: DriverBasicInfo
): Promise<{ id: Id }> {
  const hasFile = !!payload.profileFile;

  // Create => POST /drivers
  // Update basic => PUT /drivers/:id/basic
  const path = driverId ? `/drivers/${driverId}/basic` : `/drivers`;
  const method = driverId ? "PUT" : "POST";

  if (hasFile) {
    const fd = new FormData();
    Object.entries(payload).forEach(([k, v]) => {
      if (v === undefined || v === null) return;
      if (k === "profileFile") return;
      fd.append(k, String(v));
    });
    fd.append("profileFile", payload.profileFile as File);

    const res: any = await api(path, { method, body: fd });
    const id =
      res?.id ?? res?.data?.id ?? res?.driverId ?? res?.data?.driverId;
    return { id };
  }

  const res: any = await api(path, { method, body: payload });
  const id = res?.id ?? res?.data?.id ?? res?.driverId ?? res?.data?.driverId;
  return { id };
}

/* ---------------- cost details ---------------- */

export async function updateDriverCost(
  driverId: Id,
  payload: DriverCostDetails
): Promise<void> {
  await api(`/drivers/${driverId}/cost`, { method: "PUT", body: payload });
}

/* ---------------- documents ---------------- */

export async function listDriverDocuments(driverId: Id): Promise<DriverDocument[]> {
  const data: any = await api(`/drivers/${driverId}/documents`, {
    method: "GET",
  });
  return (data?.data ?? data ?? []) as DriverDocument[];
}

export async function uploadDriverDocument(
  driverId: Id,
  documentType: string,
  file: File
): Promise<void> {
  const fd = new FormData();
  fd.append("documentType", documentType);
  fd.append("file", file);

  await api(`/drivers/${driverId}/documents`, {
    method: "POST",
    body: fd,
  });
}

/* ---------------- reviews ---------------- */

export async function listDriverReviews(driverId: Id): Promise<DriverReview[]> {
  const data: any = await api(`/drivers/${driverId}/reviews`, {
    method: "GET",
  });
  return (data?.data ?? data ?? []) as DriverReview[];
}

export async function createDriverReview(
  driverId: Id,
  payload: { rating: number; description: string }
): Promise<void> {
  await api(`/drivers/${driverId}/reviews`, {
    method: "POST",
    body: payload,
  });
}
