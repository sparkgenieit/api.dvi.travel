//src/App.tsx

import { Toaster } from "@/components/ui/toaster";
import { Toaster as Sonner } from "@/components/ui/sonner";
import { TooltipProvider } from "@/components/ui/tooltip";
import { QueryClient, QueryClientProvider } from "@tanstack/react-query";
import {
  BrowserRouter,
  Routes,
  Route,
  Navigate,
  useParams,
  Outlet,
} from "react-router-dom";
import DynamicMeta from "@/components/DynamicMeta";
import { getToken } from "@/lib/api";

import { MainLayout } from "./layouts/MainLayout";
import Dashboard from "./pages/Dashboard";
import { CreateItinerary } from "./pages/CreateItinerary/CreateItinerary";
import { LatestItinerary } from "./pages/LatestItinerary";
import { AccountsManager } from "./pages/accounts/AccountsManager";
import "./App.css";
import NotFound from "./pages/NotFound";
import { AccountsLedger } from "./pages/accounts/AccountsLedger";
import Hotels from "./pages/Hotels";
import Login from "./pages/Login";
import HotelForm from "./pages/hotel-form/HotelForm";
import { DailyMomentTracker } from "./pages/daily-moment-tracker/DailyMomentTracker";
import DailyMomentDayView from "./pages/daily-moment-tracker/DailyMomentDayView";
import VendorsPage from "./pages/vendor/VendorsPage";
import VendorFormPage from "./pages/vendor/VendorFormPage";
import DriversPage from "./pages/drivers/DriversPage";
import DriverFormPage from "./pages/drivers/DriverFormPage";
import VehicleAvailabilityPage from "./pages/vehicle-availability/VehicleAvailabilityPage";
import { ItineraryDetails } from "./pages/ItineraryDetails";
import HotspotList from "./pages/hotspot/HotspotList";
import HotspotForm from "./pages/hotspot/HotspotForm";
import HotspotPreview from "./pages/hotspot/HotspotPreview";
import ParkingChargeBulkImport from "./pages/hotspot/ParkingChargeBulkImport";
import ActivityForm from "./pages/activity/ActivityForm";
import ActivityListPage from "./pages/activity/ActivityListPage";
import GuideListPage from "./pages/guide/GuideListPage";
import GuideFormPage from "./pages/guide/GuideFormPage";
import ActivityPreviewPage from "./pages/activity/ActivityPreviewPage";

// ── Deep-link helpers: /hotels/:id/<tab> → /hotels/:id/edit?tab=<tab> ──
const RoomsRedirect = () => {
  const { id } = useParams();
  return <Navigate to={`/hotels/${id}/edit?tab=rooms`} replace />;
};
const AmenitiesRedirect = () => {
  const { id } = useParams();
  return <Navigate to={`/hotels/${id}/edit?tab=amenities`} replace />;
};
const PriceBookRedirect = () => {
  const { id } = useParams();
  return <Navigate to={`/hotels/${id}/edit?tab=pricebook`} replace />;
};
const ReviewsRedirect = () => {
  const { id } = useParams();
  return <Navigate to={`/hotels/${id}/edit?tab=reviews`} replace />;
};
const PreviewRedirect = () => {
  const { id } = useParams();
  return <Navigate to={`/hotels/${id}/edit?tab=preview`} replace />;
};

/**
 * Auth guard: blocks all protected routes when no token is present.
 * Uses the same accessToken that auth.ts stores via setToken().
 */
const RequireAuth = () => {
  const token = getToken();
  if (!token) {
    return <Navigate to="/login" replace />;
  }
  return <Outlet />;
};

/**
 * TEMP placeholder only for /drivers/:id view route
 * (Keep until you create a proper DriverViewPage.tsx)
 */
const DriverViewPage = () => {
  const { id } = useParams();
  return (
    <div className="p-6">
      <h1 className="text-xl font-semibold text-slate-800">View Driver</h1>
      <p className="text-sm text-slate-500 mt-2">
        Placeholder page for driver ID: <span className="font-medium">{id}</span>
      </p>
    </div>
  );
};

const queryClient = new QueryClient();

