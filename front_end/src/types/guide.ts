// FILE: src/types/guide.ts

export interface GuideAvailableSlot {
  id: string;
  label: string;
}

export const GUIDE_SLOTS: GuideAvailableSlot[] = [
  { id: "slot1", label: "Slot 1: 9 AM to 1 PM" },
  { id: "slot2", label: "Slot 2: 9 AM to 4 PM" },
  { id: "slot3", label: "Slot 3: 6 PM to 9 PM" },
];

export interface GuideBankDetails {
  bankName: string;
  branchName: string;
  ifscCode: string;
  accountNumber: string;
  confirmAccountNumber: string;
}

export interface GuidePreferredFor {
  hotspot: boolean;
  activity: boolean;
  itinerary: boolean;
}

export interface GuidePricebook {
  startDate: string;
  endDate: string;
  pax1to5: {
    slot1: number;
    slot2: number;
    slot3: number;
  };
  pax6to14: {
    slot1: number;
    slot2: number;
    slot3: number;
  };
  pax15to40: {
    slot1: number;
    slot2: number;
    slot3: number;
  };
}

export interface GuideReview {
  id: string;
  rating: number;
  description: string;
  createdOn: string;
}

export interface Guide {
  id: number;
  name: string;
  dateOfBirth: string;
  bloodGroup: string;
  gender: string;
  primaryMobile: string;
  alternativeMobile: string;
  email: string;
  emergencyMobile: string;
  password: string;
  role: string;
  experience: number;
  aadharCardNo: string;
  languageProficiency: string;
  country: string;
  state: string;
  city: string;
  gstType: string;
  gstPercentage: string;
  availableSlots: string[];
  bankDetails: GuideBankDetails;
  preferredFor: GuidePreferredFor;
  pricebook: GuidePricebook;
  reviews: GuideReview[];
  status: 0 | 1;
}

export interface GuideListRow {
  id: number;
  name: string;
  mobileNumber: string;
  email: string;
  status: 0 | 1;
}

// Options for dropdowns
export const BLOOD_GROUPS = [
  "A RhD positive (A+)",
  "A RhD negative (A-)",
  "B RhD positive (B+)",
  "B RhD negative (B-)",
  "O RhD positive (O+)",
  "O RhD negative (O-)",
  "AB RhD positive (AB+)",
  "AB RhD negative (AB-)",
];

export const GENDERS = ["Male", "Female", "Other"];

export const ROLES = ["Vendor", "DVI", "Others"];

export const LANGUAGES = ["English", "Hindi", "Kannada", "Tamil", "Telugu", "Malayalam", "Marathi"];

export const GST_TYPES = ["Included", "Excluded", "Not Applicable"];

export const GST_PERCENTAGES = [
  "0% GST - %0",
  "5% GST - %5",
  "12% GST - %12",
  "18% GST - %18",
  "28% GST - %28",
];

export const COUNTRIES = ["India", "USA", "UK", "UAE", "Australia"];

export const STATES: Record<string, string[]> = {
  India: ["Kerala", "Karnataka", "Tamil Nadu", "Maharashtra", "Delhi"],
  USA: ["California", "New York", "Texas", "Florida"],
  UK: ["England", "Scotland", "Wales"],
  UAE: ["Dubai", "Abu Dhabi", "Sharjah"],
  Australia: ["New South Wales", "Victoria", "Queensland"],
};

export const CITIES: Record<string, string[]> = {
  Kerala: ["Munnar", "Kochi", "Thiruvananthapuram", "Alleppey"],
  Karnataka: ["Bangalore", "Mysore", "Mangalore", "Hampi"],
  "Tamil Nadu": ["Chennai", "Madurai", "Coimbatore", "Ooty"],
  Maharashtra: ["Mumbai", "Pune", "Nashik", "Aurangabad"],
  Delhi: ["New Delhi", "Noida", "Gurgaon"],
  California: ["Los Angeles", "San Francisco", "San Diego"],
  "New York": ["New York City", "Buffalo", "Albany"],
  Texas: ["Houston", "Dallas", "Austin"],
  Florida: ["Miami", "Orlando", "Tampa"],
  England: ["London", "Manchester", "Birmingham"],
  Scotland: ["Edinburgh", "Glasgow"],
  Wales: ["Cardiff", "Swansea"],
  Dubai: ["Dubai City", "Jebel Ali"],
  "Abu Dhabi": ["Abu Dhabi City", "Al Ain"],
  Sharjah: ["Sharjah City"],
  "New South Wales": ["Sydney", "Newcastle"],
  Victoria: ["Melbourne", "Geelong"],
  Queensland: ["Brisbane", "Gold Coast"],
};
