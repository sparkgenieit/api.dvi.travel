// FILE: src/pages/hotel-form/PreviewStep.tsx
import React, { useMemo } from "react";
import { useQuery } from "@tanstack/react-query";

type ApiCtx = {
  apiGetFirst: (ps: string[]) => Promise<any>;
};

// Safe string render
const S = (v: any) => (v === null || v === undefined || v === "" ? "-" : String(v));

export default function PreviewStep({
  api,          // kept for signature parity (not used here)
  hotelId,      // kept for parity if you later want to refetch before preview
  hotelData,
  onPrev,
}: {
  api: ApiCtx;
  hotelId: string;
  hotelData: any;
  onPrev: () => void;
}) {
  // Normalize all fields your screenshot shows
  const info = useMemo(() => {
    const h = hotelData || {};

    // status: 1/true -> Active, else In-Active
    const statusRaw =
      h.status ?? h.hotel_status ?? h.isActive ?? h.active ?? h.hotelStatus ?? 1;
    const isActive =
      typeof statusRaw === "boolean" ? statusRaw : Number(statusRaw) === 1;

    return {
      hotelName: S(h.hotel_name ?? h.name),
      hotelCode: S(h.hotel_code ?? h.code),
      hotelMobile: S(
        h.hotel_mobile ??
          h.hotel_mobile_no ??
          h.phone ??
          (Array.isArray(h.mobiles) ? h.mobiles.join(",") : h.mobiles)
      ),
      hotelEmail: S(h.hotel_email ?? h.email),

      hotelPlace: S(h.hotel_place ?? h.place),

      // NOTE: API gives numeric/category id under hotel_category
      hotelCategory: S(
        h.hotel_category ?? h.category ?? h.starCategory ?? h.categoryStar ?? h.stars ?? h.star
      ),

      // >>> Important: read the hotel_* keys coming from your API
      country: S(
        h.hotel_country ?? h.country_name ?? h.country ?? h.countryName
      ),
      state: S(
        h.hotel_state ?? h.state_name ?? h.state ?? h.stateName
      ),
      city: S(
        h.hotel_city ?? h.city_name ?? h.city ?? h.cityName
      ),

      pincode: S(h.hotel_pincode ?? h.pincode ?? h.pin_code ?? h.zip ?? h.zipcode),

      // Lat/Long also arrive as hotel_latitude / hotel_longitude
      latitude: S(h.hotel_latitude ?? h.latitude ?? h.lat),
      longitude: S(h.hotel_longitude ?? h.longitude ?? h.lng ?? h.long),

      // Prefer the exact API key 'hotel_address'
      address: S(
        h.hotel_address ??
          h.address ??
          [
            h.address_line1 ?? h.address1,
            h.address_line2 ?? h.address2,
            h.area ?? h.locality,
            h.city_name ?? h.city ?? h.hotel_city,
            h.state_name ?? h.state ?? h.hotel_state,
            h.hotel_pincode ?? h.pincode ?? h.zip,
          ]
            .filter(Boolean)
            .join(", ")
      ),

      isActive,
    };
  }, [hotelData]);

  return (
    <>
      {/* Step header to match the “Preview” tab look */}
      <div className="pv-step-title">Preview</div>

      <div className="pv-card">
        <div className="pv-section-title">Basic Info</div>

        {/* 3-column details grid, exactly like the screenshot */}
        <div className="pv-grid">
          {/* Row 1 */}
          <div className="pv-field">
            <div className="pv-label">Hotel Name</div>
            <div className="pv-value">{info.hotelName}</div>
          </div>
          <div className="pv-field">
            <div className="pv-label">Hotel Code</div>
            <div className="pv-value">{info.hotelCode}</div>
          </div>
          <div className="pv-field">
            <div className="pv-label">Hotel Mobile</div>
            <div className="pv-value pv-dim">{info.hotelMobile}</div>
          </div>

          {/* Row 2 */}
          <div className="pv-field">
            <div className="pv-label">Hotel Email</div>
            <div className="pv-value pv-dim">{info.hotelEmail}</div>
          </div>
          <div className="pv-field">
            <div className="pv-label">Hotel Place</div>
            <div className="pv-value">{info.hotelPlace}</div>
          </div>
          <div className="pv-field">
            <div className="pv-label">Hotel Category</div>
            <div className="pv-value">{info.hotelCategory}</div>
          </div>

          {/* Row 3 */}
          <div className="pv-field">
            <div className="pv-label">Country</div>
            <div className="pv-value">{info.country}</div>
          </div>
          <div className="pv-field">
            <div className="pv-label">State</div>
            <div className="pv-value">{info.state}</div>
          </div>
          <div className="pv-field">
            <div className="pv-label">City</div>
            <div className="pv-value">{info.city}</div>
          </div>

          {/* Row 4 */}
          <div className="pv-field">
            <div className="pv-label">Pincode</div>
            <div className="pv-value pv-dim">{info.pincode}</div>
          </div>
          <div className="pv-field">
            <div className="pv-label">Latitude</div>
            <div className="pv-value pv-dim">{info.latitude}</div>
          </div>
          <div className="pv-field">
            <div className="pv-label">Longitude</div>
            <div className="pv-value pv-dim">{info.longitude}</div>
          </div>

          {/* Row 5 (Address + Status) */}
          <div className="pv-field pv-address">
            <div className="pv-label">Address</div>
            <div className="pv-value pv-dim">{info.address}</div>
          </div>
          <div className="pv-field">
            <div className="pv-label">Hotel Status</div>
            <div className={`pv-status ${info.isActive ? "pv-ok" : "pv-bad"}`}>
              {info.isActive ? "Active" : "In-Active"}
            </div>
          </div>
          {/* The last third column stays empty to keep the grid aligned like screenshot */}
          <div className="pv-field pv-empty" />
        </div>

        <hr className="pv-divider" />
      </div>

      <div className="pv-bottom">
        <button type="button" onClick={onPrev} className="pv-btn pv-btn-back">
          Back
        </button>
      </div>

      {/* Styles tuned to match your screenshot */}
      <style>{`
        :root{
          --pv-bg:#fdf5ff;
          --pv-card:#ffffff;
          --pv-border:#f0e7ff;
          --pv-shadow:0 10px 30px rgba(139,92,246,.08);
          --pv-text:#3a3a4a;
          --pv-muted:#9aa0b4;
          --pv-primary:#c026d3;
          --pv-bad:#ef4444;
          --pv-ok:#16a34a;
        }

        .pv-step-title{
          font-weight:600; color:#9c27b0; margin-bottom:.75rem;
        }

        .pv-card{
          background:var(--pv-card);
          border:1px solid var(--pv-border);
          border-radius:14px;
          padding:16px 18px;
          box-shadow:var(--pv-shadow);
        }

        .pv-section-title{
          color:#b012ce;
          font-weight:700;
          font-size:18px;
          margin-bottom:10px;
        }

        .pv-grid{
          display:grid;
          grid-template-columns: 1fr;
          gap: 14px 18px;
        }
        @media(min-width: 1024px){
          .pv-grid{
            grid-template-columns: repeat(3, 1fr);
          }
        }

        .pv-field{}
        .pv-label{
          font-size:13px; color:#7b7f8c;
          margin-bottom:4px;
        }
        .pv-value{
          font-size:15px; color:var(--pv-text); font-weight:600;
        }
        .pv-dim{
          color:#9aa0b4; font-weight:600;
        }
        .pv-address{
          grid-column: span 2;
        }
        @media(max-width: 1023px){
          .pv-address{ grid-column: span 1; }
        }
        .pv-status{
          font-weight:700;
        }
        .pv-status.pv-bad{ color: var(--pv-bad); }
        .pv-status.pv-ok{ color: var(--pv-ok); }

        .pv-empty{ }

        .pv-divider{
          border:0; border-top:1px solid var(--pv-border);
          margin-top:16px;
        }

        .pv-bottom{
          display:flex; align-items:center; justify-content:flex-start;
          margin-top:16px;
        }
        .pv-btn{
          display:inline-flex; align-items:center; gap:.45rem;
          border-radius:12px; padding:.7rem 1.4rem; font-weight:600;
          border:1px solid transparent; cursor:pointer;
        }
        .pv-btn-back{
          background:#9ca3af; color:#fff; border-color:#9ca3af;
        }
        .pv-btn-back:hover{ background:#6b7280; }
        .pv-value,
        .pv-dim {
          color: #111827 !important; /* near-black */
        }
      `}</style>
    </>
  );
}
