// FILE: src/services/guideService.ts

import type { Guide, GuideListRow, GuideReview, GuidePricebook } from "@/types/guide";

// Mock data
let mockGuides: Guide[] = [
  {
    id: 1,
    name: "Anjaly",
    dateOfBirth: "24/05/2024",
    bloodGroup: "A RhD positive (A+)",
    gender: "Female",
    primaryMobile: "9685741230",
    alternativeMobile: "2365455878",
    email: "anjaly@gmail.com",
    emergencyMobile: "9856588888",
    password: "password123",
    role: "Vendor",
    experience: 5,
    aadharCardNo: "123456789875",
    languageProficiency: "English",
    country: "India",
    state: "Kerala",
    city: "Munnar",
    gstType: "Included",
    gstPercentage: "18% GST - %18",
    availableSlots: ["slot1", "slot2", "slot3"],
    bankDetails: {
      bankName: "IDBI BANK",
      branchName: "KILPAUCK",
      ifscCode: "1236545555",
      accountNumber: "55564564646",
      confirmAccountNumber: "55564564646",
    },
    preferredFor: {
      hotspot: false,
      activity: false,
      itinerary: true,
    },
    pricebook: {
      startDate: "",
      endDate: "",
      pax1to5: { slot1: 0, slot2: 0, slot3: 0 },
      pax6to14: { slot1: 0, slot2: 0, slot3: 0 },
      pax15to40: { slot1: 0, slot2: 0, slot3: 0 },
    },
    reviews: [
      {
        id: "rev1",
        rating: 2,
        description: "asds",
        createdOn: "08-12-2025 03:49 PM",
      },
    ],
    status: 1,
  },
];

let nextId = 2;
let nextReviewId = 2;

// Simulate API delay
const delay = (ms: number) => new Promise((res) => setTimeout(res, ms));

export const GuideAPI = {
  // List all guides
  async list(): Promise<GuideListRow[]> {
    await delay(300);
    return mockGuides.map((g) => ({
      id: g.id,
      name: g.name,
      mobileNumber: g.primaryMobile,
      email: g.email,
      status: g.status,
    }));
  },

  // Get single guide by ID
  async get(id: number): Promise<Guide | null> {
    await delay(200);
    return mockGuides.find((g) => g.id === id) ?? null;
  },

  // Create new guide
  async create(data: Omit<Guide, "id">): Promise<Guide> {
    await delay(300);
    const newGuide: Guide = {
      ...data,
      id: nextId++,
    };
    mockGuides.push(newGuide);
    return newGuide;
  },

  // Update guide
  async update(id: number, data: Partial<Guide>): Promise<Guide> {
    await delay(300);
    const idx = mockGuides.findIndex((g) => g.id === id);
    if (idx === -1) throw new Error("Guide not found");
    mockGuides[idx] = { ...mockGuides[idx], ...data };
    return mockGuides[idx];
  },

  // Delete guide
  async delete(id: number): Promise<void> {
    await delay(300);
    mockGuides = mockGuides.filter((g) => g.id !== id);
  },

  // Toggle status
  async toggleStatus(id: number, status: 0 | 1): Promise<void> {
    await delay(200);
    const idx = mockGuides.findIndex((g) => g.id === id);
    if (idx !== -1) {
      mockGuides[idx].status = status;
    }
  },

  // Update pricebook
  async updatePricebook(id: number, pricebook: GuidePricebook): Promise<void> {
    await delay(300);
    const idx = mockGuides.findIndex((g) => g.id === id);
    if (idx !== -1) {
      mockGuides[idx].pricebook = pricebook;
    }
  },

  // Add review
  async addReview(id: number, review: Omit<GuideReview, "id">): Promise<GuideReview> {
    await delay(200);
    const idx = mockGuides.findIndex((g) => g.id === id);
    if (idx === -1) throw new Error("Guide not found");
    
    const newReview: GuideReview = {
      ...review,
      id: `rev${nextReviewId++}`,
    };
    mockGuides[idx].reviews.push(newReview);
    return newReview;
  },

  // Delete review
  async deleteReview(guideId: number, reviewId: string): Promise<void> {
    await delay(200);
    const idx = mockGuides.findIndex((g) => g.id === guideId);
    if (idx !== -1) {
      mockGuides[idx].reviews = mockGuides[idx].reviews.filter((r) => r.id !== reviewId);
    }
  },
};
