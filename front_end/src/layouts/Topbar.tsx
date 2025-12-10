// src/layouts/Topbar.tsx 
import { Home, ChevronRight, Menu } from "lucide-react";
import { Button } from "@/components/ui/button";
import { useLocation, useNavigate } from "react-router-dom";

// tiny JWT helpers
function getToken() {
  return localStorage.getItem("accessToken") || "";
}
function clearToken() {
  localStorage.removeItem("accessToken");
}

interface TopbarProps {
  onMobileMenuToggle: () => void;
}

export const Topbar = ({ onMobileMenuToggle }: TopbarProps) => {
  const location = useLocation();
  const navigate = useNavigate();
  const authed = Boolean(getToken());

  // derive title from current route
  const getPageTitle = () => {
    const path = location.pathname.toLowerCase();
    if (path.includes("/hotels")) return "Hotel";
    if (path.includes("/latest-itinerary")) return "Latest Itenary";
    if (path.includes("/accounts-ledger")) return "Accounts Ledger";
    if (path.includes("/accounts")) return "Accounts";
    if (path.includes("/daily-moment")) return "Daily Moment Tracker";
    if (path.includes("/vendor")) return "Vendor";
    if (path.includes("/drivers")) return "Drivers";
    if (path.includes("/vehicles")) return "Vehicles";
    if (path.includes("/guide")) return "Guide";
    if (path.includes("/activities")) return "Activity";
    if (path.includes("/locations")) return "Locations";
    return "Dashboard";
  };

  const pageTitle = getPageTitle();

  return (
    <div className="bg-white border-b border-border">
      {/* Row: Title (left) — Breadcrumb + Login (right) */}
      <div className="flex items-center justify-between py-4 sm:py-6">        {/* Left: menu (mobile) + title */}
        <div className="flex items-center gap-3">
          <Button
            variant="ghost"
            size="icon"
            className="md:hidden"
            onClick={onMobileMenuToggle}
          >
            <Menu className="h-6 w-6" />
          </Button>
          <h4 className="text-xl sm:text-2xl font-bold text-foreground">
            {pageTitle}
          </h4>
        </div>

        {/* Right: breadcrumb + login/logout (moved to top-right corner) */}
        <div className="flex items-center gap-3">
          <nav aria-label="breadcrumb" className="hidden sm:block">
            <ol className="flex items-center gap-2 text-sm">
              <li className="flex items-center">
                <Home className="h-4 w-4 text-muted-foreground" />
              </li>
              <li className="flex items-center gap-2">
                <ChevronRight className="h-4 w-4 text-muted-foreground" />
                <span className="text-primary font-medium">{pageTitle}</span>
              </li>
            </ol>
          </nav>

          {authed ? (
            <Button
              variant="outline"
              size="sm"
              onClick={() => {
                clearToken();
                navigate("/login");
              }}
            >
              Logout
            </Button>
          ) : (
            <Button
              variant="default"
              size="sm"
              onClick={() => navigate("/login")}
            >
              Login
            </Button>
          )}
        </div>
      </div>

      {/* Breadcrumb for mobile under title (so the top-right stays clean) */}
      <div className="sm:hidden px-4 pb-3">
        <nav aria-label="breadcrumb">
          <ol className="flex items-center gap-2 text-sm">
            <li className="flex items-center">
              <Home className="h-4 w-4 text-muted-foreground" />
            </li>
            <li className="flex items-center gap-2">
              <ChevronRight className="h-4 w-4 text-muted-foreground" />
              <span className="text-primary font-medium">{pageTitle}</span>
            </li>
          </ol>
        </nav>
      </div>
    </div>
  );
};
