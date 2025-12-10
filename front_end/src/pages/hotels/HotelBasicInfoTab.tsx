// src/pages/hotels/HotelBasicInfoTab.tsx
import { useState, useEffect } from "react";
import { Hotel } from "@/services/hotels";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Textarea } from "@/components/ui/textarea";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import { X } from "lucide-react";

interface HotelBasicInfoTabProps {
  initialData: Partial<Hotel>;
  onSave: (data: Partial<Hotel>) => void;
  loading: boolean;
  onBack: () => void;
}

export const HotelBasicInfoTab = ({ initialData, onSave, loading, onBack }: HotelBasicInfoTabProps) => {
  const [formData, setFormData] = useState<Partial<Hotel>>(initialData);
  const [mobileInput, setMobileInput] = useState("");
  const [emailInput, setEmailInput] = useState("");

  useEffect(() => {
    setFormData(initialData);
  }, [initialData]);

  const updateField = (field: keyof Hotel, value: any) => {
    setFormData(prev => ({ ...prev, [field]: value }));
  };

  const addMobile = () => {
    if (mobileInput.trim()) {
      const mobiles = formData.mobile || [];
      updateField("mobile", [...mobiles, mobileInput.trim()]);
      setMobileInput("");
    }
  };

  const removeMobile = (index: number) => {
    const mobiles = formData.mobile || [];
    updateField("mobile", mobiles.filter((_, i) => i !== index));
  };

  const addEmail = () => {
    if (emailInput.trim()) {
      const emails = formData.email || [];
      updateField("email", [...emails, emailInput.trim()]);
      setEmailInput("");
    }
  };

  const removeEmail = (index: number) => {
    const emails = formData.email || [];
    updateField("email", emails.filter((_, i) => i !== index));
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    onSave(formData);
  };

  return (
    <form onSubmit={handleSubmit} className="space-y-6">
      <h2 className="text-xl font-semibold text-primary mb-6">Basic Details</h2>

      {/* Row 1: Hotel Name, Place, Status */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <Label htmlFor="name">Hotel Name <span className="text-red-500">*</span></Label>
          <Input
            id="name"
            value={formData.name || ""}
            onChange={(e) => updateField("name", e.target.value)}
            placeholder="Enter the Hotel Name"
            required
          />
        </div>
        <div>
          <Label htmlFor="place">Place <span className="text-red-500">*</span></Label>
          <Input
            id="place"
            value={formData.place || ""}
            onChange={(e) => updateField("place", e.target.value)}
            placeholder="Enter the hotel place"
            required
          />
        </div>
        <div>
          <Label htmlFor="status">Status <span className="text-red-500">*</span></Label>
          <Select value={formData.status || "Active"} onValueChange={(val) => updateField("status", val)}>
            <SelectTrigger>
              <SelectValue />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value="Active">Active</SelectItem>
              <SelectItem value="In-Active">In-Active</SelectItem>
            </SelectContent>
          </Select>
        </div>
      </div>

      {/* Row 2: Mobile, Email, Category */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <Label htmlFor="mobile">Mobile <span className="text-red-500">*</span></Label>
          <div className="flex gap-2 mb-2">
            <Input
              id="mobile"
              value={mobileInput}
              onChange={(e) => setMobileInput(e.target.value)}
              placeholder="Enter the Mobile Number"
              onKeyPress={(e) => e.key === "Enter" && (e.preventDefault(), addMobile())}
            />
            <button type="button" onClick={addMobile} className="px-3 py-2 bg-primary text-white rounded-md hover:bg-primary/90">Add</button>
          </div>
          <div className="flex flex-wrap gap-2">
            {(formData.mobile || []).map((mob, i) => (
              <span key={i} className="inline-flex items-center gap-1 px-2 py-1 bg-gray-100 rounded text-sm">
                {mob}
                <button type="button" onClick={() => removeMobile(i)} className="text-gray-500 hover:text-gray-700">
                  <X className="w-3 h-3" />
                </button>
              </span>
            ))}
          </div>
        </div>
        <div>
          <Label htmlFor="email">Email <span className="text-red-500">*</span></Label>
          <div className="flex gap-2 mb-2">
            <Input
              id="email"
              type="email"
              value={emailInput}
              onChange={(e) => setEmailInput(e.target.value)}
              placeholder="Enter the Email Id"
              onKeyPress={(e) => e.key === "Enter" && (e.preventDefault(), addEmail())}
            />
            <button type="button" onClick={addEmail} className="px-3 py-2 bg-primary text-white rounded-md hover:bg-primary/90">Add</button>
          </div>
          <div className="flex flex-wrap gap-2">
            {(formData.email || []).map((em, i) => (
              <span key={i} className="inline-flex items-center gap-1 px-2 py-1 bg-gray-100 rounded text-sm">
                {em}
                <button type="button" onClick={() => removeEmail(i)} className="text-gray-500 hover:text-gray-700">
                  <X className="w-3 h-3" />
                </button>
              </span>
            ))}
          </div>
        </div>
        <div>
          <Label htmlFor="category">Category <span className="text-red-500">*</span></Label>
          <Select value={formData.category || ""} onValueChange={(val) => updateField("category", val)}>
            <SelectTrigger>
              <SelectValue placeholder="Choose Category" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value="STD">STD</SelectItem>
              <SelectItem value="DLX">DLX</SelectItem>
              <SelectItem value="SGL">SGL</SelectItem>
              <SelectItem value="DBL">DBL</SelectItem>
            </SelectContent>
          </Select>
        </div>
      </div>

      {/* Row 3: Power Backup, Country, State */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <Label htmlFor="powerBackup">Power Backup? <span className="text-red-500">*</span></Label>
          <Select value={formData.powerBackup || "No"} onValueChange={(val) => updateField("powerBackup", val)}>
            <SelectTrigger>
              <SelectValue />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value="Yes">Yes</SelectItem>
              <SelectItem value="No">No</SelectItem>
            </SelectContent>
          </Select>
        </div>
        <div>
          <Label htmlFor="country">Country <span className="text-red-500">*</span></Label>
          <Select value={formData.country || ""} onValueChange={(val) => updateField("country", val)}>
            <SelectTrigger>
              <SelectValue placeholder="Choose Country" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value="India">India</SelectItem>
              <SelectItem value="UAE">UAE</SelectItem>
              <SelectItem value="Singapore">Singapore</SelectItem>
            </SelectContent>
          </Select>
        </div>
        <div>
          <Label htmlFor="state">State <span className="text-red-500">*</span></Label>
          <Select value={formData.state || ""} onValueChange={(val) => updateField("state", val)}>
            <SelectTrigger>
              <SelectValue placeholder="Please Choose State" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value="Karnataka">Karnataka</SelectItem>
              <SelectItem value="Tamil Nadu">Tamil Nadu</SelectItem>
              <SelectItem value="Kerala">Kerala</SelectItem>
              <SelectItem value="Maharashtra">Maharashtra</SelectItem>
            </SelectContent>
          </Select>
        </div>
      </div>

      {/* Row 4: City, Pincode, Hotel Code */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <Label htmlFor="city">City <span className="text-red-500">*</span></Label>
          <Select value={formData.city || ""} onValueChange={(val) => updateField("city", val)}>
            <SelectTrigger>
              <SelectValue placeholder="Please Choosen City" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value="Bengaluru">Bengaluru</SelectItem>
              <SelectItem value="Chennai">Chennai</SelectItem>
              <SelectItem value="Mumbai">Mumbai</SelectItem>
              <SelectItem value="Delhi">Delhi</SelectItem>
            </SelectContent>
          </Select>
        </div>
        <div>
          <Label htmlFor="pincode">Pincode <span className="text-red-500">*</span></Label>
          <Input
            id="pincode"
            value={formData.pincode || ""}
            onChange={(e) => updateField("pincode", e.target.value)}
            placeholder="Enter the Pincode"
            required
          />
        </div>
        <div>
          <Label htmlFor="hotelCode">Hotel Code <span className="text-red-500">*</span></Label>
          <Input
            id="hotelCode"
            value={formData.hotelCode || ""}
            onChange={(e) => updateField("hotelCode", e.target.value)}
            placeholder="Enter the hotel code"
            required
          />
        </div>
      </div>

      {/* Row 5: Hotel Margin, GST Type, GST Percentage */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <Label htmlFor="hotelMarginPercent">Hotel Margin (In Percentage) <span className="text-red-500">*</span></Label>
          <Input
            id="hotelMarginPercent"
            type="number"
            value={formData.hotelMarginPercent || ""}
            onChange={(e) => updateField("hotelMarginPercent", parseFloat(e.target.value) || 0)}
            placeholder="Enter the Margin"
            required
          />
        </div>
        <div>
          <Label htmlFor="hotelMarginGstType">Hotel Margin GST Type <span className="text-red-500">*</span></Label>
          <Select value={formData.hotelMarginGstType || "Included"} onValueChange={(val) => updateField("hotelMarginGstType", val)}>
            <SelectTrigger>
              <SelectValue />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value="Included">Included</SelectItem>
              <SelectItem value="Excluded">Excluded</SelectItem>
            </SelectContent>
          </Select>
        </div>
        <div>
          <Label htmlFor="hotelMarginGstPercent">Hotel Margin GST Percentage <span className="text-red-500">*</span></Label>
          <Select value={formData.hotelMarginGstPercent || "5% GST - %5"} onValueChange={(val) => updateField("hotelMarginGstPercent", val)}>
            <SelectTrigger>
              <SelectValue />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value="5% GST - %5">5% GST - %5</SelectItem>
              <SelectItem value="12% GST - %12">12% GST - %12</SelectItem>
              <SelectItem value="18% GST - %18">18% GST - %18</SelectItem>
              <SelectItem value="0% GST - %0">0% GST - %0</SelectItem>
            </SelectContent>
          </Select>
        </div>
      </div>

      {/* Row 6: Latitude, Longitude, Hotspot Status */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <Label htmlFor="latitude">Latitude</Label>
          <Input
            id="latitude"
            value={formData.latitude || ""}
            onChange={(e) => updateField("latitude", e.target.value)}
            placeholder="Enter the Latitude"
          />
        </div>
        <div>
          <Label htmlFor="longitude">Longitude</Label>
          <Input
            id="longitude"
            value={formData.longitude || ""}
            onChange={(e) => updateField("longitude", e.target.value)}
            placeholder="Enter the Longitude"
          />
        </div>
        <div>
          <Label htmlFor="hotspotStatus">Hotspot Status <span className="text-red-500">*</span></Label>
          <Select value={formData.hotspotStatus || "In-Active"} onValueChange={(val) => updateField("hotspotStatus", val)}>
            <SelectTrigger>
              <SelectValue />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value="Active">Active</SelectItem>
              <SelectItem value="In-Active">In-Active</SelectItem>
            </SelectContent>
          </Select>
        </div>
      </div>

      {/* Row 7: Address */}
      <div>
        <Label htmlFor="address">Address <span className="text-red-500">*</span></Label>
        <Textarea
          id="address"
          value={formData.address || ""}
          onChange={(e) => updateField("address", e.target.value)}
          placeholder="Enter the Address"
          rows={3}
          required
        />
      </div>

      {/* Action Buttons */}
      <div className="flex justify-between pt-4">
        <button
          type="button"
          onClick={onBack}
          className="px-6 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300"
        >
          Back
        </button>
        <button
          type="submit"
          disabled={loading}
          className="px-6 py-2 bg-primary text-white rounded-md hover:bg-primary/90 disabled:opacity-50"
        >
          {loading ? "Saving..." : "Save & Continue"}
        </button>
      </div>
    </form>
  );
};