const App = () => (
  <QueryClientProvider client={queryClient}>
    <TooltipProvider>
      <Toaster />
      <Sonner />
      <BrowserRouter>
        <DynamicMeta />
        <Routes>
          {/* Public route */}
          <Route path="/login" element={<Login />} />

          {/* All routes below require auth */}
          <Route element={<RequireAuth />}>
            <Route
              path="/"
              element={
                <MainLayout>
                  <Dashboard />
                </MainLayout>
              }
            />

            <Route
              path="/create-itinerary"
              element={
                <MainLayout>
                  <CreateItinerary />
                </MainLayout>
              }
            />
            <Route
              path="/latest-itinerary"
              element={
                <MainLayout>
                  <LatestItinerary />
                </MainLayout>
              }
            />
            <Route
              path="/itinerary-details/:id"
              element={
                <MainLayout>
                  <ItineraryDetails />
                </MainLayout>
              }
            />
            <Route
              path="/accounts-manager"
              element={
                <MainLayout>
                  <AccountsManager />
                </MainLayout>
              }
            />
            <Route
              path="/accounts-ledger"
              element={
                <MainLayout>
                  <AccountsLedger />
                </MainLayout>
              }
            />

            {/* Daily Moment Tracker – main list */}
            <Route
              path="/daily-moment-tracker"
              element={
                <MainLayout>
                  <DailyMomentTracker />
                </MainLayout>
              }
            />
            {/* Daily Moment Tracker – alias to match sidebar link /daily-moment */}
            <Route
              path="/daily-moment"
              element={
                <MainLayout>
                  <DailyMomentTracker />
                </MainLayout>
              }
            />
            {/* Daily Moment Day View – opened from car icon */}
            <Route
              path="/daily-moment/day-view/:planId/:routeId"
              element={
                <MainLayout>
                  <DailyMomentDayView />
                </MainLayout>
              }
            />

            {/* Hotels */}
            <Route
              path="/hotels"
              element={
                <MainLayout>
                  <Hotels />
                </MainLayout>
              }
            />
            {/* Create */}
            <Route
              path="/hotels/new"
              element={
                <MainLayout>
                  <HotelForm />
                </MainLayout>
              }
            />
            {/* Edit (primary) */}
            <Route
              path="/hotels/:id/edit"
              element={
                <MainLayout>
                  <HotelForm />
                </MainLayout>
              }
            />
            {/* Edit (alias): allow /hotels/:id to open the same form */}
            <Route
              path="/hotels/:id"
              element={
                <MainLayout>
                  <HotelForm />
                </MainLayout>
              }
            />
            {/* Deep-links for each step */}
            <Route path="/hotels/:id/rooms" element={<RoomsRedirect />} />
            <Route path="/hotels/:id/amenities" element={<AmenitiesRedirect />} />
            <Route path="/hotels/:id/pricebook" element={<PriceBookRedirect />} />
            <Route path="/hotels/:id/reviews" element={<ReviewsRedirect />} />
            <Route path="/hotels/:id/preview" element={<PreviewRedirect />} />

            {/* Vendor List */}
            <Route
              path="/vendor"
              element={
                <MainLayout>
                  <VendorsPage />
                </MainLayout>
              }
            />
            {/* Add Vendor */}
            <Route
              path="/vendor/new"
              element={
                <MainLayout>
                  <VendorFormPage />
                </MainLayout>
              }
            />
            {/* Edit Vendor */}
            <Route
              path="/vendor/:id"
              element={
                <MainLayout>
                  <VendorFormPage />
                </MainLayout>
              }
            />
            <Route
              path="/vendor/:id/edit"
              element={
                <MainLayout>
                  <VendorFormPage />
                </MainLayout>
              }
            />

            {/* ✅ Drivers List */}
            <Route
              path="/drivers"
              element={
                <MainLayout>
                  <DriversPage />
                </MainLayout>
              }
            />

            {/* ✅ Drivers Add (same pattern as vendor form) */}
            <Route
              path="/drivers/new"
              element={
                <MainLayout>
                  <DriverFormPage />
                </MainLayout>
              }
            />
            <Route
              path="/drivers/create"
              element={
                <MainLayout>
                  <DriverFormPage />
                </MainLayout>
              }
            />

            {/* ✅ Drivers Edit (use DriverFormPage just like VendorFormPage) */}
            <Route
              path="/drivers/:id/edit"
              element={
                <MainLayout>
                  <DriverFormPage />
                </MainLayout>
              }
            />

            {/* Drivers View (placeholder, can be changed to DriverFormPage if you want exact vendor parity) */}
            <Route
              path="/drivers/:id"
              element={
                <MainLayout>
                  <DriverViewPage />
                </MainLayout>
              }
            />

            {/* ✅ Vehicle Availability Chart */}
            <Route
              path="/vehicle-availability"
              element={
                <MainLayout>
                  <VehicleAvailabilityPage />
                </MainLayout>
              }
            />

            {/* ✅ Hotspot Routes */}
            <Route
              path="/hotspots"
              element={
                <MainLayout>
                  <HotspotList />
                </MainLayout>
              }
            />
            <Route
              path="/hotspots/new"
              element={
                <MainLayout>
                  <HotspotForm />
                </MainLayout>
              }
            />
            <Route
              path="/hotspots/:id/edit"
              element={
                <MainLayout>
                  <HotspotForm />
                </MainLayout>
              }
            />
            <Route
              path="/hotspots/:id/preview"
              element={
                <MainLayout>
                  <HotspotPreview />
                </MainLayout>
              }
            />

            {/* ✅ Activity Routes */}
          <Route
            path="/activities"
            element={
              <MainLayout>
                <ActivityListPage />
              </MainLayout>
            }
          />
            <Route
              path="/activities/new"
              element={
                <MainLayout>
                  <ActivityForm />
                </MainLayout>
              }
            />
            <Route
              path="/activities/:id/edit"
              element={
                <MainLayout>
                  <ActivityForm />
                </MainLayout>
              }
            />
          </Route>
                      <Route
              path="/activities/:id/preview"
              element={
                <MainLayout>
                  <ActivityPreviewPage />
                </MainLayout>
              }
            />
          {/* Parking Charge Bulk Import */}
          <Route
            path="/parking-charge-bulk-import" 
            element={
              <MainLayout>
                <ParkingChargeBulkImport />
              </MainLayout>
            }
          />

          {/* Catch-all */}
          <Route path="*" element={<NotFound />} />
        </Routes>
      </BrowserRouter>
    </TooltipProvider>
  </QueryClientProvider>
);

export default App;
