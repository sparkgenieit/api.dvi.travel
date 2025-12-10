// src/components/DynamicMeta.tsx
import React, { useEffect } from "react";
import { useLocation } from "react-router-dom";

// 1️⃣ central place for all route → meta
const META_BY_PATH: Record<
  string,
  {
    title: string;
    description?: string;
    keywords?: string;
  }
> = {
  "/": {
    title: "Dashboard - DVi Holidays | Travel Management System",
    description:
      "DVi Holidays dashboard to monitor itineraries, revenue, bookings and team activities.",
    keywords: "dvi holidays, dashboard, travel management, itineraries",
  },
  "/create-itinerary": {
    title: "Create Itinerary - DVi Holidays",
    description:
      "Create and manage custom travel itineraries for your customers.",
    keywords: "create itinerary, dvi holidays, travel plan, proposal",
  },
  "/latest-itinerary": {
    title: "Latest Itinerary - DVi Holidays",
    description:
      "View all latest itineraries created by your team and agents.",
    keywords: "latest itinerary, itinerary list, travel packages",
  },
  "/confirmed-itinerary": {
    title: "Confirmed Itinerary - DVi Holidays",
    description: "See all confirmed / booked itineraries in one place.",
    keywords: "confirmed itinerary, bookings, travel confirmation",
  },
  "/accounts-manager": {
    title: "Accounts Manager - DVi Holidays",
    description:
      "Payout list, profit, receivables and agent-wise account summary.",
    keywords: "accounts, payouts, travel finance, agent accounts",
  },
  "/accounts-ledger": {
    title: "Accounts Ledger - DVi Holidays",
    description:
      "Agent / vehicle / hotel component wise ledger view with totals.",
    keywords: "accounts ledger, agent ledger, travel ledger, dvi",
  },
  "/hotels": {
    title: "Hotels - DVi Holidays",
    description:
      "Hotel master, hotel list, pricebook and room / amenities details.",
    keywords: "hotels, hotel list, hotel master, pricebook, dvi holidays",
  },
  "/daily-moment": {
    title: "Daily Moment Tracker - DVi Holidays",
    description: "Track daily team / vendor / driver moments and updates.",
  },
  "/vendor-management": {
    title: "Vendor Management - DVi Holidays",
    description: "Manage vehicle / hotel / activity vendors.",
  },
  "/hotspot": {
    title: "Hotspot Management - DVi Holidays",
    description: "Create and manage sightseeing / hotspots.",
  },
  "/activity": {
    title: "Activity Management - DVi Holidays",
    description: "Create and maintain activity addons for itineraries.",
  },
  "/locations": {
    title: "Locations - DVi Holidays",
    description: "Location master for itineraries and hotel assignments.",
  },
  "/guide": {
    title: "Guide Management - DVi Holidays",
  },
  "/staff": {
    title: "Staff - DVi Holidays",
  },
  "/agent": {
    title: "Agents - DVi Holidays",
    description: "Manage travel agents, contact and payouts.",
  },
  "/pricebook-export": {
    title: "Pricebook Export - DVi Holidays",
  },
  "/settings": {
    title: "Settings - DVi Holidays",
  },
};

// 2️⃣ helper to create / update <meta>
function upsertMeta(name: string, content: string, attr: "name" | "property" = "name") {
  if (!content) return;
  let el = document.querySelector<HTMLMetaElement>(`meta[${attr}="${name}"]`);
  if (!el) {
    el = document.createElement("meta");
    el.setAttribute(attr, name);
    document.head.appendChild(el);
  }
  el.setAttribute("content", content);
}

const DEFAULT_TITLE = "DVi Holidays | Travel Management System";
const DEFAULT_DESCRIPTION =
  "DVi Holidays admin dashboard for managing travel itineraries, agents, hotels, vehicles, and bookings.";
const DEFAULT_IMAGE = "/assets/img/DVi-Logo1-2048x1860.png";

const DynamicMeta: React.FC = () => {
  const location = useLocation();

  useEffect(() => {
    const path = location.pathname.toLowerCase();

    // try exact match first
    let meta = META_BY_PATH[path];

    // small fallback: if route is nested like /hotels/123 or /accounts-ledger/vehicle
    if (!meta) {
      const firstSeg = "/" + path.split("/")[1];
      meta = META_BY_PATH[firstSeg];
    }

    const title = meta?.title ?? DEFAULT_TITLE;
    const desc = meta?.description ?? DEFAULT_DESCRIPTION;
    const keywords = meta?.keywords;

    // ----- actual DOM updates -----
    document.title = title;

    // normal SEO
    upsertMeta("description", desc);
    if (keywords) {
      upsertMeta("keywords", keywords);
    }

    // OpenGraph
    upsertMeta("og:title", title, "property");
    upsertMeta("og:description", desc, "property");
    upsertMeta("og:type", "website", "property");
    upsertMeta("og:image", DEFAULT_IMAGE, "property");

    // Twitter cards
    upsertMeta("twitter:card", "summary_large_image");
    upsertMeta("twitter:title", title);
    upsertMeta("twitter:description", desc);
    upsertMeta("twitter:image", DEFAULT_IMAGE);
  }, [location.pathname]);

  return null;
};

export default DynamicMeta;
